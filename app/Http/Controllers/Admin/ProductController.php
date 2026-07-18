<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('sort_order')->get();

        $stats = [
            ['label' => 'إجمالي المنتجات', 'value' => $products->count(), 'icon' => 'bi-box-seam', 'tone' => 'brand'],
            ['label' => 'نشطة', 'value' => $products->where('is_active', true)->count(), 'icon' => 'bi-check-circle', 'tone' => 'success'],
            ['label' => 'مخفية', 'value' => $products->where('is_active', false)->count(), 'icon' => 'bi-eye-slash', 'tone' => 'muted'],
            ['label' => 'مميزة', 'value' => $products->where('is_featured', true)->count(), 'icon' => 'bi-star', 'tone' => 'warn'],
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        $product = new Product();
        $product->setRelation('variants', collect());
        $product->setRelation('categories', collect());
        $product->setRelation('collections', collect());

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
            'collections' => Collection::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $variants = $this->validatedVariants($request);

        if ($error = $this->pricingError($data, $variants)) {
            return back()->withErrors($error)->withInput();
        }

        $categoryIds = $request->input('category_ids', []);
        $collectionIds = $request->input('collection_ids', []);

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request);
        } else {
            $data['image'] = '';
        }

        $data = $this->applyVariantPricing($data, $variants);

        DB::transaction(function () use ($data, $variants, $categoryIds, $collectionIds) {
            $product = Product::create($data);
            $this->syncVariants($product, $variants);
            $product->categories()->sync($categoryIds);
            $product->collections()->sync($collectionIds);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $product->load(['variants', 'categories', 'collections']);

        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
            'collections' => Collection::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $variants = $this->validatedVariants($request);

        if ($error = $this->pricingError($data, $variants)) {
            return back()->withErrors($error)->withInput();
        }

        $categoryIds = $request->input('category_ids', []);
        $collectionIds = $request->input('collection_ids', []);

        if ($request->hasFile('image')) {
            $this->deleteOldImage($product->image);
            $data['image'] = $this->handleImageUpload($request);
        } else {
            unset($data['image']);
        }

        $data = $this->applyVariantPricing($data, $variants);

        DB::transaction(function () use ($product, $data, $variants, $categoryIds, $collectionIds) {
            $product->update($data);
            $this->syncVariants($product, $variants);
            $product->categories()->sync($categoryIds);
            $product->collections()->sync($collectionIds);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $this->deleteOldImage($product->image);
        $product->delete();

        return back()->with('success', 'تم حذف المنتج');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'integer', 'exists:products,id'],
            'orders.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->orders as $item) {
            Product::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    // ─── Helpers ──────────────────────────────────────────

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:100000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'price' => ['nullable', 'integer', 'min:1'],
            'original_price' => ['nullable', 'integer', 'min:1'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'ribbon_label' => ['nullable', 'string', 'max:40'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'collection_ids' => ['nullable', 'array'],
            'collection_ids.*' => ['integer', 'exists:collections,id'],
            'variants' => ['nullable', 'array'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.size' => ['nullable', 'string', 'max:40'],
            'variants.*.color' => ['nullable', 'string', 'max:40'],
            'variants.*.color_hex' => ['nullable', 'string', 'max:20'],
            'variants.*.price' => ['nullable', 'integer', 'min:1'],
            'variants.*.original_price' => ['nullable', 'integer', 'min:1'],
            'variants.*.sku' => ['nullable', 'string', 'max:60'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'variants.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($data['variants'], $data['category_ids'], $data['collection_ids']);

        $data['price'] = isset($data['price']) && $data['price'] !== '' ? (int) $data['price'] : null;
        $data['original_price'] = isset($data['original_price']) && $data['original_price'] !== ''
            ? (int) $data['original_price']
            : null;

        return $data;
    }

    /** لازم سعر أساسي أو فاريانت واحد على الأقل بسعر */
    private function pricingError(array $data, array $variants): ?array
    {
        if (! empty($variants) || ! empty($data['price'])) {
            return null;
        }

        return [
            'price' => 'أدخلي سعراً أساسياً للمنتج، أو أضيفي فاريانت واحد على الأقل بسعر.',
            'variants' => 'أضيفي فاريانت بسعر، أو حددي السعر الأساسي فوق.',
        ];
    }

    private function applyVariantPricing(array $data, array $variants): array
    {
        if (! empty($variants)) {
            $data['price'] = collect($variants)->min('price');
        }

        return $data;
    }

    private function validatedVariants(Request $request): array
    {
        $rows = $request->input('variants', []);
        if (! is_array($rows)) {
            return [];
        }

        $variants = [];
        foreach (array_values($rows) as $index => $row) {
            $price = isset($row['price']) && $row['price'] !== '' ? (int) $row['price'] : null;
            $size = trim((string) ($row['size'] ?? ''));
            $color = trim((string) ($row['color'] ?? ''));

            // تجاهل الصفوف الفارغة تمامًا
            if ($price === null && $size === '' && $color === '') {
                continue;
            }

            if ($price === null || $price < 1) {
                continue;
            }

            if ($size === '' && $color === '') {
                continue;
            }

            $variants[] = [
                'id' => isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null,
                'size' => $size !== '' ? $size : null,
                'color' => $color !== '' ? $color : null,
                'color_hex' => ! empty($row['color_hex']) ? trim((string) $row['color_hex']) : null,
                'price' => $price,
                'original_price' => isset($row['original_price']) && $row['original_price'] !== ''
                    ? (int) $row['original_price']
                    : null,
                'sku' => ! empty($row['sku']) ? trim((string) $row['sku']) : null,
                'stock' => isset($row['stock']) && $row['stock'] !== '' ? (int) $row['stock'] : null,
                'is_active' => ! array_key_exists('is_active', $row) || (bool) $row['is_active'],
                'sort_order' => isset($row['sort_order']) ? (int) $row['sort_order'] : $index,
            ];
        }

        return $variants;
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $keptIds = [];

        foreach ($variants as $index => $variant) {
            $payload = [
                'size' => $variant['size'],
                'color' => $variant['color'],
                'color_hex' => $variant['color_hex'],
                'price' => $variant['price'],
                'original_price' => $variant['original_price'],
                'sku' => $variant['sku'],
                'stock' => $variant['stock'],
                'is_active' => $variant['is_active'],
                'sort_order' => $variant['sort_order'] ?? $index,
            ];

            if (! empty($variant['id'])) {
                $existing = ProductVariant::where('product_id', $product->id)
                    ->where('id', $variant['id'])
                    ->first();

                if ($existing) {
                    $existing->update($payload);
                    $keptIds[] = $existing->id;
                    continue;
                }
            }

            $created = $product->variants()->create($payload);
            $keptIds[] = $created->id;
        }

        if (empty($keptIds)) {
            $product->variants()->delete();
        } else {
            $product->variants()->whereNotIn('id', $keptIds)->delete();
        }
    }

    private function handleImageUpload(Request $request): string
    {
        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $directory = public_path('assets/images/products');

        File::ensureDirectoryExists($directory);
        $file->move($directory, $filename);

        return 'assets/images/products/' . $filename;
    }

    private function deleteOldImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (str_starts_with($path, 'assets/images/products/')) {
            $full = public_path($path);
            if (file_exists($full)) {
                unlink($full);
            }
        }
    }
}
