<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class LandingController extends Controller
{
    public function index()
    {
        $settings = Setting::allAsArray();

        $featured = Product::query()
            ->active()
            ->where('is_featured', true)
            ->with('category')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('customer.landing', [
            'settings' => $settings,
            'categories' => $categories,
            'featured' => $featured,
        ]);
    }
}
