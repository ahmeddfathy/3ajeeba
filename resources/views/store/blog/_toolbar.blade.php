@php
    $activeCategory = $activeCategory ?? null;
@endphp

<div class="store-blog__toolbar">
    <form method="GET" action="{{ route('blog.index') }}" class="store-blog__search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
            <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        <input type="search" name="search" value="{{ request('search') }}" placeholder="ابحثي في المقالات...">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
    </form>

    <div class="store-blog__filters" role="navigation" aria-label="تصنيفات المدونة">
        <a href="{{ route('blog.index') }}" class="store-blog__filter {{ ! $activeCategory && ! request('category') && ! request()->routeIs('blog.category') ? 'is-active' : '' }}">الكل</a>
        @foreach($categories as $cat)
            <a href="{{ route('blog.category', $cat) }}"
               class="store-blog__filter {{ ($activeCategory && $activeCategory->id === $cat->id) || request('category') === $cat->slug || (request()->routeIs('blog.category') && optional(request()->route('category'))->slug === $cat->slug) ? 'is-active' : '' }}">
                {{ $cat->name }}
                @if($cat->blogs_count)
                    <span>{{ $cat->blogs_count }}</span>
                @endif
            </a>
        @endforeach
    </div>
</div>
