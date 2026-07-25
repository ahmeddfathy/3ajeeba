@php
    $whatsapp = \App\Models\Setting::whatsappNumber();
    $checkoutMode = \App\Models\Setting::checkoutMode();
    $allowWhatsApp = \App\Models\Setting::allowsWhatsAppCheckout();
    $allowOnline = \App\Models\Setting::allowsOnlineCheckout();
@endphp

<div class="store-overlay" data-store-overlay hidden></div>

{{-- Mobile navigation drawer --}}
<aside
    id="store-nav-drawer"
    class="store-drawer store-drawer--nav"
    data-drawer="nav"
    hidden
    aria-hidden="true"
    aria-label="قائمة التنقل"
>
    <div class="store-drawer__head">
        <h2>القائمة</h2>
        <button type="button" class="store-icon-btn" data-close-drawer aria-label="إغلاق القائمة">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
    <nav class="store-drawer__nav">
        <ul>
            @foreach (config('store.nav', []) as $item)
                <li>
                    <a href="{{ url($item['url']) }}" data-close-drawer>{{ $item['label'] }}</a>
                    @if (!empty($item['children']))
                        <ul>
                            @foreach ($item['children'] as $child)
                                <li><a href="{{ url($child['url']) }}" data-close-drawer>{{ $child['label'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
</aside>

{{-- Categories sheet --}}
<aside
    class="store-drawer store-drawer--panel store-drawer--categories"
    data-drawer="categories"
    hidden
    aria-hidden="true"
    aria-label="الأقسام"
>
    <div class="store-drawer__head">
        <h2>الأقسام</h2>
        <button type="button" class="store-icon-btn" data-close-drawer aria-label="إغلاق الأقسام">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
    <div class="store-drawer__body">
        <ul class="store-categories-sheet">
            <li>
                <a href="{{ route('products.index') }}" data-close-drawer>
                    <span>جميع المنتجات</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    </svg>
                </a>
            </li>
            @foreach (($storeCategories ?? collect()) as $category)
                <li>
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" data-close-drawer>
                        <span>{{ $category->name }}</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                    </a>
                </li>
            @endforeach
            @foreach (($storeCollections ?? collect()) as $collection)
                <li>
                    <a href="{{ route('products.index', ['collection' => $collection->slug]) }}" data-close-drawer>
                        <span>{{ $collection->name }}</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M14 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>

{{-- Cart drawer --}}
<aside
    class="store-drawer store-drawer--panel"
    data-drawer="cart"
    hidden
    aria-hidden="true"
    aria-label="سلة التسوق"
>
    <div class="store-drawer__head">
        <h2>السلة</h2>
        <button type="button" class="store-icon-btn" data-close-drawer aria-label="إغلاق السلة">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
    <div class="store-drawer__body" data-cart-items></div>
    <div class="store-drawer__foot" data-cart-footer hidden>
        <div class="store-drawer__total">
            <span>الإجمالي</span>
            <strong data-cart-total>0 {{ config('store.currency', 'ج.م') }}</strong>
        </div>

        @if ($allowOnline)
            <form class="store-checkout-form" data-online-checkout novalidate>
                <p class="store-drawer__note">أكملي بياناتك وسنستلم طلبك مباشرة.</p>
                <div class="store-checkout-form__fields">
                    <label>
                        <span>الاسم *</span>
                        <input type="text" name="customer_name" required autocomplete="name" placeholder="اسمكِ">
                    </label>
                    <label>
                        <span>رقم الموبايل *</span>
                        <input type="tel" name="customer_phone" required autocomplete="tel" dir="ltr" placeholder="01xxxxxxxxx">
                    </label>
                    <label>
                        <span>المحافظة *</span>
                        <input type="text" name="governorate" required placeholder="بني سويف">
                    </label>
                    <label>
                        <span>العنوان *</span>
                        <textarea name="address" rows="2" required placeholder="الشارع / المنطقة / علامة مميزة"></textarea>
                    </label>
                    <label>
                        <span>ملاحظات</span>
                        <textarea name="notes" rows="2" placeholder="اختياري"></textarea>
                    </label>
                </div>
                <p class="store-checkout-form__error" data-checkout-error hidden></p>
                <button type="submit" class="store-btn store-btn--primary store-btn--block" data-online-submit>
                    تأكيد الطلب
                </button>
            </form>
        @endif

        @if ($allowWhatsApp)
            @if ($allowOnline)
                <p class="store-drawer__divider"><span>أو</span></p>
            @else
                <p class="store-drawer__note">سيتم تحويلك إلى واتساب لإتمام الطلب مع فريق عجيبة.</p>
            @endif
            <a
                href="https://wa.me/{{ $whatsapp }}"
                class="store-wa-btn store-wa-btn--block {{ $allowOnline ? '' : '' }}"
                data-whatsapp-checkout
                target="_blank"
                rel="noopener noreferrer"
                aria-disabled="true"
            >
                @include('partials.store.icon-whatsapp', ['size' => 20])
                <span>إتمام الطلب عبر واتساب</span>
            </a>
        @endif
    </div>
</aside>



{{-- Search dialog --}}
<dialog class="store-search" data-search-dialog aria-label="البحث في المتجر">
    <form class="store-search__form" data-search-form action="{{ route('products.index') }}" method="get">
        <div class="store-search__bar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.6"/>
                <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
            <input
                type="search"
                name="q"
                placeholder="ابحثي عن منتج..."
                data-search-input
                autocomplete="off"
            >
            <button type="submit" class="store-btn store-btn--primary">بحث</button>
            <button type="button" class="store-icon-btn" data-close-search aria-label="إغلاق البحث">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <p class="store-search__hint">اكتبِ اسم المنتج ثم اضغطي بحث.</p>
    </form>
</dialog>

{{-- Quick Pick Variant Selection Modal --}}
<div class="store-quick-pick" data-quick-pick-modal hidden>
    <div class="store-quick-pick__backdrop" data-close-quick-pick></div>
    <div class="store-quick-pick__dialog">
        <button type="button" class="store-quick-pick__close" data-close-quick-pick aria-label="إغلاق">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
        <div class="store-quick-pick__head">
            <img src="" alt="" class="store-quick-pick__img" data-qp-img hidden>
            <div class="store-quick-pick__meta">
                <h3 class="store-quick-pick__title" data-qp-title></h3>
                <div class="store-quick-pick__price" data-qp-price></div>
            </div>
        </div>
        <div class="store-quick-pick__body">
            <div class="store-quick-pick__group" data-qp-color-group hidden>
                <label class="store-quick-pick__label">اختاري اللون:</label>
                <div class="store-quick-pick__options" data-qp-colors></div>
            </div>
            <div class="store-quick-pick__group" data-qp-size-group hidden>
                <label class="store-quick-pick__label">اختاري المقاس:</label>
                <div class="store-quick-pick__options" data-qp-sizes></div>
            </div>
        </div>
        <div class="store-quick-pick__foot">
            <button type="button" class="store-btn store-btn--primary store-btn--block" data-qp-submit>
                تأكيد وإضافة للسلة
            </button>
        </div>
    </div>
</div>
