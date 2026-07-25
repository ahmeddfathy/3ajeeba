@extends('layouts.store')

@section('title', $title . ' | ' . config('store.name'))
@section('meta_description', 'تسوقي من تشكيلة عجيبة: عبايات وحجابات وخمر وإكسسوارات بأناقة فاخرة.')
@section('active_nav', 'products')

@section('content')
<section class="store-catalog" aria-labelledby="catalog-title">
    @if (!empty($bannerImage))
        <header class="store-catalog__banner">
            <div class="store-catalog__banner-inner">
                <div class="store-catalog__banner-copy">
                    <p class="store-catalog__banner-eyebrow">{{ $eyebrow }}</p>
                    <h1 id="catalog-title" class="store-catalog__banner-title">{{ $title }}</h1>
                    @if (!empty($lead))
                        <p class="store-catalog__banner-lead">{{ $lead }}</p>
                    @endif
                </div>

                <figure class="store-catalog__banner-visual">
                    <img
                        src="{{ $bannerImage }}"
                        alt="{{ $bannerAlt }}"
                        width="960"
                        height="720"
                        loading="eager"
                        decoding="async"
                    >
                </figure>
            </div>
        </header>
    @endif

    <div class="store-catalog__wrap">
        @if (empty($bannerImage))
            <div class="store-catalog__hero">
                <div>
                    <p class="store-catalog__eyebrow">{{ $eyebrow ?? 'متجر عجيبة' }}</p>
                    <h1 id="catalog-title" class="store-section__title">{{ $title }}</h1>
                    <p class="store-catalog__lead">{{ $lead ?? 'اكتشفي تشكيلاتنا المختارة وتصاميم تجمع بين الرقي والراحة.' }}</p>
                </div>

                <form
                    class="store-catalog__search"
                    action="{{ route('products.index') }}"
                    method="get"
                    role="search"
                    data-suggest-url="{{ route('products.suggest') }}"
                    data-search-form
                >
                    @if ($filter)
                        <input type="hidden" name="filter" value="{{ $filter }}">
                    @endif
                    @if ($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    @if ($collection)
                        <input type="hidden" name="collection" value="{{ $collection }}">
                    @endif
                    <label class="sr-only" for="catalog-q">بحث المنتجات</label>
                    <input
                        id="catalog-q"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="عباية، قسم، مجموعة..."
                        data-search-input
                        autocomplete="off"
                        role="combobox"
                        aria-expanded="false"
                        aria-autocomplete="list"
                    >
                    <button type="submit" class="store-btn store-btn--primary">بحث</button>
                    <div class="store-search__suggest store-search__suggest--inline" data-search-suggest role="listbox" hidden></div>
                </form>
            </div>
        @else
            <form
                class="store-catalog__search store-catalog__search--bar"
                action="{{ route('products.index') }}"
                method="get"
                role="search"
                data-suggest-url="{{ route('products.suggest') }}"
                data-search-form
            >
                @if ($filter)
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                @if ($collection)
                    <input type="hidden" name="collection" value="{{ $collection }}">
                @endif
                <label class="sr-only" for="catalog-q">بحث المنتجات</label>
                <input
                    id="catalog-q"
                    type="search"
                    name="q"
                    value="{{ $search }}"
                    placeholder="عباية، قسم، مجموعة..."
                    data-search-input
                    autocomplete="off"
                    role="combobox"
                    aria-expanded="false"
                    aria-autocomplete="list"
                >
                <button type="submit" class="store-btn store-btn--primary">بحث</button>
                <div class="store-search__suggest store-search__suggest--inline" data-search-suggest role="listbox" hidden></div>
            </form>
        @endif

        @unless ($collection)
            @php
                $chipBase = array_filter([
                    'filter' => $filter ?: null,
                    'q' => $search ?: null,
                ], fn ($value) => filled($value));
            @endphp
            <div class="store-catalog__filters" role="navigation" aria-label="تصفية الأقسام">
                <a
                    href="{{ route('products.index', $chipBase) }}"
                    class="store-chip {{ ! $category ? 'is-active' : '' }}"
                >الكل</a>
                @foreach ($categories as $item)
                    <a
                        href="{{ route('products.index', array_merge($chipBase, ['category' => $item->slug])) }}"
                        class="store-chip {{ $category === $item->slug ? 'is-active' : '' }}"
                    >
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
        @endunless

        @if ($products->isEmpty())
            <div class="store-empty">
                <p>لا توجد منتجات مطابقة حاليًا.</p>
                <a href="{{ route('products.index') }}" class="store-btn store-btn--primary">عرض كل المنتجات</a>
            </div>
        @else
            <div class="store-products store-products--catalog" role="list">
                @foreach ($products as $product)
                    <div role="listitem">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <div class="store-catalog__pagination">
                {{ $products->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</section>
@endsection
