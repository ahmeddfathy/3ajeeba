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
        @endif

        <a href="{{ $url }}" class="store-product-card__view" aria-label="عرض تفاصيل {{ $product->name }}" title="عرض المنتج">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </a>
    </div>

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
        <div class="store-product-card__actions">
            <button type="button" class="store-product-card__add" data-add-to-cart>
                أضيفي للسلة
            </button>
            <button type="button" class="store-wa-btn store-product-card__wa" data-whatsapp-buy aria-label="اطلبي {{ $product->name }} عبر واتساب">
                @include('partials.store.icon-whatsapp', ['size' => 18])
                <span>واتساب</span>
            </button>
        </div>
    </div>
</article>
