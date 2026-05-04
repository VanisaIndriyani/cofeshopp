<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StockHistory;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminPosController extends Controller
{
    public function index(Request $request)
    {
        $tables = Table::query()->active()->orderBy('code')->get();
        $products = Product::query()->active()->with('category')->orderBy('name')->get();

        return view('admin.pos.index', [
            'tables' => $tables,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'table_id' => ['required', 'integer', 'exists:tables,id'],
            'customer_name' => ['nullable', 'string', 'max:60'],
            'payment_method' => ['required', Rule::in([Payment::METHOD_CASH, Payment::METHOD_QRIS])],
            'mark_paid' => ['nullable', 'boolean'],
            'items_json' => ['required', 'string'],
        ]);

        $items = json_decode($data['items_json'], true);
        if (! is_array($items) || count($items) === 0) {
            $request->session()->flash('toast', ['type' => 'error', 'message' => 'Item POS masih kosong.']);
            return back();
        }

        $productIds = collect($items)->pluck('product_id')->map(fn ($v) => (int) $v)->unique()->values();

        try {
            $order = DB::transaction(function () use ($request, $data, $items, $productIds) {
                $products = Product::query()
                    ->active()
                    ->whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

            $subtotal = 0;
            $normalized = [];

            foreach ($items as $it) {
                $productId = (int) ($it['product_id'] ?? 0);
                $qty = (int) ($it['qty'] ?? 0);
                $note = isset($it['note']) ? (string) $it['note'] : '';

                $product = $products->get($productId);
                if (! $product || $qty <= 0) {
                    continue;
                }
                if ($qty > (int) $product->stock) {
                    throw new \RuntimeException("Stok {$product->name} tidak cukup.");
                }

                $lineSubtotal = (int) $product->price * $qty;
                $subtotal += $lineSubtotal;

                $normalized[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'note' => $note,
                    'subtotal' => $lineSubtotal,
                ];
            }

            if (count($normalized) === 0) {
                throw new \RuntimeException('Item POS masih kosong.');
            }

            $taxPercent = Setting::number('tax_percent', 0);
            $servicePercent = Setting::number('service_percent', 0);
            $taxAmount = (int) round($subtotal * ($taxPercent / 100));
            $serviceAmount = (int) round($subtotal * ($servicePercent / 100));
            $grandTotal = $subtotal + $taxAmount + $serviceAmount;

            $order = Order::create([
                'table_id' => (int) $data['table_id'],
                'created_by_user_id' => $request->user()->id,
                'customer_name' => $data['customer_name'] ?: 'Walk-in',
                'status' => Order::STATUS_PROCESSING,
                'subtotal' => $subtotal,
                'tax_percent' => (string) $taxPercent,
                'tax_amount' => $taxAmount,
                'service_percent' => (string) $servicePercent,
                'service_amount' => $serviceAmount,
                'grand_total' => $grandTotal,
                'confirmed_at' => Carbon::now(),
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
                    'created_by_user_id' => $request->user()->id,
                    'type' => StockHistory::TYPE_OUT,
                    'qty' => $qty,
                    'stock_before' => $before,
                    'stock_after' => $after,
                    'note' => "POS {$order->invoice}",
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'status' => $request->boolean('mark_paid') ? Payment::STATUS_PAID : Payment::STATUS_UNPAID,
                'amount' => $grandTotal,
                'paid_at' => $request->boolean('mark_paid') ? Carbon::now() : null,
            ]);

                return $order;
            });
        } catch (\Throwable $e) {
            $request->session()->flash('toast', ['type' => 'error', 'message' => $e->getMessage()]);
            return back();
        }

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Order POS berhasil dibuat.']);
        return redirect()->route('admin.orders.index', ['q' => $order->invoice]);
    }
}
