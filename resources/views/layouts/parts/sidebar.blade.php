<!-- SIDEBAR OVERLAY (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="document.getElementById('adminSidebar').classList.remove('open');this.classList.remove('active');"></div>

<!-- SIDEBAR -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <img src="{{ asset('assets/brand/logo.jpeg') }}" alt="عجيبة">
        </div>
        <div>
            <div class="sidebar-brand-text">عجيبة</div>
            <div class="sidebar-brand-sub">لوحة الإدارة</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-label">الطلبات</div>
        <a href="{{ route('admin.orders.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.orders.dashboard') ? 'active' : '' }}">
            <i class="bi bi-bag-check" aria-hidden="true"></i>
            <span>إدارة الطلبات</span>
            @php $newCount = \App\Models\Order::where('status','new')->count(); @endphp
            @if($newCount > 0)
                <span class="badge-count">{{ $newCount }}</span>
            @endif
        </a>

        @if(auth()->user()?->role === 'admin')
        <div class="sidebar-label">المستخدمون</div>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <i class="bi bi-people" aria-hidden="true"></i>
            <span>إدارة المستخدمين</span>
        </a>

        <div class="sidebar-label">المتجر</div>
        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam" aria-hidden="true"></i>
            <span>إدارة المنتجات</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid" aria-hidden="true"></i>
            <span>الفئات</span>
        </a>
        <a href="{{ route('admin.collections.index') }}" class="sidebar-link {{ request()->routeIs('admin.collections.*') ? 'active' : '' }}">
            <i class="bi bi-layers" aria-hidden="true"></i>
            <span>المجموعات</span>
        </a>

        <div class="sidebar-label">المدونة</div>
        <a href="{{ route('admin.blogs.index') }}" class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text" aria-hidden="true"></i>
            <span>المقالات</span>
        </a>
        <a href="{{ route('admin.blog-categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags" aria-hidden="true"></i>
            <span>تصنيفات المدونة</span>
        </a>

        <div class="sidebar-label">النظام</div>
        <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear" aria-hidden="true"></i>
            <span>إعدادات المتجر</span>
        </a>
        @endif
    </nav>

    <div class="sidebar-user">
        <a href="/user/profile" style="display:flex;align-items:center;gap:12px;text-decoration:none;flex:1;min-width:0;">
            <div class="user-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()?->name }}</div>
                <div class="user-role">{{ auth()->user()?->role === 'admin' ? 'مدير النظام' : 'مشرف' }}</div>
            </div>
        </a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form-sidebar">
            @csrf
            <button type="submit" class="logout-btn" title="تسجيل الخروج">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>

    <div class="sidebar-footer">
        © {{ date('Y') }} 3ajeeba — v1.0
    </div>
</aside>
