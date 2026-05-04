<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $settings = Setting::allAsArray();
        $cart = $request->session()->get('cart', ['items' => []]);
        $items = collect($cart['items'] ?? [])->values();
        $subtotal = (int) $items->sum(fn ($i) => ((int) ($i['price'] ?? 0)) * ((int) ($i['qty'] ?? 0)));

        return view('customer.cart', [
            'settings' => $settings,
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
            'note' => ['nullable', 'string', 'max:120'],
        ]);

        $cart = $request->session()->get('cart', ['items' => []]);
        if (! Arr::get($cart, 'table_id') || ! Arr::get($cart, 'table_code')) {
            return $this->respond($request, false, 'Scan QR meja dulu ya.', redirect()->route('landing'));
        }

        $product = Product::query()->active()->findOrFail($data['product_id']);
        $qty = (int) ($data['qty'] ?? 1);

        $items = $cart['items'] ?? [];
        $existing = $items[$product->id] ?? null;
        $nextQty = $qty + (int) ($existing['qty'] ?? 0);
        $nextQty = min($nextQty, max(0, (int) $product->stock));

        $items[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (int) $product->price,
            'qty' => $nextQty,
            'note' => (string) ($data['note'] ?? ($existing['note'] ?? '')),
            'photo_path' => $product->photo_path,
        ];

        $cart['items'] = $items;
        $request->session()->put('cart', $cart);

        return $this->respond($request, true, 'Ditambahkan ke keranjang.', back());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
            'note' => ['nullable', 'string', 'max:120'],
        ]);

        $cart = $request->session()->get('cart', ['items' => []]);
        $items = $cart['items'] ?? [];

        if (! isset($items[$data['product_id']])) {
            return $this->respond($request, false, 'Item tidak ditemukan di keranjang.', back());
        }

        $product = Product::query()->active()->findOrFail($data['product_id']);
        $qty = min((int) $data['qty'], max(0, (int) $product->stock));

        if ($qty <= 0) {
            unset($items[$product->id]);
        } else {
            $items[$product->id]['qty'] = $qty;
            $items[$product->id]['note'] = (string) ($data['note'] ?? ($items[$product->id]['note'] ?? ''));
        }

        $cart['items'] = $items;
        $request->session()->put('cart', $cart);

        return $this->respond($request, true, 'Keranjang diperbarui.', back());
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart = $request->session()->get('cart', ['items' => []]);
        $items = $cart['items'] ?? [];
        unset($items[$data['product_id']]);
        $cart['items'] = $items;
        $request->session()->put('cart', $cart);

        return $this->respond($request, true, 'Item dihapus.', back());
    }

    public function clear(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $cart['items'] = [];
        $request->session()->put('cart', $cart);

        return $this->respond($request, true, 'Keranjang dikosongkan.', back());
    }

    private function respond(Request $request, bool $ok, string $message, $redirect)
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => $ok, 'message' => $message]);
        }

        $request->session()->flash('toast', ['type' => $ok ? 'success' : 'error', 'message' => $message]);
        return $redirect;
    }
}
