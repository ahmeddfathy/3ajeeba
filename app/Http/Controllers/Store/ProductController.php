<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        // Collection/filter stay as page context; category is only a sub-filter.
        $title = match (true) {
            filled($search) && ! filled($collectionModel) && ! filled($categoryModel) => 'نتائج البحث: ' . $search,
            $filter === 'featured' => 'الأفضل',
            $filter === 'new' => 'جديدنا',
            filled($collectionModel) => $collectionModel->name,
            filled($categoryModel) => $categoryModel->name,
            filled($search) => 'نتائج البحث: ' . $search,
            default => 'جميع المنتجات',
        };

        $lead = match (true) {
            filled($collectionModel) && filled($collectionModel->description) => $collectionModel->description,
            filled($categoryModel) && filled($categoryModel->description) => $categoryModel->description,
            default => 'اكتشفي تشكيلاتنا المختارة وتصاميم تجمع بين الرقي والراحة.',
        };

        $bannerImage = $collectionModel?->image_url ?: $categoryModel?->image_url;
        $bannerAlt = $collectionModel?->name ?: $categoryModel?->name ?: $title;
        $eyebrow = match (true) {
            filled($collectionModel) => 'مجموعة',
            filled($categoryModel) => 'قسم',
            default => 'متجر عجيبة',
        };

        return view('store.products.index', [
            'products' => $products,
            'title' => $title,
            'lead' => $lead,
            'eyebrow' => $eyebrow,
            'search' => $search,
            'filter' => $filter,
            'category' => $categorySlug,
            'collection' => $collectionSlug,
            'categories' => $categories,
            'bannerImage' => $bannerImage,
            'bannerAlt' => $bannerAlt,
        ]);
    }

    public function suggest(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $currency = config('store.currency', 'ر.س');
        $categorySlug = trim((string) $request->query('category', ''));
        $collectionSlug = trim((string) $request->query('collection', ''));
        $filter = trim((string) $request->query('filter', ''));

        $categoryModel = $categorySlug
            ? Category::active()->where('slug', $categorySlug)->first()
            : null;
        $collectionModel = $collectionSlug
            ? Collection::active()->where('slug', $collectionSlug)->first()
            : null;

        $scoped = filled($categoryModel) || filled($collectionModel) || in_array($filter, ['featured', 'new'], true);

        $productsQuery = Product::active()->with('activeVariants')->reorder();

        if (filled($categoryModel)) {
            $productsQuery->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryModel->id));
        }

        if (filled($collectionModel)) {
            $productsQuery->whereHas('collections', fn ($q) => $q->where('collections.id', $collectionModel->id));
        }

        if ($filter === 'featured') {
            $productsQuery->where('is_featured', true);
        } elseif ($filter === 'new') {
            $productsQuery->where(function ($builder) {
                $builder->where('ribbon_label', 'like', '%جديد%')
                    ->orWhere('created_at', '>=', now()->subDays(60));
            });
        }

        if ($q !== '') {
            $like = '%' . $q . '%';
            $productsQuery->where(function ($builder) use ($like) {
                $builder->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('ribbon_label', 'like', $like);
            })->orderByRaw('CASE WHEN name LIKE ? THEN 0 WHEN name LIKE ? THEN 1 ELSE 2 END', [
                $q . '%',
                '%' . $q . '%',
            ])->orderBy('sort_order');
        } else {
            $productsQuery->orderByDesc('is_featured')->orderBy('sort_order')->latest();
        }

        $products = $productsQuery->take(6)->get()->map(fn (Product $product) => [
            'type' => 'product',
            'id' => $product->id,
            'title' => $product->name,
            'subtitle' => number_format($product->display_price) . ' ' . $currency,
            'image' => $product->image_url,
            'url' => route('products.show', $product),
        ]);

        $categories = collect();
        $collections = collect();

        // Outside a scoped catalog page, also suggest categories/collections.
        if (! $scoped) {
            $categoriesQuery = Category::active();
            $collectionsQuery = Collection::active();

            if ($q !== '') {
                $like = '%' . $q . '%';
                $categoriesQuery->where(function ($builder) use ($like) {
                    $builder->where('name', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                });
                $collectionsQuery->where(function ($builder) use ($like) {
                    $builder->where('name', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                });
            }

            $categories = $categoriesQuery->take(4)->get()->map(fn (Category $category) => [
                'type' => 'category',
                'id' => $category->id,
                'title' => $category->name,
                'subtitle' => 'قسم',
                'image' => $category->image_url,
                'url' => route('products.index', ['category' => $category->slug]),
            ]);

            $collections = $collectionsQuery->take(4)->get()->map(fn (Collection $collection) => [
                'type' => 'collection',
                'id' => $collection->id,
                'title' => $collection->name,
                'subtitle' => 'مجموعة',
                'image' => $collection->image_url,
                'url' => route('products.index', ['collection' => $collection->slug]),
            ]);
        }

        $contextParams = array_filter([
            'category' => $categorySlug ?: null,
            'collection' => $collectionSlug ?: null,
            'filter' => $filter ?: null,
            'q' => $q !== '' ? $q : null,
        ], fn ($value) => filled($value));

        $scopeLabel = match (true) {
            filled($collectionModel) => $collectionModel->name,
            filled($categoryModel) => $categoryModel->name,
            $filter === 'featured' => 'الأفضل',
            $filter === 'new' => 'جديدنا',
            default => null,
        };

        return response()->json([
            'q' => $q,
            'scoped' => $scoped,
            'scope_label' => $scopeLabel,
            'products' => $products,
            'categories' => $categories,
            'collections' => $collections,
            'see_all_url' => route('products.index', $contextParams),
            'see_all_label' => match (true) {
                $q !== '' && $scopeLabel => 'عرض نتائج «' . Str::limit($q, 20) . '» في ' . $scopeLabel,
                $q !== '' => 'عرض كل النتائج لـ «' . Str::limit($q, 28) . '»',
                $scopeLabel => 'تصفح منتجات ' . $scopeLabel,
                default => 'تصفح جميع المنتجات',
            },
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
