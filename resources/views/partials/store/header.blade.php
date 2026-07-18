@php
    $nav = config('store.nav', []);
    $logo = asset('assets/brand/logo.jpeg');
    $activeNav = trim($__env->yieldContent('active_nav') ?: 'home');
@endphp

<header class="store-header" data-store-header>
    <div class="store-header__inner">
        <button
            type="button"
            class="store-icon-btn store-header__menu-btn"
            data-open-drawer="nav"
            aria-label="فتح القائمة"
            aria-expanded="false"
            aria-controls="store-nav-drawer"
        >
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </button>

        <nav class="store-nav" aria-label="التنقل الرئيسي">
            <ul class="store-nav__list">
                @foreach ($nav as $item)
                    @php
                        $hasChildren = !empty($item['children']);
                        $routeName = $item['route'] ?? null;
                        $isActive = ($routeName === 'home' && $activeNav === 'home')
                            || ($routeName === 'products.index' && $activeNav === 'products')
                            || ($routeName === 'contact' && $activeNav === 'contact')
                            || ($routeName === 'blog.index' && $activeNav === 'blog')
                            || (!empty($item['active']) && $activeNav === 'home');
                    @endphp
                    <li class="store-nav__item {{ $hasChildren ? 'has-dropdown' : '' }}">
                        @if ($hasChildren)
                            <button
                                type="button"
                                class="store-nav__link {{ $isActive ? 'is-active' : '' }}"
                                data-dropdown-trigger
                                aria-expanded="false"
                                aria-haspopup="true"
                            >
                                {{ $item['label'] }}
                                <svg class="store-nav__caret" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                    <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <ul class="store-dropdown" data-dropdown-menu>
                                @foreach ($item['children'] as $child)
                                    <li>
                                        <a href="{{ url($child['url']) }}">{{ $child['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a
                                href="{{ url($item['url']) }}"
                                class="store-nav__link {{ $isActive ? 'is-active' : '' }}"
                                @if ($isActive) aria-current="page" @endif
                            >
                                {{ $item['label'] }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <a href="{{ route('home') }}" class="store-logo" aria-label="عجيبة — الصفحة الرئيسية">
            <img
                src="{{ $logo }}"
                alt="شعار عجيبة"
                width="732"
                height="732"
                decoding="async"
            >
        </a>

        <div class="store-header__actions">
            <button type="button" class="store-icon-btn" data-open-search aria-label="البحث">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>

            <button type="button" class="store-icon-btn" data-open-drawer="cart" aria-label="السلة">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 8h10l-1 11H8L7 8z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    <path d="M9 8a3 3 0 0 1 6 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
                <span class="store-badge" data-cart-count hidden>0</span>
            </button>
        </div>
    </div>
</header>
