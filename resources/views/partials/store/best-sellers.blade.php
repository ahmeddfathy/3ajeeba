@php
    $currency = config('store.currency', 'ر.س');
@endphp

<section class="store-section" id="best-sellers" aria-labelledby="best-sellers-title">
    <div class="store-section__head">
        <div class="store-section__title-wrap">
            <h2 id="best-sellers-title" class="store-section__title">الأكثر مبيعًا</h2>
            <span class="store-section__rule" aria-hidden="true"></span>
        </div>
        <a href="{{ route('products.index') }}" class="store-section__link">
            عرض جميع المنتجات
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>

    @if ($bestSellers->isEmpty())
        <div class="store-empty">
            <p>لا توجد منتجات معروضة حاليًا. أضيفي منتجات نشطة من لوحة الإدارة.</p>
        </div>
    @else
        <div class="store-products" role="list">
            @foreach ($bestSellers->take(10) as $product)
                <div role="listitem">
                    <x-product-card :product="$product" :currency="$currency" />
                </div>
            @endforeach
        </div>
    @endif
</section>
