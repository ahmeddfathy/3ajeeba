@extends('layouts.store')

@php
    $page = config('store.blog_page', []);
    $blogImage = $page['image'] ?? config('store.hero.image');
@endphp

@section('title', $category->name . ' | المدونة | ' . config('store.name'))
@section('meta_description', $category->description ?: ('مقالات تصنيف ' . $category->name . ' من مدونة عجيبة'))
@section('active_nav', 'blog')

@section('content')
<section class="store-blog" aria-labelledby="blog-title">
    <header class="store-page-hero store-page-hero--blog store-page-hero--short">
        @if ($blogImage)
            <img
                class="store-page-hero__media"
                src="{{ asset($blogImage) }}"
                alt="{{ $category->name }}"
                width="1600"
                height="500"
                loading="eager"
                decoding="async"
            >
        @endif
        <div class="store-page-hero__shade" aria-hidden="true"></div>
        <div class="store-page-hero__content">
            <a href="{{ route('blog.index') }}" class="store-page-hero__back">← العودة للمدونة</a>
            <p class="store-page-hero__eyebrow">تصنيف</p>
            <h1 id="blog-title" class="store-page-hero__title">{{ $category->name }}</h1>
            @if($category->description)
                <p class="store-page-hero__text">{{ $category->description }}</p>
            @endif
        </div>
    </header>

    <div class="store-blog__wrap">
        @include('store.blog._toolbar', ['activeCategory' => $category])

        @if($blogs->count())
            <div class="store-blog__grid">
                @foreach($blogs as $post)
                    @include('store.blog._card', ['post' => $post])
                @endforeach
            </div>

            @if($blogs->hasPages())
                <div class="store-blog__pagination">
                    {{ $blogs->links() }}
                </div>
            @endif
        @else
            <div class="store-blog__empty">
                <p>لا مقالات في هذا التصنيف بعد.</p>
                <a href="{{ route('blog.index') }}" class="store-btn store-btn--primary">عرض كل المقالات</a>
            </div>
        @endif
    </div>
</section>
@endsection
