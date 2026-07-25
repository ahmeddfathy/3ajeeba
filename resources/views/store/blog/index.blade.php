@extends('layouts.store')

@php
    $page = config('store.blog_page', []);
    $blogImage = $page['image'] ?? config('store.hero.image');
    $blogImageAlt = $page['image_alt'] ?? 'مدونة عجيبة';
@endphp

@section('title', 'المدونة | ' . config('store.name'))
@section('meta_description', 'مقالات ونصائح من عجيبة عن الأزياء المحتشمة، التنسيق، والعناية بالأناقة اليومية.')
@section('active_nav', 'blog')

@section('content')
<section class="store-blog" aria-labelledby="blog-title">
    <header class="store-page-hero store-page-hero--blog">
        @if ($blogImage)
            <img
                class="store-page-hero__media"
                src="{{ asset($blogImage) }}"
                alt="{{ $blogImageAlt }}"
                width="1600"
                height="700"
                loading="eager"
                decoding="async"
            >
        @endif
        <div class="store-page-hero__shade" aria-hidden="true"></div>
        <div class="store-page-hero__content">
            <p class="store-page-hero__eyebrow">من عالم عجيبة</p>
            <h1 id="blog-title" class="store-page-hero__title">المدونة</h1>
            <p class="store-page-hero__text">
                {{ $page['lead'] ?? 'نصائح ستايل، أفكار تنسيق، وكل ما يساعدكِ تختاري إطلالتك بثقة.' }}
            </p>
        </div>
    </header>

    <div class="store-blog__wrap">
        @include('store.blog._toolbar', ['activeCategory' => null])

        @if(isset($featured) && $featured)
            @include('store.blog._card', ['post' => $featured, 'featured' => true])
        @endif

        @if($blogs->count())
            <div class="store-blog__grid {{ isset($featured) && $featured ? 'store-blog__grid--after-featured' : '' }}">
                @foreach($blogs as $post)
                    @include('store.blog._card', ['post' => $post])
                @endforeach
            </div>

            @if($blogs->hasPages())
                <div class="store-blog__pagination">
                    {{ $blogs->links() }}
                </div>
            @endif
        @elseif(!isset($featured) || ! $featured)
            <div class="store-blog__empty">
                <p>لا توجد مقالات حالياً — عودي قريباً.</p>
            </div>
        @endif
    </div>
</section>
@endsection
