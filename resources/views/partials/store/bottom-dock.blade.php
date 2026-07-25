<div class="store-bottom-dock">
    {{-- شريط تأكيد الطلب — فوق الناف بار مباشرة --}}
    <div class="store-cart-sticky-bar" data-cart-sticky-bar hidden>
        <div class="store-cart-sticky-bar__inner">
            <div class="store-cart-sticky-bar__summary">
                <span class="store-cart-sticky-bar__count" data-cart-bar-count>0 منتجات</span>
                <strong class="store-cart-sticky-bar__total" data-cart-bar-total>0 ج.م</strong>
            </div>
            <div class="store-cart-sticky-bar__actions">
                <button type="button" class="store-cart-sticky-bar__view" data-open-drawer="cart">
                    عرض السلة
                </button>
                <a href="#" target="_blank" rel="noopener noreferrer" class="store-cart-sticky-bar__cta" data-cart-bar-whatsapp>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 11V7a4 4 0 0 0-8 0v4"/>
                        <rect x="4" y="7" width="16" height="14" rx="3"/>
                    </svg>
                    <span>تأكيد الطلب</span>
                </a>
            </div>
        </div>
    </div>

    @include('partials.store.bottom-nav')
</div>
