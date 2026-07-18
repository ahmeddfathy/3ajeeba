@php
    $categories = $categories ?? collect();
@endphp

<section class="store-section" id="categories" aria-labelledby="categories-title">
    <div class="store-section__head">
        <div class="store-section__title-wrap">
            <h2 id="categories-title" class="store-section__title">تسوقي حسب الفئة</h2>
            <span class="store-section__rule" aria-hidden="true"></span>
        </div>
        <a href="{{ route('products.index') }}" class="store-section__link">
            عرض جميع الفئات
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>

    <div class="store-categories" role="list">
        @forelse ($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="store-category-card" role="listitem">
                <div class="store-category-card__media">
                    @if ($category->image_url)
                        <img
                            src="{{ $category->image_url }}"
                            alt="{{ $category->name }}"
                            width="480"
                            height="360"
                            loading="lazy"
                            decoding="async"
                        >
                    @else
                        <div class="store-placeholder store-placeholder--category store-placeholder--{{ $category->slug }}" aria-hidden="true"></div>
                    @endif
                </div>
                <div class="store-category-card__body">
                    <h3>{{ $category->name }}</h3>
                    <span>
                        اكتشفي المزيد
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </a>
        @empty
            <div class="store-empty" style="grid-column:1/-1;">
                <p>أضيفي فئات من لوحة الإدارة لتظهر هنا.</p>
            </div>
        @endforelse
    </div>
</section>
