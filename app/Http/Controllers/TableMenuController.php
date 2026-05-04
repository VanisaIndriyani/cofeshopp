<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Table;
use Illuminate\Http\Request;

class TableMenuController extends Controller
{
    public function show(Request $request, string $code)
    {
        $table = Table::query()->active()->where('code', $code)->firstOrFail();

        $cart = $request->session()->get('cart', []);
        $cart['table_id'] = $table->id;
        $cart['table_code'] = $table->code;
        $cart['items'] = $cart['items'] ?? [];
        $request->session()->put('cart', $cart);

        $settings = Setting::allAsArray();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $query = Product::query()
            ->active()
            ->with('category')
            ->orderBy('name');

        if ($request->filled('q')) {
            $q = trim((string) $request->query('q'));
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $category = (string) $request->query('category');
            $query->whereHas('category', fn ($c) => $c->where('slug', $category));
        }

        $products = $query->paginate(12)->withQueryString();

        return view('customer.menu', [
            'settings' => $settings,
            'table' => $table,
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => (string) $request->query('category', ''),
            'q' => (string) $request->query('q', ''),
        ]);
    }
}
