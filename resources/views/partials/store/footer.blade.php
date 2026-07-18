@php
    $footer = config('store.footer');
    $social = config('store.social');
    $payments = config('store.payments');
    $logo = asset('assets/brand/logo.jpeg');
    $whatsappUrl = ($social['whatsapp'] ?? null) ?: (
        config('store.contact.whatsapp')
            ? 'https://wa.me/' . preg_replace('/\D+/', '', (string) config('store.contact.whatsapp'))
            : null
    );
@endphp

<footer class="store-footer">
    <div class="store-footer__grid">
        <div class="store-footer__brand">
            <a href="{{ route('home') }}" class="store-footer__logo" aria-label="عجيبة">
                <img src="{{ $logo }}" alt="شعار عجيبة" width="732" height="732" loading="lazy" decoding="async">
            </a>
            <p>{!! nl2br(e($footer['about'])) !!}</p>
        </div>

        <div class="store-footer__col" data-footer-accordion>
            <button type="button" class="store-footer__toggle" aria-expanded="false">
                معلومات
                <svg width="14" height="14" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
            </button>
            <h3 class="store-footer__heading">معلومات</h3>
            <ul>
                @foreach ($footer['info'] as $link)
                    <li>
                        @if (!empty($link['whatsapp']) && $whatsappUrl)
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">{{ $link['label'] }}</a>
                        @elseif (!empty($link['url']))
                            <a href="{{ url($link['url']) }}">{{ $link['label'] }}</a>
                        @else
                            <span>{{ $link['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="store-footer__col" data-footer-accordion>
            <button type="button" class="store-footer__toggle" aria-expanded="false">
                خدمة العملاء
                <svg width="14" height="14" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
            </button>
            <h3 class="store-footer__heading">خدمة العملاء</h3>
            <ul>
                @foreach ($footer['support'] as $link)
                    <li>
                        @if (!empty($link['whatsapp']) && $whatsappUrl)
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">{{ $link['label'] }}</a>
                        @elseif (!empty($link['url']))
                            <a href="{{ url($link['url']) }}">{{ $link['label'] }}</a>
                        @else
                            <span>{{ $link['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="store-footer__col store-footer__social-col">
            <h3 class="store-footer__heading">تابعينا</h3>
            <ul class="store-footer__social">
                @foreach ([
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'snapchat' => 'Snapchat',
                    'tiktok' => 'TikTok',
                    'whatsapp' => 'WhatsApp',
                ] as $key => $label)
                    @php $href = $social[$key] ?? null; @endphp
                    <li>
                        @if ($href)
                            <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">
                                @include('partials.store.social-icon', ['name' => $key])
                            </a>
                        @else
                            {{-- TODO: أضف رابط {{ $label }} في config/store.php --}}
                            <span class="is-disabled" aria-label="{{ $label }} (قريبًا)" title="قريبًا">
                                @include('partials.store.social-icon', ['name' => $key])
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>

            <h3 class="store-footer__heading store-footer__heading--payments">طرق الدفع</h3>
            <ul class="store-footer__payments" aria-label="وسائل الدفع">
                @foreach (['mada' => 'مدى', 'visa' => 'Visa', 'mastercard' => 'Mastercard', 'apple_pay' => 'Apple Pay'] as $key => $label)
                    @if (!empty($payments[$key]))
                        <li><span>{{ $label }}</span></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <div class="store-footer__bottom">
        <p>© {{ now()->year }} عجيبة. جميع الحقوق محفوظة.</p>
    </div>
</footer>
