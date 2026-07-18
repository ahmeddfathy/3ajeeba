@php
    $items = config('store.bottom_nav', []);
    $active = trim($__env->yieldContent('active_nav') ?: 'home');
@endphp

<nav class="store-bottom-nav" aria-label="التنقل السريع">
    @foreach ($items as $item)
        @if ($item['key'] === 'categories')
            <button
                type="button"
                class="store-bottom-nav__item {{ $active === 'categories' ? 'is-active' : '' }}"
                data-open-drawer="categories"
                aria-label="الأقسام"
            >
                @include('partials.store.bottom-nav-icon', ['icon' => $item['icon']])
                <span>{{ $item['label'] }}</span>
            </button>
        @elseif ($item['key'] === 'cart')
            <button
                type="button"
                class="store-bottom-nav__item {{ $active === 'cart' ? 'is-active' : '' }}"
                data-open-drawer="cart"
                aria-label="السلة"
            >
                <span class="store-bottom-nav__icon-wrap">
                    @include('partials.store.bottom-nav-icon', ['icon' => $item['icon']])
                    <span class="store-badge" data-cart-count hidden>0</span>
                </span>
                <span>{{ $item['label'] }}</span>
            </button>
        @else
            <a
                href="{{ url($item['url']) }}"
                class="store-bottom-nav__item {{ $active === $item['key'] ? 'is-active' : '' }}"
            >
                @include('partials.store.bottom-nav-icon', ['icon' => $item['icon']])
                <span>{{ $item['label'] }}</span>
            </a>
        @endif
    @endforeach
</nav>
