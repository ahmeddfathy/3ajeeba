<div class="store-bottom-dock">
    {{-- شريط تأكيد الطلب --}}
    <div class="store-cart-sticky-bar" data-cart-sticky-bar hidden>
        <div class="store-cart-sticky-bar__inner">
            <div class="store-cart-sticky-bar__summary">
                <span class="store-cart-sticky-bar__count" data-cart-bar-count>0 منتجات</span>
                <strong class="store-cart-sticky-bar__total" data-cart-bar-total>0 ج.م</strong>
            </div>
            <div class="store-cart-sticky-bar__actions">
                <a href="#" target="_blank" rel="noopener noreferrer" class="store-cart-sticky-bar__cta" data-cart-bar-whatsapp>
                    <span>تأكيد الطلب</span>
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </a>
                <button type="button" class="store-cart-sticky-bar__view" data-open-drawer="cart">
                    عرض السلة
                </button>
            </div>
        </div>
    </div>

    @include('partials.store.bottom-nav')
</div>
