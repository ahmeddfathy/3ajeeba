@extends('layouts.store')

@section('title', ($blog->meta_title ?: $blog->title) . ' | ' . config('store.name'))
@section('meta_description', $blog->meta_description ?: $blog->excerpt_text)
@section('active_nav', 'blog')

@section('content')
<article class="store-blog-post">
    <header class="store-page-hero store-page-hero--blog store-page-hero--post">
        @if($blog->featured_image_url)
            <img
                class="store-page-hero__media"
                src="{{ $blog->featured_image_url }}"
                alt="{{ $blog->featured_image_alt ?: $blog->title }}"
                width="1600"
                height="800"
                loading="eager"
                decoding="async"
            >
        @endif
        <div class="store-page-hero__shade" aria-hidden="true"></div>
        <div class="store-page-hero__content">
            <a href="{{ route('blog.index') }}" class="store-page-hero__back">← العودة للمدونة</a>
            @if($blog->category)
                <a href="{{ route('blog.category', $blog->category) }}" class="store-page-hero__cat">{{ $blog->category->name }}</a>
            @endif
            <h1 class="store-page-hero__title">{{ $blog->title }}</h1>
            <p class="store-page-hero__meta">
                <time datetime="{{ optional($blog->published_at ?? $blog->created_at)->toDateString() }}">
                    {{ optional($blog->published_at ?? $blog->created_at)->translatedFormat('d F Y') }}
                </time>
            </p>
        </div>
    </header>

    <div class="store-blog-post__wrap">
        @if($blog->excerpt_text)
            <p class="store-blog-post__excerpt">{{ $blog->excerpt_text }}</p>
        @endif

        @if($blog->content)
            <div class="store-blog-post__body prose-store">
                {!! $blog->content !!}
            </div>
        @endif

        @if(is_array($blog->tags) && count($blog->tags))
            <ul class="store-blog-post__tags" aria-label="وسوم المقال">
                @foreach($blog->tags as $tag)
                    <li>{{ $tag }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</article>

@if($related->count())
    <section class="store-blog-related" aria-labelledby="related-title">
        <div class="store-blog-related__inner">
            <div class="store-blog-related__head">
                <h2 id="related-title">مقالات ذات صلة</h2>
                <p>اقرأي المزيد من نصائح عجيبة</p>
            </div>
            <div class="store-blog__grid store-blog__grid--related">
                @foreach($related as $post)
                    @include('store.blog._card', ['post' => $post])
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
