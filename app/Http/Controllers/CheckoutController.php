<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StockHistory;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $settings = Setting::allAsArray();
        $cart = $request->session()->get('cart', ['items' => []]);
        $items = collect($cart['items'] ?? [])->values();

        if (! Arr::get($cart, 'table_id') || $items->isEmpty()) {
            $request->session()->flash('toast', ['type' => 'error', 'message' => 'Keranjang masih kosong.']);
            return redirect()->route('cart.show');
        }

        $table = Table::query()->findOrFail((int) $cart['table_id']);
        $subtotal = (int) $items->sum(fn ($i) => ((int) ($i['price'] ?? 0)) * ((int) ($i['qty'] ?? 0)));
        $taxPercent = Setting::number('tax_percent', 0);
        $servicePercent = Setting::number('service_percent', 0);
        $taxAmount = (int) round($subtotal * ($taxPercent / 100));
        $serviceAmount = (int) round($subtotal * ($servicePercent / 100));
        $grandTotal = $subtotal + $taxAmount + $serviceAmount;

        return view('customer.checkout', [
            'settings' => $settings,
            'cart' => $cart,
            'items' => $items,
            'table' => $table,
            'subtotal' => $subtotal,
            'taxPercent' => $taxPercent,
            'servicePercent' => $servicePercent,
            'taxAmount' => $taxAmount,
            'serviceAmount' => $serviceAmount,
            'grandTotal' => $grandTotal,
            'transferProofRequired' => Setting::bool('transfer_proof_required', false),
        ]);
    }

    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', ['items' => []]);
        $items = collect($cart['items'] ?? [])->values();

        if (! Arr::get($cart, 'table_id') || $items->isEmpty()) {
            return redirect()->route('cart.show');
        }

        $transferProofRequired = Setting::bool('transfer_proof_required', false);

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:60'],
            'customer_note' => ['nullable', 'string', 'max:200'],
            'payment_method' => ['required', Rule::in([Payment::METHOD_CASH, Payment::METHOD_QRIS])],
            'qris_proof' => [
                Rule::requiredIf(fn () => $transferProofRequired && $request->input('payment_method') === Payment::METHOD_QRIS),
                'nullable',
                'image',
                'max:2048',
            ],
        ]);

        $productIds = $items->pluck('product_id')->map(fn ($v) => (int) $v)->unique()->values();
        $products = Product::query()
            ->active()
            ->whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $normalized = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $qty = (int) ($item['qty'] ?? 0);

            $product = $products->get($productId);
            if (! $product || $qty <= 0) {
                continue;
            }

            if ($qty > (int) $product->stock) {
                $request->session()->flash('toast', ['type' => 'error', 'message' => "Stok {$product->name} tidak cukup."]);
                return redirect()->route('cart.show');
            }

            $lineSubtotal = (int) $product->price * $qty;
            $subtotal += $lineSubtotal;

            $normalized[] = [
                'product' => $product,
                'qty' => $qty,
                'note' => (string) ($item['note'] ?? ''),
                'subtotal' => $lineSubtotal,
            ];
        }

        if (count($normalized) === 0) {
            $request->session()->flash('toast', ['type' => 'error', 'message' => 'Keranjang masih kosong.']);
            return redirect()->route('cart.show');
        }

        $taxPercent = Setting::number('tax_percent', 0);
        $servicePercent = Setting::number('service_percent', 0);
        $taxAmount = (int) round($subtotal * ($taxPercent / 100));
        $serviceAmount = (int) round($subtotal * ($servicePercent / 100));
        $grandTotal = $subtotal + $taxAmount + $serviceAmount;

        $proofPath = null;
        if ($request->hasFile('qris_proof')) {
            $proofPath = $request->file('qris_proof')->store('payments', 'public');
        }

        $order = DB::transaction(function () use ($cart, $data, $normalized, $subtotal, $taxPercent, $taxAmount, $servicePercent, $serviceAmount, $grandTotal, $proofPath) {
            $order = Order::create([
                'table_id' => (int) $cart['table_id'],
                'customer_name' => $data['customer_name'],
                'customer_note' => $data['customer_note'] ?? null,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'tax_percent' => (string) $taxPercent,
                'tax_amount' => $taxAmount,
                'service_percent' => (string) $servicePercent,
                'service_amount' => $serviceAmount,
                'grand_total' => $grandTotal,
            ]);

            foreach ($normalized as $row) {
                $product = $row['product'];
                $qty = (int) $row['qty'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => (int) $product->price,
                    'qty' => $qty,
                    'note' => $row['note'] ?: null,
                    'subtotal' => (int) $row['subtotal'],
                ]);

                $before = (int) $product->stock;
                $after = $before - $qty;
                $product->update(['stock' => $after]);

                StockHistory::create([
                    'product_id' => $product->id,
                    'created_by_user_id' => null,
                    'type' => StockHistory::TYPE_OUT,
                    'qty' => $qty,
                    'stock_before' => $before,
                    'stock_after' => $after,
                    'note' => "Order {$order->invoice}",
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'status' => Payment::STATUS_UNPAID,
                'amount' => $grandTotal,
                'qris_proof_path' => $proofPath,
            ]);

            return $order->load(['items', 'table', 'payment']);
        });

        $cart['items'] = [];
        $request->session()->put('cart', $cart);

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Pesanan berhasil dibuat.']);
        return redirect()->route('order.show', ['invoice' => $order->invoice]);
    }
}
