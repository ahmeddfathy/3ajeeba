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
    $discountPct = $hasDiscount ? round((1 - $price / $original) * 100) : 0;
    $hasVariants = $product->has_variants;
    $activeVariants = $hasVariants
        ? $product->activeVariants->map(fn($v) => [
            'id' => $v->id,
            'size' => $v->size,
            'color' => $v->color,
            'price' => (int) $v->price,
            'original_price' => $v->original_price ? (int) $v->original_price : null,
            'label' => $v->label,
        ])->values()->all()
        : [];
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
    data-variants="{{ json_encode($activeVariants) }}"
>
    <div class="store-product-card__media">
        <a href="{{ $url }}" class="store-product-card__image" aria-label="عرض {{ $product->name }}">
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
        </a>

        @if ($ribbon)
            <span class="store-product-card__ribbon">{{ $ribbon }}</span>
        @elseif ($hasDiscount)
            <span class="store-product-card__ribbon store-product-card__ribbon--discount">خصم {{ $discountPct }}%</span>
        @endif

        <a href="{{ $url }}" class="store-product-card__view" aria-label="عرض تفاصيل {{ $product->name }}" title="عرض المنتج السريع">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </a>
    </div>

    <div class="store-product-card__body">
        <h3 class="store-product-card__name">
            <a href="{{ $url }}" title="{{ $product->name }}">{{ $product->name }}</a>
        </h3>

        <div class="store-product-card__price">
            <div class="store-product-card__price-wrap">
                @if ($hasVariants)
                    <span class="store-product-card__from">من</span>
                @endif
                <span class="store-product-card__amount">{{ number_format($price) }}</span>
                <span class="store-product-card__currency">{{ $currency }}</span>
            </div>

            @if ($hasDiscount)
                <s class="store-product-card__original">{{ number_format($original) }} {{ $currency }}</s>
            @endif
        </div>

        <div class="store-product-card__actions">
            <button type="button" class="store-product-card__add" data-add-to-cart>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span>أضيفي للسلة</span>
            </button>
            <button type="button" class="store-wa-btn store-product-card__wa" data-whatsapp-buy aria-label="اطلبي {{ $product->name }} عبر واتساب" title="طلب سريع عبر واتساب">
                @include('partials.store.icon-whatsapp', ['size' => 18])
            </button>
        </div>
    </div>
</article>
