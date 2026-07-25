@extends('layouts.store')

@php
    $page = config('store.contact_page', []);
    $whatsappUrl = $whatsapp ? 'https://wa.me/' . $whatsapp : null;
    $whatsappInquiryUrl = $whatsappUrl
        ? $whatsappUrl . '?text=' . rawurlencode('السلام عليكم، لدي استفسار بخصوص منتجات عجيبة.')
        : null;
    $phoneDigits = preg_replace('/\D+/', '', (string) $phone);
    $phoneDisplay = $phoneDigits
        ? preg_replace('/(\d{2})(\d{2})(\d{4})(\d{4})/', '+$1 $2 $3 $4', $phoneDigits)
        : null;
    $contactImage = $page['image'] ?? config('store.hero.image');
    $contactImageAlt = $page['image_alt'] ?? 'تواصل مع عجيبة';
@endphp

@section('title', 'تواصل معنا | ' . config('store.name'))
@section('meta_description', 'تواصلي مع عجيبة عبر واتساب أو الهاتف أو فيسبوك — خدمة عملاء سريعة وودودة.')
@section('active_nav', 'contact')

@section('content')
<section class="store-contact" aria-labelledby="contact-title">
    <header class="store-page-hero store-page-hero--contact">
        @if ($contactImage)
            <img
                class="store-page-hero__media"
                src="{{ asset($contactImage) }}"
                alt="{{ $contactImageAlt }}"
                width="1600"
                height="700"
                loading="eager"
                decoding="async"
            >
        @endif
        <div class="store-page-hero__shade" aria-hidden="true"></div>
        <div class="store-page-hero__content">
            <p class="store-page-hero__eyebrow">نحن هنا لمساعدتكِ</p>
            <h1 id="contact-title" class="store-page-hero__title">تواصل معنا</h1>
            <p class="store-page-hero__text">
                لأي استفسار عن المنتجات، المقاسات، أو الطلبات — تواصلي معنا مباشرة وسنرد عليكِ في أسرع وقت.
            </p>
            @if ($whatsappInquiryUrl)
                <a href="{{ $whatsappInquiryUrl }}" class="store-wa-btn store-page-hero__cta" target="_blank" rel="noopener noreferrer">
                    @include('partials.store.icon-whatsapp', ['size' => 20])
                    <span>ابدئي محادثة واتساب</span>
                </a>
            @endif
        </div>
    </header>

    <div class="store-contact__wrap">
        <div class="store-contact__meta">
            <div>
                <strong>{{ $page['response_note'] ?? 'نرد على واتساب خلال دقائق' }}</strong>
                <span>{{ $page['hours'] ?? 'يوميًا من 10 صباحًا حتى 10 مساءً' }}</span>
            </div>
            <div>
                <strong>{{ $page['location'] ?? 'بني سويف، مصر' }}</strong>
                <span>{{ $page['location_note'] ?? 'فرع رئيسي معتمد من مصنع عجيبة' }}</span>
            </div>
        </div>

        <div class="store-contact__channels">
            @if ($whatsappUrl)
                <a href="{{ $whatsappUrl }}" class="store-contact__card store-contact__card--whatsapp" target="_blank" rel="noopener noreferrer">
                    <span class="store-contact__icon store-contact__icon--wa" aria-hidden="true">
                        @include('partials.store.icon-whatsapp', ['size' => 26])
                    </span>
                    <strong>واتساب</strong>
                    <p>الطريقة الأسرع للطلب والاستفسار</p>
                    <span class="store-contact__link">ابدئي المحادثة</span>
                </a>
            @endif

            @if ($phoneDisplay)
                <a href="tel:+{{ $phoneDigits }}" class="store-contact__card">
                    <span class="store-contact__icon" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M6.5 4.5h3l1.5 4-2 1.5a11 11 0 0 0 5.5 5.5l1.5-2 4 1.5v3A2 2 0 0 1 18 20 14 14 0 0 1 4 6a2 2 0 0 1 2.5-1.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <strong>اتصال هاتفي</strong>
                    <p>خلال مواعيد العمل</p>
                    <span class="store-contact__link" dir="ltr">{{ $phoneDisplay }}</span>
                </a>
            @endif

            @if ($facebook)
                <a href="{{ $facebook }}" class="store-contact__card store-contact__card--fb" target="_blank" rel="noopener noreferrer">
                    <span class="store-contact__icon store-contact__icon--fb" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M14 8h2.5V5.5H14c-1.9 0-3.5 1.6-3.5 3.5v2H8.5V14H10.5v6h3v-6H16l.5-3h-3V9c0-.6.4-1 1-1z" stroke="currentColor" stroke-width="1.4"/>
                            <rect x="3.5" y="3.5" width="17" height="17" rx="4" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </span>
                    <strong>فيسبوك</strong>
                    <p>تابعي جديدنا وعروضنا</p>
                    <span class="store-contact__link">عجيبة بني سويف</span>
                </a>
            @endif

            <div class="store-contact__card store-contact__card--static">
                <span class="store-contact__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <strong>الموقع</strong>
                <p>{{ $page['location_note'] ?? 'فرع رئيسي معتمد' }}</p>
                <span class="store-contact__link">{{ $page['location'] ?? 'بني سويف، مصر' }}</span>
            </div>
        </div>

        <div class="store-contact__help">
            <h2>كيف نقدر نساعدكِ؟</h2>
            <ul>
                <li>مساعدة في اختيار المقاس واللون</li>
                <li>تأكيد الطلب عبر واتساب بسهولة</li>
                <li>متابعة الشحن والتوصيل</li>
                <li>استبدال وإرجاع خلال {{ config('store.return_days', 14) }} يوم</li>
            </ul>
        </div>
    </div>
</section>
@endsection
