@extends('layouts.store')

@php
    $currency = config('store.currency', 'ر.س');
    $variants = $product->activeVariants;
    $hasVariants = $variants->isNotEmpty();
    $colors = $variants->pluck('color')->filter()->unique()->values();
    $sizes = $variants->pluck('size')->filter()->unique()->values();
    $default = $variants->first();
    $displayPrice = $hasVariants ? $default->price : $product->price;
    $displayOriginal = $hasVariants
        ? ($default->original_price && $default->original_price > $default->price ? $default->original_price : null)
        : ($product->original_price && $product->original_price > $product->price ? $product->original_price : null);
    $imageUrl = $product->image_url;
@endphp

@section('title', $product->name . ' | ' . config('store.name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?: $product->name), 150))
@section('active_nav', 'products')

@section('content')
<article
    class="store-section store-pdp"
    data-product-card
    data-product-id="{{ $product->id }}"
    data-product-name="{{ $product->name }}"
    data-product-price="{{ $displayPrice }}"
    data-product-image="{{ $imageUrl ?? '' }}"
    data-has-variants="{{ $hasVariants ? '1' : '0' }}"
    @if ($default)
        data-variant-id="{{ $default->id }}"
        data-variant-label="{{ $default->label }}"
        data-variant-size="{{ $default->size }}"
        data-variant-color="{{ $default->color }}"
    @endif
>
    <nav class="store-breadcrumb" aria-label="مسار التنقل">
        <a href="{{ route('home') }}">الرئيسية</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('products.index') }}">المنتجات</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">{{ $product->name }}</span>
    </nav>

    <div class="store-pdp__grid">
        <div class="store-pdp__media">
            @if ($imageUrl)
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $product->name }}"
                    width="900"
                    height="900"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                >
            @else
                <div class="store-placeholder store-placeholder--product" role="img" aria-label="{{ $product->name }}">
                    <span>{{ $product->name }}</span>
                </div>
            @endif

            @if ($product->ribbon_label)
                <span class="store-product-card__ribbon">{{ $product->ribbon_label }}</span>
            @endif
        </div>

        <div class="store-pdp__info">
            <h1 class="store-pdp__title">{{ $product->name }}</h1>

            <div class="store-pdp__price" data-pdp-price-wrap>
                <strong data-pdp-price>{{ number_format($displayPrice) }} {{ $currency }}</strong>
                <s data-pdp-original @if (! $displayOriginal) hidden @endif>
                    {{ $displayOriginal ? number_format($displayOriginal) . ' ' . $currency : '' }}
                </s>
                <span class="store-pdp__discount" data-pdp-discount hidden></span>
            </div>

            @if ($product->description)
                <p class="store-pdp__desc">{{ $product->description }}</p>
            @endif

            @if ($product->categories->isNotEmpty() || $product->collections->isNotEmpty())
                <div class="store-pdp__taxonomies">
                    @foreach ($product->categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="store-chip">{{ $category->name }}</a>
                    @endforeach
                    @foreach ($product->collections as $collection)
                        <a href="{{ route('products.index', ['collection' => $collection->slug]) }}" class="store-chip">{{ $collection->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($hasVariants)
                <script type="application/json" data-variants-json>
                    {!! $variants->map(fn ($v) => [
                        'id' => $v->id,
                        'size' => $v->size,
                        'color' => $v->color,
                        'color_hex' => $v->color_hex,
                        'price' => $v->price,
                        'original_price' => $v->original_price,
                        'label' => $v->label,
                        'stock' => $v->stock,
                    ])->values()->toJson(JSON_UNESCAPED_UNICODE) !!}
                </script>

                @if ($colors->isNotEmpty())
                    <div class="store-pdp__option" data-option-color>
                        <div class="store-pdp__option-label">
                            <span>اللون:</span>
                            <strong data-selected-color>{{ $default?->color }}</strong>
                        </div>
                        <div class="store-pdp__swatches" role="listbox" aria-label="اختيار اللون">
                            @foreach ($colors as $color)
                                @php
                                    $hex = optional($variants->firstWhere('color', $color))->color_hex ?: '#A36F50';
                                    $isActive = $default?->color === $color;
                                @endphp
                                <button
                                    type="button"
                                    class="store-swatch {{ $isActive ? 'is-active' : '' }}"
                                    data-select-color="{{ $color }}"
                                    style="--swatch: {{ $hex }}"
                                    aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                                    title="{{ $color }}"
                                >
                                    <span class="sr-only">{{ $color }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($sizes->isNotEmpty())
                    <div class="store-pdp__option" data-option-size>
                        <div class="store-pdp__option-label">
                            <span>المقاس:</span>
                            <strong data-selected-size>{{ $default?->size }}</strong>
                        </div>
                        <div class="store-pdp__sizes" role="listbox" aria-label="اختيار المقاس">
                            @foreach ($sizes as $size)
                                @php $isActive = $default?->size === $size; @endphp
                                <button
                                    type="button"
                                    class="store-size {{ $isActive ? 'is-active' : '' }}"
                                    data-select-size="{{ $size }}"
                                    aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                                >
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="store-pdp__variant-badge" data-variant-note>
                    <span>الخيار المحدد:</span>
                    <strong data-variant-label-text>{{ $default?->label }}</strong>
                </div>
            @endif

            <div class="store-pdp__qty">
                <span>الكمية المطلوبة</span>
                <div class="store-qty">
                    <button type="button" data-pdp-decrease aria-label="تقليل الكمية">−</button>
                    <input type="number" min="1" value="1" data-pdp-qty aria-label="كمية المنتج">
                    <button type="button" data-pdp-increase aria-label="زيادة الكمية">+</button>
                </div>
            </div>

            <div class="store-pdp__actions">
                <button type="button" class="store-btn store-btn--primary store-pdp__cart-btn" data-add-to-cart>
                    إضافة للسلة
                </button>
                <button type="button" class="store-wa-btn store-pdp__wa-btn" data-whatsapp-buy>
                    @include('partials.store.icon-whatsapp', ['size' => 20])
                    <span>اطلبي عبر واتساب</span>
                </button>
            </div>

            <ul class="store-pdp__perks">
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    إرجاع واستبدال مرن خلال {{ config('store.return_days', 14) }} يوم
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    تأكيد ومتابعة فورية للطلب عبر واتساب
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    تغليف راقي ومناسب للهدايا والمناسبات
                </li>
            </ul>
        </div>
    </div>

    @if ($product->details)
        <section class="store-pdp__details" aria-labelledby="product-details-title">
            <h2 id="product-details-title">تفاصيل المنتج</h2>
            <div class="store-pdp__details-body prose-store">
                {!! $product->details !!}
            </div>
        </section>
    @endif
</article>

@if ($related->isNotEmpty())
<section class="store-section" aria-labelledby="related-title">
    <div class="store-section__head">
        <div class="store-section__title-wrap">
            <h2 id="related-title" class="store-section__title">قد يعجبكِ أيضًا</h2>
            <span class="store-section__rule" aria-hidden="true"></span>
        </div>
        <a href="{{ route('products.index') }}" class="store-section__link">عرض الكل</a>
    </div>
    <div class="store-products store-products--related" role="list">
        @foreach ($related as $item)
            <div role="listitem">
                <x-product-card :product="$item" />
            </div>
        @endforeach
    </div>
</section>
@endif
@endsection
