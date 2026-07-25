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
                        $key = $item['key'] ?? null;
                        $routeName = $item['route'] ?? null;
                        if ($routeName === 'contact') continue; // Contact link is placed on the left side in actions

                        $children = $item['children'] ?? [];

                        if ($key === 'categories' && isset($storeCategories) && $storeCategories->isNotEmpty()) {
                            $children = array_merge([
                                ['label' => 'جميع الأقسام', 'url' => route('products.index')]
                            ], $storeCategories->map(fn($c) => [
                                'label' => $c->name,
                                'url' => route('products.index', ['category' => $c->slug])
                            ])->all());
                        } elseif ($key === 'collections' && isset($storeCollections) && $storeCollections->isNotEmpty()) {
                            $children = array_merge([
                                ['label' => 'جميع المجموعات', 'url' => route('products.index')]
                            ], $storeCollections->map(fn($c) => [
                                'label' => $c->name,
                                'url' => route('products.index', ['collection' => $c->slug])
                            ])->all());
                        }

                        $hasChildren = !empty($children);
                        $isActive = ($routeName === 'home' && $activeNav === 'home')
                            || ($routeName === 'products.index' && $activeNav === 'products' && !$key)
                            || ($key === 'categories' && request()->has('category'))
                            || ($key === 'collections' && request()->has('collection'))
                            || ($routeName === 'blog.index' && $activeNav === 'blog');
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
                                @foreach ($children as $child)
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
            <a href="{{ route('contact') }}" class="store-header__contact-btn {{ $activeNav === 'contact' ? 'is-active' : '' }}">
                تواصل معنا
            </a>

            <button type="button" class="store-icon-btn" data-open-search aria-label="البحث">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>

            <button type="button" class="store-icon-btn" data-open-drawer="cart" aria-label="السلة">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 11V7a4 4 0 0 0-8 0v4"/>
                    <rect x="4" y="7" width="16" height="14" rx="3"/>
                </svg>
                <span class="store-badge" data-cart-count hidden>0</span>
            </button>
        </div>
    </div>
</header>
