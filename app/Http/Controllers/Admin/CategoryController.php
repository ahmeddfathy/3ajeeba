<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\UploadsImages;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use UploadsImages;

    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        $stats = [
            ['label' => 'إجمالي الفئات', 'value' => $categories->count(), 'icon' => 'bi-grid', 'tone' => 'brand'],
            ['label' => 'نشطة', 'value' => $categories->where('is_active', true)->count(), 'icon' => 'bi-check-circle', 'tone' => 'success'],
            ['label' => 'مخفية', 'value' => $categories->where('is_active', false)->count(), 'icon' => 'bi-eye-slash', 'tone' => 'muted'],
            ['label' => 'منتجات مربوطة', 'value' => $categories->sum('products_count'), 'icon' => 'bi-box-seam', 'tone' => 'warn'],
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadPublicImage($request, 'image', 'assets/images/categories') ?? '';

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة الفئة');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);

        if ($request->hasFile('image')) {
            $this->deletePublicImage($category->image, 'assets/images/categories/');
            $data['image'] = $this->uploadPublicImage($request, 'image', 'assets/images/categories');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث الفئة');
    }

    public function destroy(Category $category)
    {
        $this->deletePublicImage($category->image, 'assets/images/categories/');
        $category->delete();

        return back()->with('success', 'تم حذف الفئة');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:categories,slug,' . ($category?->id ?: 'NULL')],
            'description' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
