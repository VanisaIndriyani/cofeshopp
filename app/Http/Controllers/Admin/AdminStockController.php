<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminStockController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $productOptions = Product::query()->orderBy('name')->get(['id', 'name']);

        $histories = StockHistory::query()
            ->with(['product', 'createdBy'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.stocks.index', [
            'products' => $products,
            'productOptions' => $productOptions,
            'histories' => $histories,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in([StockHistory::TYPE_IN, StockHistory::TYPE_OUT, StockHistory::TYPE_ADJUST])],
            'qty' => ['required', 'integer', 'min:1', 'max:99999'],
            'note' => ['nullable', 'string', 'max:160'],
        ]);

        DB::transaction(function () use ($request, $data) {
            $product = Product::query()->lockForUpdate()->findOrFail($data['product_id']);
            $before = (int) $product->stock;
            $qty = (int) $data['qty'];

            if ($data['type'] === StockHistory::TYPE_IN) {
                $after = $before + $qty;
            } elseif ($data['type'] === StockHistory::TYPE_OUT) {
                $after = max(0, $before - $qty);
            } else {
                $after = $qty;
                $qty = abs($after - $before);
            }

            $product->update(['stock' => $after]);

            StockHistory::create([
                'product_id' => $product->id,
                'created_by_user_id' => $request->user()->id,
                'type' => $data['type'],
                'qty' => $qty,
                'stock_before' => $before,
                'stock_after' => $after,
                'note' => $data['note'] ?? null,
            ]);
        });

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Stok berhasil dicatat.']);
        return back();
    }
}
