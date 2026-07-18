<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active()->with('activeVariants');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('ribbon_label', 'like', "%{$search}%");
            });
        }

        $filter = $request->query('filter');
        if ($filter === 'featured') {
            $query->where('is_featured', true);
        } elseif ($filter === 'new') {
            $query->where(function ($builder) {
                $builder->where('ribbon_label', 'like', '%جديد%')
                    ->orWhere('created_at', '>=', now()->subDays(60));
            });
        }

        $categorySlug = $request->query('category');
        $categoryModel = null;
        if ($categorySlug) {
            $categoryModel = Category::active()->where('slug', $categorySlug)->first();
            if ($categoryModel) {
                $query->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryModel->id));
            }
        }

        $collectionSlug = $request->query('collection');
        $collectionModel = null;
        if ($collectionSlug) {
            $collectionModel = Collection::active()->where('slug', $collectionSlug)->first();
            if ($collectionModel) {
                $query->whereHas('collections', fn ($q) => $q->where('collections.id', $collectionModel->id));
            }
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->get();

        $title = match (true) {
            filled($search) => 'نتائج البحث: ' . $search,
            $filter === 'featured' => 'الأكثر مبيعًا',
            $filter === 'new' => 'جديدنا',
            filled($categoryModel) => $categoryModel->name,
            filled($collectionModel) => $collectionModel->name,
            default => 'جميع المنتجات',
        };

        return view('store.products.index', [
            'products' => $products,
            'title' => $title,
            'search' => $search,
            'filter' => $filter,
            'category' => $categorySlug,
            'collection' => $collectionSlug,
            'categories' => $categories,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['activeVariants', 'categories', 'collections']);

        $related = Product::active()
            ->with('activeVariants')
            ->where('id', '!=', $product->id)
            ->when(
                $product->categories->isNotEmpty(),
                fn ($q) => $q->whereHas('categories', fn ($cq) => $cq->whereIn('categories.id', $product->categories->pluck('id')))
            )
            ->take(4)
            ->get();

        if ($related->isEmpty()) {
            $related = Product::active()
                ->with('activeVariants')
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();
        }

        return view('store.products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
