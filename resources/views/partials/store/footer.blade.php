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

        <div class="store-footer__col">
            <h3 class="store-footer__heading">روابط تهمك</h3>
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

        <div class="store-footer__col">
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
                            <span class="is-disabled" aria-label="{{ $label }} (قريبًا)" title="قريبًا">
                                @include('partials.store.social-icon', ['name' => $key])
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="store-footer__bottom">
        <p>© {{ now()->year }} عجيبة. جميع الحقوق محفوظة.</p>
    </div>

    {{-- Floating scroll to top button matching reference design --}}
    <button type="button" class="store-scroll-top" data-scroll-top aria-label="الرجوع للأعلى">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>
</footer>
