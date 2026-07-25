@php
    $isFeatured = ! empty($featured);
@endphp

<article class="store-blog-card {{ $isFeatured ? 'store-blog-card--featured' : '' }}">
    <a href="{{ route('blog.show', $post) }}" class="store-blog-card__media">
        @if($post->featured_image_url)
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?: $post->title }}" loading="lazy" decoding="async">
        @else
            <div class="store-blog-card__placeholder" aria-hidden="true">
                <span>{{ config('store.name') }}</span>
            </div>
        @endif
        <span class="store-blog-card__overlay" aria-hidden="true"></span>
        @if($post->category)
            <span class="store-blog-card__badge">{{ $post->category->name }}</span>
        @endif
    </a>

    <div class="store-blog-card__body">
        <div class="store-blog-card__meta">
            <time datetime="{{ optional($post->published_at ?? $post->created_at)->toDateString() }}">
                {{ optional($post->published_at ?? $post->created_at)->translatedFormat('d M Y') }}
            </time>
            @if($isFeatured)
                <span class="store-blog-card__label">مقال مميز</span>
            @endif
        </div>

        <h2 class="store-blog-card__title">
            <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
        </h2>

        <p class="store-blog-card__excerpt">{{ $post->excerpt_text }}</p>

        <a href="{{ route('blog.show', $post) }}" class="store-blog-card__more">
            <span>اقرأي المقال</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
</article>
