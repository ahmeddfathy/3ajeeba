@extends('layouts.store')

@section('title', config('store.name') . ' | أناقة فاخرة بتفاصيل استثنائية')
@section('meta_description', 'تسوقي من عجيبة تشكيلات العبايات والحجابات والخمر والإكسسوارات بتصاميم فاخرة ومحتشمة.')
@section('active_nav', 'home')

@section('content')
    @include('partials.store.hero')
    @include('partials.store.categories')
    @include('partials.store.collections')
    @include('partials.store.best-sellers')
    @include('partials.store.benefits')
    @include('partials.store.seasonal-banner')
@endsection
