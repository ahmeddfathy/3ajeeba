<article class="store-blog-card">
    <a href="{{ route('blog.show', $post) }}" class="store-blog-card__media">
        @if($post->featured_image_url)
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?: $post->title }}" loading="lazy">
        @else
            <div class="store-blog-card__placeholder" aria-hidden="true"></div>
        @endif
        @if($post->category)
            <span class="store-blog-card__badge">{{ $post->category->name }}</span>
        @endif
    </a>
    <div class="store-blog-card__body">
        <time datetime="{{ optional($post->published_at ?? $post->created_at)->toDateString() }}">
            {{ optional($post->published_at ?? $post->created_at)->translatedFormat('d M Y') }}
        </time>
        <h2>
            <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
        </h2>
        <p>{{ $post->excerpt_text }}</p>
        <a href="{{ route('blog.show', $post) }}" class="store-blog-card__more">اقرأ المقال</a>
    </div>
</article>
