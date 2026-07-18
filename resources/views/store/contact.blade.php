@extends('layouts.store')

@php
    $whatsappUrl = $whatsapp ? 'https://wa.me/' . $whatsapp : null;
    $phoneDigits = preg_replace('/\D+/', '', (string) $phone);
    $phoneDisplay = $phoneDigits
        ? preg_replace('/(\d{2})(\d{2})(\d{4})(\d{4})/', '+$1 $2 $3 $4', $phoneDigits)
        : null;
@endphp

@section('title', 'تواصل معنا | ' . config('store.name'))
@section('meta_description', 'تواصلي مع عجيبة عبر واتساب أو الهاتف أو فيسبوك — خدمة عملاء سريعة وودودة.')
@section('active_nav', 'contact')

@section('content')
<section class="store-section store-contact" aria-labelledby="contact-title">
    <div class="store-contact__hero">
        <p class="store-catalog__eyebrow">نحن هنا لمساعدتكِ</p>
        <h1 id="contact-title" class="store-section__title">تواصل معنا</h1>
        <p class="store-catalog__lead">
            لأي استفسار عن المنتجات، المقاسات، أو الطلبات — تواصلي معنا مباشرة وسنرد عليكِ في أسرع وقت.
        </p>
    </div>

    <div class="store-contact__cards store-contact__cards--wide">
        @if ($whatsappUrl)
            <a href="{{ $whatsappUrl }}" class="store-contact__card" target="_blank" rel="noopener noreferrer">
                <span class="store-contact__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M5 20c4-5 6-8 6-11a4 4 0 1 1 8 0c0 5-4 8-8 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M9.5 13.5c1.5.8 3.2 1.2 5 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </span>
                <div>
                    <h2>واتساب</h2>
                    <p>الطريقة الأسرع للطلب والاستفسار</p>
                    <strong>ابدئي المحادثة</strong>
                </div>
            </a>
        @endif

        @if ($phoneDisplay)
            <a href="tel:+{{ $phoneDigits }}" class="store-contact__card">
                <span class="store-contact__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M6.5 4.5h3l1.5 4-2 1.5a11 11 0 0 0 5.5 5.5l1.5-2 4 1.5v3A2 2 0 0 1 18 20 14 14 0 0 1 4 6a2 2 0 0 1 2.5-1.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div>
                    <h2>اتصال هاتفي</h2>
                    <p>خلال مواعيد العمل</p>
                    <strong dir="ltr">{{ $phoneDisplay }}</strong>
                </div>
            </a>
        @endif

        @if ($facebook)
            <a href="{{ $facebook }}" class="store-contact__card" target="_blank" rel="noopener noreferrer">
                <span class="store-contact__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M14 8h2.5V5.5H14c-1.9 0-3.5 1.6-3.5 3.5v2H8.5V14H10.5v6h3v-6H16l.5-3h-3V9c0-.6.4-1 1-1z" stroke="currentColor" stroke-width="1.4"/>
                        <rect x="3.5" y="3.5" width="17" height="17" rx="4" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <div>
                    <h2>فيسبوك</h2>
                    <p>تابعي جديدنا وعروضنا</p>
                    <strong>عجيبة بني سويف</strong>
                </div>
            </a>
        @endif

        <div class="store-contact__card is-static">
            <span class="store-contact__icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </span>
            <div>
                <h2>الموقع</h2>
                <p>فرع رئيسي معتمد من مصنع عجيبة</p>
                <strong>بني سويف، مصر</strong>
            </div>
        </div>
    </div>
</section>
@endsection
