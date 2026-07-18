<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Support\ArabicSlug;
use App\Support\UploadsImages;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use UploadsImages;

    public function index(Request $request)
    {
        $query = Blog::with('category')->orderByDesc('created_at');

        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $blogs = $query->paginate(12)->withQueryString();
        $categories = BlogCategory::orderBy('name')->get();

        $stats = [
            ['label' => 'إجمالي المقالات', 'value' => Blog::count(), 'icon' => 'bi-journal-text', 'tone' => 'brand'],
            ['label' => 'منشورة', 'value' => Blog::where('is_published', true)->count(), 'icon' => 'bi-check-circle', 'tone' => 'success'],
            ['label' => 'مسودات', 'value' => Blog::where('is_published', false)->count(), 'icon' => 'bi-file-earmark', 'tone' => 'muted'],
            ['label' => 'التصنيفات', 'value' => BlogCategory::count(), 'icon' => 'bi-tags', 'tone' => 'warn'],
        ];

        return view('admin.blogs.index', compact('blogs', 'categories', 'stats'));
    }

    public function create()
    {
        return view('admin.blogs.form', [
            'blog' => new Blog(),
            'categories' => BlogCategory::active()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['featured_image'] = $this->uploadPublicImage($request, 'featured_image', 'assets/images/blog') ?? null;

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'تم إضافة المقال');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.form', [
            'blog' => $blog,
            'categories' => BlogCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $this->validated($request, $blog);

        if ($request->hasFile('featured_image')) {
            $this->deletePublicImage($blog->featured_image, 'assets/images/blog/');
            $data['featured_image'] = $this->uploadPublicImage($request, 'featured_image', 'assets/images/blog');
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'تم تحديث المقال');
    }

    public function destroy(Blog $blog)
    {
        $this->deletePublicImage($blog->featured_image, 'assets/images/blog/');
        $blog->delete();

        return back()->with('success', 'تم حذف المقال');
    }

    public function toggleStatus(Blog $blog)
    {
        $blog->is_published = ! $blog->is_published;
        if ($blog->is_published && ! $blog->published_at) {
            $blog->published_at = now();
        }
        $blog->save();

        return back()->with('success', $blog->is_published ? 'تم نشر المقال' : 'تم إخفاء المقال');
    }

    private function validated(Request $request, ?Blog $blog = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blogs,slug,'.($blog?->id ?: 'NULL')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:200000'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'string', 'max:500'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
        ]);

        $data['slug'] = $data['slug']
            ? ArabicSlug::make($data['slug'])
            : ArabicSlug::make($data['title']);

        if (Blog::where('slug', $data['slug'])->when($blog, fn ($q) => $q->where('id', '!=', $blog->id))->exists()) {
            $data['slug'] .= '-'.uniqid();
        }

        $data['is_published'] = $request->boolean('is_published');
        $data['blog_category_id'] = $data['blog_category_id'] ?: null;

        if (! empty($data['tags'])) {
            $data['tags'] = collect(explode(',', $data['tags']))
                ->map(fn ($tag) => trim($tag))
                ->filter()
                ->values()
                ->all();
        } else {
            $data['tags'] = [];
        }

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (! $data['is_published']) {
            $data['published_at'] = $data['published_at'] ?? null;
        }

        return $data;
    }
}
