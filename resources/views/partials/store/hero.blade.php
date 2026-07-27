@php
    $hero = config('store.hero');
    $returnDays = config('store.return_days', 14);
    $shippingLabel = config('store.free_shipping_label');
    $heroImage = !empty($hero['image']) ? asset($hero['image']) : asset('assets/brand/hero-banner.jpg');
@endphp

<section class="store-page-hero store-page-hero--home" aria-labelledby="hero-title">
    @if ($heroImage)
        <img
            class="store-page-hero__media"
            src="{{ $heroImage }}"
            alt="{{ $hero['image_alt'] ?? 'أناقة فاخرة بتفاصيل استثنائية' }}"
            width="1600"
            height="750"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        >
    @endif

    <div class="store-page-hero__shade" aria-hidden="true"></div>

    <div class="store-page-hero__content">
        <p class="store-page-hero__eyebrow">تشكيلات حصرية — عجيبة</p>
        <h1 id="hero-title" class="store-page-hero__title">{!! nl2br(e($hero['title'])) !!}</h1>
        <p class="store-page-hero__text">{!! nl2br(e($hero['text'])) !!}</p>

        <div class="store-page-hero__actions">
            <a href="{{ url($hero['cta_url']) }}" class="store-btn store-page-hero__cta">
                <span>{{ $hero['cta'] }}</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

        <ul class="store-page-hero__perks">
            <li>
                <span class="store-page-hero__perk-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M4 12h12l4-4v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span>
                    <strong>إرجاع سهل</strong>
                    خلال {{ $returnDays }} يوم
                </span>
            </li>
            <li>
                <span class="store-page-hero__perk-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M3 7h13v10H3zM16 10h3l2 3v4h-5V10z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <circle cx="7.5" cy="18.5" r="1.5" fill="currentColor"/>
                        <circle cx="17.5" cy="18.5" r="1.5" fill="currentColor"/>
                    </svg>
                </span>
                <span>
                    <strong>شحن مجاني</strong>
                    {{ $shippingLabel }}
                </span>
            </li>
        </ul>
    </div>
</section>
