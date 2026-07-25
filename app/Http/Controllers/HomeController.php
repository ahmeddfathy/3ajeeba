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
        $bestSellers = Product::active()
            ->with('activeVariants')
            ->latest()
            ->take(8)
            ->get();

        return view('index', [
            'bestSellers' => $bestSellers,
            'categories' => Category::active()->get(),
            'collections' => Collection::active()->get(),
            'store' => config('store'),
        ]);
    }
}
