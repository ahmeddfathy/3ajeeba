<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('store.name') . ' | أناقة فاخرة بتفاصيل استثنائية')</title>
    <meta name="description" content="@yield('meta_description', 'عجيبة علامة فاخرة للأزياء المحتشمة والعبايات بتصاميم تجمع بين الرقي والراحة.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@500;600;700&family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                'name' => config('store.name'),
                'url' => url('/'),
                'logo' => asset('assets/brand/logo.jpeg'),
            ],
            [
                '@type' => 'WebSite',
                'name' => config('store.name'),
                'url' => url('/'),
                'inLanguage' => 'ar',
            ],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    @stack('head')
</head>
@php
    $checkoutMode = \App\Models\Setting::checkoutMode();
    $storeWhatsapp = \App\Models\Setting::whatsappNumber();
@endphp
<body
    class="store-body"
    data-checkout-mode="{{ $checkoutMode }}"
    data-whatsapp="{{ $storeWhatsapp }}"
    data-whatsapp-intro="{{ e(config('store.whatsapp_order_intro')) }}"
    data-currency="{{ config('store.currency', 'ر.س') }}"
    data-orders-url="{{ route('orders.store') }}"
    data-active-nav="@yield('active_nav', 'home')"
>
    <div class="store-shell">
        <div class="store-frame">
            @include('partials.store.header')

            <main id="main-content">
                @yield('content')
            </main>

            @include('partials.store.footer')
        </div>
    </div>

    @include('partials.store.bottom-dock')
    @include('partials.store.overlays')

    @stack('scripts')
</body>
</html>
