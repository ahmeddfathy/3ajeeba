@extends('layouts.store')

@section('title', $title . ' | ' . config('store.name'))
@section('meta_description', 'تسوقي من تشكيلة عجيبة: عبايات وحجابات وخمر وإكسسوارات بأناقة فاخرة.')
@section('active_nav', 'products')

@section('content')
<section class="store-section store-catalog" aria-labelledby="catalog-title">
    <div class="store-catalog__hero">
        <div>
            <p class="store-catalog__eyebrow">متجر عجيبة</p>
            <h1 id="catalog-title" class="store-section__title">{{ $title }}</h1>
            <p class="store-catalog__lead">اكتشفي تشكيلاتنا المختارة وتصاميم تجمع بين الرقي والراحة.</p>
        </div>

        <form class="store-catalog__search" action="{{ route('products.index') }}" method="get" role="search">
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
            <input id="catalog-q" type="search" name="q" value="{{ $search }}" placeholder="ابحثي عن منتج...">
            <button type="submit" class="store-btn store-btn--primary">بحث</button>
        </form>
    </div>

    <div class="store-catalog__filters" role="navigation" aria-label="تصفية الأقسام">
        <a href="{{ route('products.index') }}" class="store-chip {{ ! $category && ! $filter && ! $collection ? 'is-active' : '' }}">الكل</a>
        <a href="{{ route('products.index', ['filter' => 'featured']) }}" class="store-chip {{ $filter === 'featured' ? 'is-active' : '' }}">الأكثر مبيعًا</a>
        <a href="{{ route('products.index', ['filter' => 'new']) }}" class="store-chip {{ $filter === 'new' ? 'is-active' : '' }}">جديدنا</a>
        @foreach ($categories as $item)
            <a
                href="{{ route('products.index', ['category' => $item->slug]) }}"
                class="store-chip {{ $category === $item->slug ? 'is-active' : '' }}"
            >
                {{ $item->name }}
            </a>
        @endforeach
    </div>

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
</section>
@endsection
