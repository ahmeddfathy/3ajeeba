@extends('layouts.store')

@section('title', ($blog->meta_title ?: $blog->title) . ' | ' . config('store.name'))
@section('meta_description', $blog->meta_description ?: $blog->excerpt_text)
@section('active_nav', 'blog')

@section('content')
<article class="store-section store-blog-post">
    <header class="store-blog-post__header">
        <a href="{{ route('blog.index') }}" class="store-blog-post__back">← العودة للمدونة</a>

        @if($blog->category)
            <a href="{{ route('blog.category', $blog->category) }}" class="store-blog-post__cat">{{ $blog->category->name }}</a>
        @endif

        <h1 class="store-section__title">{{ $blog->title }}</h1>

        <div class="store-blog-post__meta">
            <time datetime="{{ optional($blog->published_at ?? $blog->created_at)->toDateString() }}">
                {{ optional($blog->published_at ?? $blog->created_at)->translatedFormat('d F Y') }}
            </time>
        </div>
    </header>

    @if($blog->featured_image_url)
        <figure class="store-blog-post__cover">
            <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->featured_image_alt ?: $blog->title }}">
        </figure>
    @endif

    @if($blog->content)
        <div class="store-blog-post__body prose-store">
            {!! $blog->content !!}
        </div>
    @endif

    @if(is_array($blog->tags) && count($blog->tags))
        <ul class="store-blog-post__tags">
            @foreach($blog->tags as $tag)
                <li>{{ $tag }}</li>
            @endforeach
        </ul>
    @endif
</article>

@if($related->count())
    <section class="store-section store-blog-related" aria-labelledby="related-title">
        <h2 id="related-title" class="store-section__title">مقالات ذات صلة</h2>
        <div class="store-blog__grid">
            @foreach($related as $post)
                @include('store.blog._card', ['post' => $post])
            @endforeach
        </div>
    </section>
@endif
@endsection
