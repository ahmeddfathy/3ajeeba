@php
    $hero = config('store.hero');
    $returnDays = config('store.return_days', 14);
    $shippingLabel = config('store.free_shipping_label');
@endphp

<section class="store-hero" aria-labelledby="hero-title">
    <div class="store-hero__card">
        <div class="store-hero__content">
            <h1 id="hero-title" class="store-hero__title">{!! nl2br(e($hero['title'])) !!}</h1>
            <p class="store-hero__text">{!! nl2br(e($hero['text'])) !!}</p>

            <a href="{{ url($hero['cta_url']) }}" class="store-btn store-btn--primary">
                <span>{{ $hero['cta'] }}</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <ul class="store-hero__perks">
                <li>
                    <span class="store-hero__perk-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M4 12h12l4-4v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>
                        <strong>إرجاع سهل</strong>
                        خلال {{ $returnDays }} يوم
                    </span>
                </li>
                <li>
                    <span class="store-hero__perk-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M3 7h13v10H3zM16 10h3l2 3v4h-5V10z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
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

        <div class="store-hero__media">
            @if (!empty($hero['image']))
                <img
                    src="{{ asset($hero['image']) }}"
                    alt="{{ $hero['image_alt'] }}"
                    width="960"
                    height="720"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                >
            @else
                <div class="store-placeholder store-placeholder--hero" role="img" aria-label="{{ $hero['image_alt'] }}">
                    <span>صورة التشكيلة قريبًا</span>
                </div>
            @endif
        </div>
    </div>
</section>
