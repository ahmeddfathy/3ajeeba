<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Support\ArabicSlug;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->orderBy('sort_order')->get();

        $stats = [
            ['label' => 'إجمالي التصنيفات', 'value' => $categories->count(), 'icon' => 'bi-tags', 'tone' => 'brand'],
            ['label' => 'ظاهرة', 'value' => $categories->where('is_active', true)->count(), 'icon' => 'bi-eye', 'tone' => 'success'],
            ['label' => 'مخفية', 'value' => $categories->where('is_active', false)->count(), 'icon' => 'bi-eye-slash', 'tone' => 'muted'],
            ['label' => 'مقالات مربوطة', 'value' => $categories->sum('blogs_count'), 'icon' => 'bi-journal-text', 'tone' => 'warn'],
        ];

        return view('admin.blog-categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.blog-categories.form', ['category' => new BlogCategory()]);
    }

    public function store(Request $request)
    {
        BlogCategory::create($this->validated($request));

        return redirect()->route('admin.blog-categories.index')->with('success', 'تم إضافة تصنيف المدونة');
    }

    public function edit(BlogCategory $blog_category)
    {
        return view('admin.blog-categories.form', ['category' => $blog_category]);
    }

    public function update(Request $request, BlogCategory $blog_category)
    {
        $blog_category->update($this->validated($request, $blog_category));

        return redirect()->route('admin.blog-categories.index')->with('success', 'تم تحديث التصنيف');
    }

    public function destroy(BlogCategory $blog_category)
    {
        $blog_category->delete();

        return back()->with('success', 'تم حذف التصنيف');
    }

    private function validated(Request $request, ?BlogCategory $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:blog_categories,slug,'.($category?->id ?: 'NULL')],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ? ArabicSlug::make($data['slug']) : ArabicSlug::make($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
