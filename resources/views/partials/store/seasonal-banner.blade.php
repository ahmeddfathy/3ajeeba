@php
    $banner = config('store.seasonal_banner');
@endphp

<section class="store-section store-section--tight" aria-labelledby="seasonal-title">
    <div class="store-seasonal">
        <div class="store-seasonal__media">
            @if (!empty($banner['image']))
                <img
                    src="{{ asset($banner['image']) }}"
                    alt="{{ $banner['image_alt'] }}"
                    width="1400"
                    height="420"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div class="store-placeholder store-placeholder--seasonal" aria-hidden="true"></div>
            @endif
        </div>

        <div class="store-seasonal__content">
            <div class="store-seasonal__copy">
                <h2 id="seasonal-title">{{ $banner['title'] }}</h2>
                <p>{{ $banner['text'] }}</p>
                <a href="{{ url($banner['cta_url']) }}" class="store-btn store-btn--light">
                    {{ $banner['cta'] }}
                </a>
            </div>

            @if (!empty($banner['cta_secondary']))
                <a href="{{ url($banner['cta_url']) }}" class="store-seasonal__cta-circle">
                    <span>{{ $banner['cta_secondary'] }}</span>
                </a>
            @endif
        </div>
    </div>
</section>
