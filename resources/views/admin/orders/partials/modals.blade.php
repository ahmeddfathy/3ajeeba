{{-- ORDER DRAWER OVERLAY --}}
<div class="order-drawer-overlay" id="drawerOverlay" onclick="closeDrawer(event)">
    <div class="order-drawer" id="orderDrawer">
        <div class="drawer-header">
            <h3><i class="bi bi-bag-fill"></i> <span id="drawerTitle">تفاصيل الطلب</span></h3>
            <button class="drawer-close" onclick="closeDrawer()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="drawer-body" id="drawerBody">
            <div class="drawer-loading">
                <div class="spinner"></div>
                <span>جاري التحميل...</span>
            </div>
        </div>
    </div>
</div>
