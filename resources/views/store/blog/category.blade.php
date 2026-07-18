@extends('layouts.store')

@section('title', $category->name . ' | المدونة | ' . config('store.name'))
@section('meta_description', $category->description ?: ('مقالات تصنيف ' . $category->name . ' من مدونة عجيبة'))
@section('active_nav', 'blog')

@section('content')
<section class="store-section store-blog" aria-labelledby="blog-title">
    <div class="store-blog__hero">
        <p class="store-catalog__eyebrow">تصنيف</p>
        <h1 id="blog-title" class="store-section__title">{{ $category->name }}</h1>
        @if($category->description)
            <p class="store-catalog__lead">{{ $category->description }}</p>
        @endif
    </div>

    <div class="store-blog__toolbar">
        <div class="store-blog__filters" role="navigation" aria-label="تصنيفات المدونة">
            <a href="{{ route('blog.index') }}" class="store-blog__filter">الكل</a>
            @foreach($categories as $cat)
                <a href="{{ route('blog.category', $cat) }}"
                   class="store-blog__filter {{ $cat->id === $category->id ? 'is-active' : '' }}">
                    {{ $cat->name }}
                    @if($cat->blogs_count)
                        <span>{{ $cat->blogs_count }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    @if($blogs->count())
        <div class="store-blog__grid">
            @foreach($blogs as $post)
                @include('store.blog._card', ['post' => $post])
            @endforeach
        </div>

        @if($blogs->hasPages())
            <div class="store-catalog__pagination">
                {{ $blogs->links() }}
            </div>
        @endif
    @else
        <p class="store-blog__empty">لا مقالات في هذا التصنيف بعد.</p>
    @endif
</section>
@endsection
