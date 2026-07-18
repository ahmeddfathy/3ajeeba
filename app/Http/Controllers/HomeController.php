<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featured = Product::active()
            ->with('activeVariants')
            ->where('is_featured', true)
            ->take(10)
            ->get();

        $bestSellers = $featured->isNotEmpty()
            ? $featured
            : Product::active()->with('activeVariants')->take(10)->get();

        return view('index', [
            'bestSellers' => $bestSellers,
            'categories' => Category::active()->get(),
            'collections' => Collection::active()->get(),
            'store' => config('store'),
        ]);
    }
}
