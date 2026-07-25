<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->with('category')->orderByDesc('published_at')->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%");
            });
        }

        $featured = null;
        if (
            ! $request->filled('search')
            && ! $request->filled('category')
            && (int) $request->get('page', 1) === 1
        ) {
            $featured = (clone $query)->first();
        }

        if ($featured) {
            $query->where('id', '!=', $featured->id);
        }

        $blogs = $query->paginate(9)->withQueryString();
        $categories = BlogCategory::active()->withCount(['blogs' => fn ($q) => $q->published()])->get();

        return view('store.blog.index', compact('blogs', 'categories', 'featured'));
    }

    public function category(BlogCategory $category)
    {
        if (! $category->is_active) {
            abort(404);
        }

        $blogs = Blog::published()
            ->where('blog_category_id', $category->id)
            ->with('category')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        $categories = BlogCategory::active()->withCount(['blogs' => fn ($q) => $q->published()])->get();

        return view('store.blog.category', compact('blogs', 'category', 'categories'));
    }

    public function show(Blog $blog)
    {
        if (! $blog->is_published) {
            abort(404);
        }

        $blog->load('category');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_category_id, fn ($q) => $q->where('blog_category_id', $blog->blog_category_id))
            ->with('category')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('store.blog.show', compact('blog', 'related'));
    }
}
