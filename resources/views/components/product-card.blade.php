@props([
    'product',
    'currency' => null,
])

@php
    $currency = $currency ?? config('store.currency', 'ر.س');
    $imageUrl = $product->image_url;
    $price = $product->display_price;
    $original = $product->display_original_price;
    $hasDiscount = $original && $original > $price;
    $hasVariants = $product->has_variants;
    $ribbon = $product->ribbon_label;
    $url = route('products.show', $product);
@endphp

<article
    class="store-product-card"
    data-product-card
    data-product-id="{{ $product->id }}"
    data-product-name="{{ $product->name }}"
    data-product-price="{{ $price }}"
    data-product-image="{{ $imageUrl ?? '' }}"
    data-has-variants="{{ $hasVariants ? '1' : '0' }}"
>
    <a href="{{ $url }}" class="store-product-card__media" aria-label="عرض {{ $product->name }}">
        @if ($imageUrl)
            <img
                src="{{ $imageUrl }}"
                alt="{{ $product->name }}"
                width="400"
                height="300"
                loading="lazy"
                decoding="async"
            >
        @else
            <div class="store-placeholder store-placeholder--product" aria-hidden="true">
                <span>{{ $product->name }}</span>
            </div>
        @endif

        @if ($ribbon)
            <span class="store-product-card__ribbon">{{ $ribbon }}</span>
        @endif
    </a>

    <button
        type="button"
        class="store-product-card__wish"
        data-wishlist-toggle
        data-product-id="{{ $product->id }}"
        aria-label="إضافة {{ $product->name }} إلى المفضلة"
        aria-pressed="false"
    >
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.5A4 4 0 0 1 19 10c0 5.6-7 10-7 10z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        </svg>
    </button>

    <div class="store-product-card__body">
        <h3 class="store-product-card__name">
            <a href="{{ $url }}">{{ $product->name }}</a>
        </h3>
        <div class="store-product-card__price">
            <span>
                @if ($hasVariants)<small>من</small> @endif
                {{ number_format($price) }} {{ $currency }}
            </span>
            @if ($hasDiscount)
                <s>{{ number_format($original) }} {{ $currency }}</s>
            @endif
        </div>
        @if ($hasVariants)
            <a href="{{ $url }}" class="store-btn store-btn--ghost store-product-card__add">اختاري المقاس واللون</a>
        @else
            <button type="button" class="store-btn store-btn--ghost store-product-card__add" data-add-to-cart>
                أضيفي للسلة
            </button>
        @endif
    </div>
</article>
