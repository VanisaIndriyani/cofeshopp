<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $category = (string) $request->query('category', '');

        $query = Product::query()->with('category')->orderByDesc('created_at');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category !== '') {
            $query->whereHas('category', fn ($c) => $c->where('slug', $category));
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'q' => $q,
            'category' => $category,
        ]);
    }

    public function create()
    {
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
        }

        Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'stock' => $data['stock'],
            'low_stock_threshold' => $data['low_stock_threshold'] ?? 5,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'photo_path' => $path,
        ]);

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Menu berhasil ditambahkan.']);
        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.form', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = $product->photo_path;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
            if ($product->photo_path) {
                Storage::disk('public')->delete($product->photo_path);
            }
        }

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'stock' => $data['stock'],
            'low_stock_threshold' => $data['low_stock_threshold'] ?? $product->low_stock_threshold,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'photo_path' => $path,
        ]);

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Menu berhasil diperbarui.']);
        return redirect()->route('admin.products.index');
    }

    public function destroy(Request $request, Product $product)
    {
        if ($product->photo_path) {
            Storage::disk('public')->delete($product->photo_path);
        }

        $product->delete();

        $request->session()->flash('toast', ['type' => 'success', 'message' => 'Menu berhasil dihapus.']);
        return back();
    }
}
