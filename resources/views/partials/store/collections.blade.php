@php
    $collections = $collections ?? collect();
@endphp

<section class="store-section" id="collections" aria-labelledby="collections-title">
    <div class="store-section__head">
        <div class="store-section__title-wrap">
            <h2 id="collections-title" class="store-section__title">مجموعات مختارة</h2>
            <span class="store-section__rule" aria-hidden="true"></span>
        </div>
        <a href="{{ route('products.index') }}" class="store-section__link">
            عرض جميع المجموعات
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>

    <div class="store-collections" role="list">
        @forelse ($collections as $collection)
            <article class="store-collection-card" role="listitem">
                <div class="store-collection-card__media">
                    @if ($collection->image_url)
                        <img
                            src="{{ $collection->image_url }}"
                            alt="{{ $collection->name }}"
                            width="720"
                            height="360"
                            loading="lazy"
                            decoding="async"
                        >
                    @else
                        <div class="store-placeholder store-placeholder--collection store-placeholder--{{ $collection->slug }}" aria-hidden="true"></div>
                    @endif
                </div>
                <div class="store-collection-card__body">
                    <span class="store-collection-card__label">{{ $collection->label ?: 'مجموعة' }}</span>
                    <h3>{{ $collection->name }}</h3>
                    <a href="{{ route('products.index', ['collection' => $collection->slug]) }}" class="store-collection-card__cta">
                        تسوقي الآن
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </article>
        @empty
            <div class="store-empty" style="grid-column:1/-1;">
                <p>أضيفي مجموعات من لوحة الإدارة لتظهر هنا.</p>
            </div>
        @endforelse
    </div>
</section>
