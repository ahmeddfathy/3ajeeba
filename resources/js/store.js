const CART_KEY = '3ajeeba_cart';
const WISHLIST_KEY = '3ajeeba_wishlist';

function readStorage(key, fallback = []) {
    try {
        const raw = localStorage.getItem(key);
        if (!raw) return fallback;
        const parsed = JSON.parse(raw);
        return Array.isArray(parsed) ? parsed : fallback;
    } catch {
        return fallback;
    }
}

function writeStorage(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

function createStore() {
    const body = document.body;
    const currency = body.dataset.currency || 'ر.س';
    const whatsapp = (body.dataset.whatsapp || '').replace(/\D+/g, '');
    const intro = body.dataset.whatsappIntro || 'السلام عليكم، أرغب بطلب المنتجات التالية من عجيبة:\n\n';
    const checkoutMode = body.dataset.checkoutMode || 'whatsapp';
    const ordersUrl = body.dataset.ordersUrl || '/orders';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const overlay = document.querySelector('[data-store-overlay]');
    const drawers = Array.from(document.querySelectorAll('[data-drawer]'));
    const header = document.querySelector('[data-store-header]');
    const searchDialog = document.querySelector('[data-search-dialog]');
    const cartCountEls = document.querySelectorAll('[data-cart-count]');
    const wishlistCountEls = document.querySelectorAll('[data-wishlist-count]');
    const cartItemsEl = document.querySelector('[data-cart-items]');
    const cartFooterEl = document.querySelector('[data-cart-footer]');
    const cartTotalEl = document.querySelector('[data-cart-total]');
    const wishlistItemsEl = document.querySelector('[data-wishlist-items]');
    const whatsappCheckout = document.querySelector('[data-whatsapp-checkout]');
    const onlineCheckoutForm = document.querySelector('[data-online-checkout]');
    const checkoutErrorEl = document.querySelector('[data-checkout-error]');
    const onlineSubmitBtn = document.querySelector('[data-online-submit]');

    let cart = readStorage(CART_KEY);
    let wishlist = readStorage(WISHLIST_KEY);

    function formatMoney(amount) {
        return `${Number(amount || 0).toLocaleString('ar-SA')} ${currency}`;
    }

    function buildWhatsAppUrl(message) {
        if (!whatsapp) return null;
        return `https://wa.me/${whatsapp}?text=${encodeURIComponent(message)}`;
    }

    function cartMessage() {
        const lines = cart.map((item, index) => {
            const variantLine = item.variantLabel ? `الفاريانت: ${item.variantLabel}\n` : '';
            return `${index + 1}) ${item.name}\n${variantLine}الكمية: ${item.quantity}\nالسعر: ${formatMoney(item.price)}\n`;
        });
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        return `${intro}${lines.join('\n')}الإجمالي: ${formatMoney(total)}\n`;
    }

    function cartLineKey(item) {
        return `${item.id}:${item.variantId || 'base'}`;
    }

    function syncBadges() {
        const cartQty = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        cartCountEls.forEach((el) => {
            el.textContent = String(cartQty);
            el.hidden = cartQty === 0;
        });

        wishlistCountEls.forEach((el) => {
            el.textContent = String(wishlist.length);
            el.hidden = wishlist.length === 0;
        });

        document.querySelectorAll('[data-wishlist-toggle]').forEach((btn) => {
            const id = String(btn.dataset.productId);
            const active = wishlist.some((item) => String(item.id) === id);
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }

    const stickyCartBar = document.querySelector('[data-cart-sticky-bar]');
    const cartBarCount = stickyCartBar?.querySelector('[data-cart-bar-count]');
    const cartBarTotal = stickyCartBar?.querySelector('[data-cart-bar-total]');
    const cartBarWhatsapp = stickyCartBar?.querySelector('[data-cart-bar-whatsapp]');

    const quickPickModal = document.querySelector('[data-quick-pick-modal]');
    const qpImg = quickPickModal?.querySelector('[data-qp-img]');
    const qpTitle = quickPickModal?.querySelector('[data-qp-title]');
    const qpPrice = quickPickModal?.querySelector('[data-qp-price]');
    const qpColorGroup = quickPickModal?.querySelector('[data-qp-color-group]');
    const qpColors = quickPickModal?.querySelector('[data-qp-colors]');
    const qpSizeGroup = quickPickModal?.querySelector('[data-qp-size-group]');
    const qpSizes = quickPickModal?.querySelector('[data-qp-sizes]');

    let currentQpCard = null;
    let currentQpVariants = [];
    let currentQpSelectedColor = '';
    let currentQpSelectedSize = '';

    function closeQuickPick() {
        if (!quickPickModal) return;
        quickPickModal.hidden = true;
        currentQpCard = null;
        currentQpVariants = [];
    }

    function openQuickPick(card) {
        if (!quickPickModal || !card) return;
        currentQpCard = card;
        try {
            currentQpVariants = JSON.parse(card.dataset.variants || '[]');
        } catch (e) {
            currentQpVariants = [];
        }

        if (!currentQpVariants.length) return;

        if (qpImg) {
            const image = card.dataset.productImage || '';
            if (image) {
                qpImg.src = image;
                qpImg.hidden = false;
            } else {
                qpImg.removeAttribute('src');
                qpImg.hidden = true;
            }
        }
        if (qpTitle) qpTitle.textContent = card.dataset.productName || '';
        if (qpPrice) qpPrice.textContent = formatMoney(card.dataset.productPrice || 0);

        const colors = [...new Set(currentQpVariants.map((v) => v.color).filter(Boolean))];
        const sizes = [...new Set(currentQpVariants.map((v) => v.size).filter(Boolean))];

        currentQpSelectedColor = colors[0] || '';
        currentQpSelectedSize = sizes[0] || '';

        if (colors.length && qpColorGroup && qpColors) {
            qpColorGroup.hidden = false;
            qpColors.innerHTML = colors.map((c) => `
                <button type="button" class="store-quick-pick__btn ${c === currentQpSelectedColor ? 'is-selected' : ''}" data-qp-opt-color="${c}">${c}</button>
            `).join('');
        } else if (qpColorGroup) {
            qpColorGroup.hidden = true;
        }

        if (sizes.length && qpSizeGroup && qpSizes) {
            qpSizeGroup.hidden = false;
            qpSizes.innerHTML = sizes.map((s) => `
                <button type="button" class="store-quick-pick__btn ${s === currentQpSelectedSize ? 'is-selected' : ''}" data-qp-opt-size="${s}">${s}</button>
            `).join('');
        } else if (qpSizeGroup) {
            qpSizeGroup.hidden = true;
        }

        updateQpSelection();
        quickPickModal.hidden = false;
    }

    function updateQpSelection() {
        if (!currentQpVariants.length) return;
        const match = currentQpVariants.find((v) => {
            const matchColor = !v.color || v.color === currentQpSelectedColor;
            const matchSize = !v.size || v.size === currentQpSelectedSize;
            return matchColor && matchSize;
        }) || currentQpVariants[0];

        if (match && qpPrice) {
            qpPrice.textContent = formatMoney(match.price);
        }

        qpColors?.querySelectorAll('[data-qp-opt-color]').forEach((btn) => {
            btn.classList.toggle('is-selected', btn.dataset.qpOptColor === currentQpSelectedColor);
        });

        qpSizes?.querySelectorAll('[data-qp-opt-size]').forEach((btn) => {
            btn.classList.toggle('is-selected', btn.dataset.qpOptSize === currentQpSelectedSize);
        });
    }

    quickPickModal?.querySelectorAll('[data-close-quick-pick]').forEach((btn) => {
        btn.addEventListener('click', closeQuickPick);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && quickPickModal && !quickPickModal.hidden) {
            closeQuickPick();
        }
    });

    quickPickModal?.addEventListener('click', (e) => {
        const colorBtn = e.target.closest('[data-qp-opt-color]');
        if (colorBtn) {
            currentQpSelectedColor = colorBtn.dataset.qpOptColor;
            updateQpSelection();
            return;
        }

        const sizeBtn = e.target.closest('[data-qp-opt-size]');
        if (sizeBtn) {
            currentQpSelectedSize = sizeBtn.dataset.qpOptSize;
            updateQpSelection();
            return;
        }

        if (e.target.closest('[data-qp-submit]')) {
            if (!currentQpCard || !currentQpVariants.length) return;
            const match = currentQpVariants.find((v) => {
                const matchColor = !v.color || v.color === currentQpSelectedColor;
                const matchSize = !v.size || v.size === currentQpSelectedSize;
                return matchColor && matchSize;
            }) || currentQpVariants[0];

            const labelParts = [];
            if (match.size) labelParts.push(`المقاس: ${match.size}`);
            if (match.color) labelParts.push(`اللون: ${match.color}`);

            const product = {
                id: Number(currentQpCard.dataset.productId),
                name: currentQpCard.dataset.productName,
                price: Number(match.price),
                image: currentQpCard.dataset.productImage || '',
                quantity: 1,
                variantId: match.id,
                variantLabel: match.label || labelParts.join(' | '),
                size: match.size || '',
                color: match.color || '',
            };

            addToCart(product, { open: false });
            closeQuickPick();
        }
    });

    function syncStickyCartBar() {
        if (!stickyCartBar) return;
        const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        const totalAmount = cart.reduce((sum, item) => sum + item.price * (item.quantity || 1), 0);

        if (totalItems > 0) {
            stickyCartBar.hidden = false;
            if (cartBarCount) {
                cartBarCount.textContent = totalItems === 1 ? 'منتج واحد' : `${totalItems} منتجات`;
            }
            if (cartBarTotal) cartBarTotal.textContent = formatMoney(totalAmount);
            if (cartBarWhatsapp) {
                const url = buildWhatsAppUrl(cartMessage());
                cartBarWhatsapp.href = url || '#';
            }
        } else {
            stickyCartBar.hidden = true;
        }
    }

    function persist() {
        writeStorage(CART_KEY, cart);
        writeStorage(WISHLIST_KEY, wishlist);
        syncBadges();
        renderCart();
        renderWishlist();
        syncWhatsAppCheckout();
        syncStickyCartBar();
    }

    function syncWhatsAppCheckout() {
        if (!whatsappCheckout) return;
        const url = cart.length ? buildWhatsAppUrl(cartMessage()) : null;
        if (url) {
            whatsappCheckout.href = url;
            whatsappCheckout.removeAttribute('aria-disabled');
            whatsappCheckout.classList.remove('is-disabled');
        } else {
            whatsappCheckout.href = '#';
            whatsappCheckout.setAttribute('aria-disabled', 'true');
            whatsappCheckout.classList.add('is-disabled');
        }
    }

    function openDrawer(name) {
        const drawer = drawers.find((el) => el.dataset.drawer === name);
        if (!drawer) return;

        closeDrawer({ keepOverlay: true });
        drawer.hidden = false;
        drawer.setAttribute('aria-hidden', 'false');
        requestAnimationFrame(() => drawer.classList.add('is-open'));

        if (overlay) overlay.hidden = false;

        const menuBtn = document.querySelector('[data-open-drawer="nav"]');
        if (menuBtn && name === 'nav') {
            menuBtn.setAttribute('aria-expanded', 'true');
        }

        document.body.style.overflow = 'hidden';
    }

    function closeDrawer({ keepOverlay = false } = {}) {
        drawers.forEach((drawer) => {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            window.setTimeout(() => {
                if (!drawer.classList.contains('is-open')) {
                    drawer.hidden = true;
                }
            }, 220);
        });

        document.querySelector('[data-open-drawer="nav"]')?.setAttribute('aria-expanded', 'false');

        if (!keepOverlay && overlay) overlay.hidden = true;
        if (!keepOverlay) document.body.style.overflow = '';
    }

    function renderCart() {
        if (!cartItemsEl) return;

        if (!cart.length) {
            cartItemsEl.innerHTML = '<p class="store-empty-drawer">سلتك فارغة حاليًا.</p>';
            if (cartFooterEl) cartFooterEl.hidden = true;
            return;
        }

        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

        cartItemsEl.innerHTML = cart.map((item) => {
            const key = cartLineKey(item);
            return `
            <div class="store-line" data-cart-line="${key}">
                <div class="store-line__media">
                    ${item.image
                        ? `<img src="${item.image}" alt="${item.name}" width="64" height="64" loading="lazy">`
                        : '<div class="store-placeholder" aria-hidden="true"></div>'}
                </div>
                <div>
                    <h3>${item.name}</h3>
                    ${item.variantLabel ? `<p>${item.variantLabel}</p>` : ''}
                    <p>${formatMoney(item.price)}</p>
                </div>
                <div class="store-line__actions">
                    <div class="store-qty">
                        <button type="button" data-qty-decrease="${key}" aria-label="تقليل الكمية">−</button>
                        <span>${item.quantity}</span>
                        <button type="button" data-qty-increase="${key}" aria-label="زيادة الكمية">+</button>
                    </div>
                    <button type="button" class="store-icon-btn" data-remove-cart="${key}" aria-label="إزالة من السلة">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6 7h12M9 7V5h6v2M8 7l1 12h6l1-12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        }).join('');

        if (cartFooterEl) cartFooterEl.hidden = false;
        if (cartTotalEl) cartTotalEl.textContent = formatMoney(total);
    }

    function renderWishlist() {
        if (!wishlistItemsEl) return;

        if (!wishlist.length) {
            wishlistItemsEl.innerHTML = '<p class="store-empty-drawer">لا توجد منتجات في المفضلة.</p>';
            return;
        }

        wishlistItemsEl.innerHTML = wishlist.map((item) => `
            <div class="store-line">
                <div class="store-line__media">
                    ${item.image
                        ? `<img src="${item.image}" alt="${item.name}" width="64" height="64" loading="lazy">`
                        : '<div class="store-placeholder" aria-hidden="true"></div>'}
                </div>
                <div>
                    <h3>${item.name}</h3>
                    <p>${formatMoney(item.price)}</p>
                </div>
                <div class="store-line__actions">
                    <button type="button" class="store-btn store-btn--ghost" data-move-to-cart="${item.id}">أضيفي للسلة</button>
                    <button type="button" class="store-icon-btn" data-remove-wishlist="${item.id}" aria-label="إزالة من المفضلة">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    function productFromCard(card) {
        const qtyInput = card.querySelector('[data-pdp-qty]');
        const quantity = Math.max(1, Number(qtyInput?.value || 1));
        const hasVariants = card.dataset.hasVariants === '1';
        const variantId = card.dataset.variantId ? Number(card.dataset.variantId) : null;
        const isPdp = card.classList.contains('store-pdp');

        if (hasVariants && !variantId && isPdp) {
            return null;
        }

        return {
            id: Number(card.dataset.productId),
            name: card.dataset.productName,
            price: Number(card.dataset.productPrice || 0),
            image: card.dataset.productImage || '',
            quantity,
            variantId,
            variantLabel: card.dataset.variantLabel || '',
            size: card.dataset.variantSize || '',
            color: card.dataset.variantColor || '',
        };
    }

    function addToCart(product, { open = true } = {}) {
        if (!product) {
            alert('اختاري المقاس واللون أولًا');
            return;
        }

        const existing = cart.find((item) => cartLineKey(item) === cartLineKey(product));
        if (existing) {
            existing.quantity += product.quantity || 1;
        } else {
            cart.push({ ...product, quantity: product.quantity || 1 });
        }
        persist();
        if (open) openDrawer('cart');
    }

    function initVariantPicker() {
        const card = document.querySelector('.store-pdp[data-has-variants="1"]');
        const jsonEl = document.querySelector('[data-variants-json]');
        if (!card || !jsonEl) return;

        let variants = [];
        try {
            variants = JSON.parse(jsonEl.textContent || '[]');
        } catch {
            variants = [];
        }
        if (!variants.length) return;

        let selectedColor = card.dataset.variantColor || variants[0].color || '';
        let selectedSize = card.dataset.variantSize || variants[0].size || '';

        function findVariant() {
            return variants.find((variant) => {
                const colorOk = !variant.color || variant.color === selectedColor || (!selectedColor && !variant.color);
                const sizeOk = !variant.size || variant.size === selectedSize || (!selectedSize && !variant.size);
                const matchColor = selectedColor ? variant.color === selectedColor : !variant.color || variants.every((v) => !v.color);
                const matchSize = selectedSize ? variant.size === selectedSize : !variant.size || variants.every((v) => !v.size);

                if (selectedColor && selectedSize) {
                    return variant.color === selectedColor && variant.size === selectedSize;
                }
                if (selectedColor && !selectedSize) {
                    return variant.color === selectedColor;
                }
                if (!selectedColor && selectedSize) {
                    return variant.size === selectedSize;
                }
                return colorOk && sizeOk && matchColor && matchSize;
            }) || variants.find((variant) => (
                (!selectedColor || variant.color === selectedColor)
                && (!selectedSize || variant.size === selectedSize)
            )) || variants[0];
        }

        function availableSizesForColor(color) {
            return [...new Set(variants.filter((v) => !color || v.color === color).map((v) => v.size).filter(Boolean))];
        }

        function applyVariant(variant) {
            if (!variant) return;
            card.dataset.variantId = String(variant.id);
            card.dataset.variantLabel = variant.label || '';
            card.dataset.variantSize = variant.size || '';
            card.dataset.variantColor = variant.color || '';
            card.dataset.productPrice = String(variant.price);

            const priceEl = card.querySelector('[data-pdp-price]');
            const originalEl = card.querySelector('[data-pdp-original]');
            const discountEl = card.querySelector('[data-pdp-discount]');
            const labelEl = card.querySelector('[data-variant-label-text]');
            const colorText = card.querySelector('[data-selected-color]');
            const sizeText = card.querySelector('[data-selected-size]');

            if (priceEl) priceEl.textContent = formatMoney(variant.price);
            if (labelEl) labelEl.textContent = variant.label || '';
            if (colorText) colorText.textContent = variant.color || '';
            if (sizeText) sizeText.textContent = variant.size || '';

            if (originalEl) {
                if (variant.original_price && variant.original_price > variant.price) {
                    originalEl.hidden = false;
                    originalEl.textContent = formatMoney(variant.original_price);
                } else {
                    originalEl.hidden = true;
                    originalEl.textContent = '';
                }
            }

            if (discountEl) {
                if (variant.original_price && variant.original_price > variant.price) {
                    const pct = Math.round((1 - variant.price / variant.original_price) * 100);
                    discountEl.hidden = false;
                    discountEl.textContent = `خصم ${pct}%`;
                } else {
                    discountEl.hidden = true;
                }
            }
        }

        function refreshSizeButtons() {
            const sizes = availableSizesForColor(selectedColor);
            card.querySelectorAll('[data-select-size]').forEach((btn) => {
                const size = btn.dataset.selectSize;
                const enabled = sizes.includes(size);
                btn.disabled = !enabled;
                btn.classList.toggle('is-disabled', !enabled);
                if (!enabled && selectedSize === size) {
                    selectedSize = sizes[0] || '';
                }
            });
        }

        function syncActiveButtons() {
            card.querySelectorAll('[data-select-color]').forEach((btn) => {
                const active = btn.dataset.selectColor === selectedColor;
                btn.classList.toggle('is-active', active);
                btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
            card.querySelectorAll('[data-select-size]').forEach((btn) => {
                const active = btn.dataset.selectSize === selectedSize;
                btn.classList.toggle('is-active', active);
                btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        }

        card.addEventListener('click', (event) => {
            const colorBtn = event.target.closest('[data-select-color]');
            if (colorBtn) {
                selectedColor = colorBtn.dataset.selectColor || '';
                refreshSizeButtons();
                if (selectedSize && !availableSizesForColor(selectedColor).includes(selectedSize)) {
                    selectedSize = availableSizesForColor(selectedColor)[0] || '';
                }
                syncActiveButtons();
                applyVariant(findVariant());
                return;
            }

            const sizeBtn = event.target.closest('[data-select-size]');
            if (sizeBtn && !sizeBtn.disabled) {
                selectedSize = sizeBtn.dataset.selectSize || '';
                syncActiveButtons();
                applyVariant(findVariant());
            }
        });

        // seed dataset from default
        selectedColor = variants[0].color || '';
        selectedSize = variants[0].size || '';
        refreshSizeButtons();
        syncActiveButtons();
        applyVariant(findVariant());
    }

    function toggleWishlist(product) {
        const index = wishlist.findIndex((item) => item.id === product.id);
        if (index >= 0) {
            wishlist.splice(index, 1);
        } else {
            wishlist.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
            });
        }
        persist();
    }

    document.addEventListener('click', (event) => {
        const openDrawerBtn = event.target.closest('[data-open-drawer]');
        if (openDrawerBtn) {
            openDrawer(openDrawerBtn.dataset.openDrawer);
            return;
        }

        if (event.target.closest('[data-close-drawer]')) {
            closeDrawer();
            return;
        }

        if (event.target === overlay) {
            closeDrawer();
            return;
        }

        if (event.target.closest('[data-close-search]')) {
            searchDialog?.close();
            return;
        }

        const addBtn = event.target.closest('[data-add-to-cart]');
        if (addBtn) {
            const card = addBtn.closest('[data-product-card]');
            if (!card) return;

            addToCart(productFromCard(card), { open: false });
            return;
        }

        const waBuy = event.target.closest('[data-whatsapp-buy]');
        if (waBuy) {
            const card = waBuy.closest('[data-product-card]');
            if (!card) return;
            const isPdp = card.classList.contains('store-pdp');
            const product = productFromCard(card);
            if (!product) {
                alert('اختاري المقاس واللون أولًا');
                return;
            }

            let message;
            if (isPdp) {
                const variantLine = product.variantLabel ? `الفاريانت: ${product.variantLabel}\n` : '';
                message = `${intro}1) ${product.name}\n${variantLine}الكمية: ${product.quantity}\nالسعر: ${formatMoney(product.price)}\n\nالإجمالي: ${formatMoney(product.price * product.quantity)}\n`;
            } else {
                message = `السلام عليكم، أريد الاستفسار عن المنتج التالي من عجيبة:\n\n${product.name}\nالسعر: ${formatMoney(product.price)}\n\n(المقاس واللون حسب التوفر)`;
            }

            const url = buildWhatsAppUrl(message);
            if (!url) {
                alert('رقم واتساب غير مضبوط بعد. أضيفيه في STORE_WHATSAPP داخل ملف .env');
                return;
            }
            window.open(url, '_blank', 'noopener,noreferrer');
            return;
        }

        const wishBtn = event.target.closest('[data-wishlist-toggle]');
        if (wishBtn) {
            const card = wishBtn.closest('[data-product-card]');
            if (card) toggleWishlist(productFromCard(card));
            return;
        }

        const increase = event.target.closest('[data-qty-increase]');
        if (increase) {
            const item = cart.find((row) => cartLineKey(row) === increase.dataset.qtyIncrease);
            if (item) {
                item.quantity += 1;
                persist();
            }
            return;
        }

        const decrease = event.target.closest('[data-qty-decrease]');
        if (decrease) {
            const item = cart.find((row) => cartLineKey(row) === decrease.dataset.qtyDecrease);
            if (item) {
                item.quantity = Math.max(1, item.quantity - 1);
                persist();
            }
            return;
        }

        const pdpInc = event.target.closest('[data-pdp-increase]');
        if (pdpInc) {
            const input = document.querySelector('[data-pdp-qty]');
            if (input) input.value = String(Math.max(1, Number(input.value || 1) + 1));
            return;
        }

        const pdpDec = event.target.closest('[data-pdp-decrease]');
        if (pdpDec) {
            const input = document.querySelector('[data-pdp-qty]');
            if (input) input.value = String(Math.max(1, Number(input.value || 1) - 1));
            return;
        }

        const removeCart = event.target.closest('[data-remove-cart]');
        if (removeCart) {
            cart = cart.filter((row) => cartLineKey(row) !== removeCart.dataset.removeCart);
            persist();
            return;
        }

        const removeWish = event.target.closest('[data-remove-wishlist]');
        if (removeWish) {
            wishlist = wishlist.filter((row) => String(row.id) !== removeWish.dataset.removeWishlist);
            persist();
            return;
        }

        const moveToCart = event.target.closest('[data-move-to-cart]');
        if (moveToCart) {
            const item = wishlist.find((row) => String(row.id) === moveToCart.dataset.moveToCart);
            if (item) addToCart({ ...item, quantity: 1 });
            return;
        }

        if (event.target.closest('[data-whatsapp-checkout]')) {
            if (!cart.length || !whatsapp) {
                event.preventDefault();
                if (!whatsapp) {
                    alert('رقم واتساب غير مضبوط بعد. أضيفيه من إعدادات المتجر في لوحة التحكم.');
                }
            }
            return;
        }

        const dropdownTrigger = event.target.closest('[data-dropdown-trigger]');
        if (dropdownTrigger) {
            const item = dropdownTrigger.closest('.store-nav__item');
            const open = item?.classList.contains('is-open');
            document.querySelectorAll('.store-nav__item.is-open').forEach((el) => el.classList.remove('is-open'));
            if (item && !open) {
                item.classList.add('is-open');
                dropdownTrigger.setAttribute('aria-expanded', 'true');
            } else {
                dropdownTrigger.setAttribute('aria-expanded', 'false');
            }
            return;
        }

        if (!event.target.closest('.store-nav__item')) {
            document.querySelectorAll('.store-nav__item.is-open').forEach((el) => {
                el.classList.remove('is-open');
                el.querySelector('[data-dropdown-trigger]')?.setAttribute('aria-expanded', 'false');
            });
        }

        const footerToggle = event.target.closest('.store-footer__toggle');
        if (footerToggle) {
            const col = footerToggle.closest('[data-footer-accordion]');
            const open = col?.classList.toggle('is-open');
            footerToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    });

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function suggestItemHtml(item, index) {
        const thumb = item.image
            ? `<img src="${escapeHtml(item.image)}" alt="" loading="lazy" decoding="async">`
            : `<span>${escapeHtml(String(item.title || '').slice(0, 1))}</span>`;
        const typeLabel = item.type === 'category'
            ? 'قسم'
            : item.type === 'collection'
                ? 'مجموعة'
                : 'منتج';

        return `
            <a
                href="${escapeHtml(item.url)}"
                class="store-search__suggest-item"
                role="option"
                data-suggest-index="${index}"
                id="suggest-option-${index}"
            >
                <span class="store-search__suggest-thumb">${thumb}</span>
                <span>
                    <p class="store-search__suggest-title">${escapeHtml(item.title)}</p>
                    <p class="store-search__suggest-sub">${escapeHtml(item.subtitle || '')}</p>
                </span>
                <span class="store-search__suggest-type">${typeLabel}</span>
            </a>
        `;
    }

    function renderSuggestions(panel, data) {
        const groups = [
            { key: 'categories', label: 'أقسام' },
            { key: 'collections', label: 'مجموعات' },
            { key: 'products', label: data.q ? 'منتجات' : 'مقترحات لك' },
        ];

        let html = '';
        let index = 0;
        const flat = [];

        groups.forEach((group) => {
            const items = Array.isArray(data[group.key]) ? data[group.key] : [];
            if (!items.length) return;
            html += `<div class="store-search__suggest-group">`;
            html += `<p class="store-search__suggest-label">${group.label}</p>`;
            items.forEach((item) => {
                flat.push(item);
                html += suggestItemHtml(item, index);
                index += 1;
            });
            html += `</div>`;
        });

        if (!flat.length) {
            html = data.q
                ? `<p class="store-search__suggest-empty">لا توجد نتائج قريبة — جرّبي كلمة أقصر أو اسم قسم.</p>`
                : `<p class="store-search__suggest-empty">ابدئي بالكتابة للبحث في المنتجات والأقسام.</p>`;
        }

        if (data.see_all_url) {
            html += `<a class="store-search__suggest-all" href="${escapeHtml(data.see_all_url)}">${escapeHtml(data.see_all_label || 'عرض كل النتائج')}</a>`;
        }

        panel.innerHTML = html;
        panel.hidden = false;
        return flat;
    }

    function initSearchSuggest(form) {
        const input = form.querySelector('[data-search-input]');
        const panel = form.querySelector('[data-search-suggest]');
        const hint = form.querySelector('[data-search-hint]');
        const suggestUrl = form.dataset.suggestUrl;
        if (!input || !panel || !suggestUrl) return;

        let timer = 0;
        let activeIndex = -1;
        let items = [];
        let lastController = null;

        const setExpanded = (open) => {
            input.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (!open) {
                panel.hidden = true;
                activeIndex = -1;
                input.removeAttribute('aria-activedescendant');
            }
        };

        const highlight = () => {
            panel.querySelectorAll('[data-suggest-index]').forEach((el) => {
                const idx = Number(el.dataset.suggestIndex);
                el.classList.toggle('is-active', idx === activeIndex);
            });
            if (activeIndex >= 0) {
                input.setAttribute('aria-activedescendant', `suggest-option-${activeIndex}`);
            } else {
                input.removeAttribute('aria-activedescendant');
            }
        };

        const fetchSuggestions = async (q) => {
            if (lastController) lastController.abort();
            lastController = new AbortController();
            try {
                const url = new URL(suggestUrl, window.location.origin);
                url.searchParams.set('q', q);
                const res = await fetch(url.toString(), {
                    headers: { Accept: 'application/json' },
                    signal: lastController.signal,
                });
                if (!res.ok) return;
                const data = await res.json();
                items = renderSuggestions(panel, data);
                activeIndex = -1;
                setExpanded(true);
                highlight();
                if (hint) {
                    hint.hidden = Boolean(data.q) || items.length > 0;
                }
            } catch (error) {
                if (error?.name !== 'AbortError') {
                    setExpanded(false);
                }
            }
        };

        const schedule = () => {
            window.clearTimeout(timer);
            timer = window.setTimeout(() => {
                fetchSuggestions(String(input.value || '').trim());
            }, 180);
        };

        input.addEventListener('input', schedule);
        input.addEventListener('focus', () => {
            if (panel.hidden) schedule();
        });

        input.addEventListener('keydown', (event) => {
            if (panel.hidden || !items.length) return;

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                activeIndex = (activeIndex + 1) % items.length;
                highlight();
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                activeIndex = activeIndex <= 0 ? items.length - 1 : activeIndex - 1;
                highlight();
            } else if (event.key === 'Enter' && activeIndex >= 0) {
                event.preventDefault();
                const link = panel.querySelector(`[data-suggest-index="${activeIndex}"]`);
                if (link?.href) window.location.href = link.href;
            } else if (event.key === 'Escape') {
                setExpanded(false);
            }
        });

        document.addEventListener('click', (event) => {
            if (!form.contains(event.target)) setExpanded(false);
        });
    }

    document.querySelectorAll('[data-search-form][data-suggest-url]').forEach(initSearchSuggest);

    document.querySelector('[data-open-search]')?.addEventListener('click', () => {
        if (!searchDialog || typeof searchDialog.showModal !== 'function') return;
        searchDialog.showModal();
        window.setTimeout(() => {
            const input = searchDialog.querySelector('[data-search-input]');
            input?.focus();
            input?.dispatchEvent(new Event('focus'));
        }, 30);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeDrawer();
            document.querySelectorAll('.store-nav__item.is-open').forEach((el) => el.classList.remove('is-open'));
        }
    });

    window.addEventListener('scroll', () => {
        header?.classList.toggle('is-scrolled', window.scrollY > 8);
    }, { passive: true });

    async function submitOnlineOrder(event) {
        event.preventDefault();
        if (!onlineCheckoutForm || !cart.length) return;
        if (!['online', 'both'].includes(checkoutMode)) return;

        const formData = new FormData(onlineCheckoutForm);
        const payload = {
            customer_name: String(formData.get('customer_name') || '').trim(),
            customer_phone: String(formData.get('customer_phone') || '').trim(),
            governorate: String(formData.get('governorate') || '').trim(),
            address: String(formData.get('address') || '').trim(),
            notes: String(formData.get('notes') || '').trim() || null,
            items: cart.map((item) => ({
                name: item.variantLabel ? `${item.name} (${item.variantLabel})` : item.name,
                price: item.price,
                quantity: item.quantity,
                image: item.image || null,
            })),
        };

        if (checkoutErrorEl) {
            checkoutErrorEl.hidden = true;
            checkoutErrorEl.textContent = '';
        }

        if (onlineSubmitBtn) {
            onlineSubmitBtn.disabled = true;
            onlineSubmitBtn.textContent = 'جاري إرسال الطلب...';
        }

        try {
            const response = await fetch(ordersUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data.success) {
                const message = data.message
                    || (data.errors ? Object.values(data.errors).flat()[0] : null)
                    || 'تعذر إرسال الطلب. حاولِ مرة أخرى.';
                throw new Error(message);
            }

            cart = [];
            persist();
            window.location.href = data.thank_you_url || '/';
        } catch (error) {
            if (checkoutErrorEl) {
                checkoutErrorEl.textContent = error.message || 'حدث خطأ غير متوقع.';
                checkoutErrorEl.hidden = false;
            } else {
                alert(error.message || 'حدث خطأ غير متوقع.');
            }
        } finally {
            if (onlineSubmitBtn) {
                onlineSubmitBtn.disabled = false;
                onlineSubmitBtn.textContent = 'تأكيد الطلب';
            }
        }
    }

    onlineCheckoutForm?.addEventListener('submit', submitOnlineOrder);

    const scrollTopBtn = document.querySelector('[data-scroll-top]');
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 250) {
                scrollTopBtn.classList.add('is-visible');
            } else {
                scrollTopBtn.classList.remove('is-visible');
            }
        }, { passive: true });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    initVariantPicker();
    persist();
}

document.addEventListener('DOMContentLoaded', createStore);
