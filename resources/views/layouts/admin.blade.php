<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') — 3ajeeba Admin</title>

    <!-- Cairo Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?t={{ \App\Support\AssetVersion::t('assets/css/admin.css') }}">

    <style>
        :root {
            /* عجيبة — بني للأكتف، خلفية رمادية نظيفة */
            --purple-900: #2C241E;
            --purple-800: #4A3428;
            --purple-700: #6B4632;
            --purple-600: #A36F50;
            --purple-500: #A36F50;
            --purple-400: #C4A090;
            --purple-300: #DCC8BA;
            --purple-200: #E8E4E0;
            --purple-100: #F3F0ED;
            --purple-50:  #F7F6F4;
            --gold-500:   #A36F50;
            --gold-400:   #C4A090;
            --gold-gradient: linear-gradient(135deg, #B88468 0%, #A36F50 55%, #76503C 100%);
            --sidebar-w: 268px;
            --header-h: 68px;
            --bg: #F4F5F7;
            --card: #FFFFFF;
            --text: #2C241E;
            --muted: #6B6560;
            --border: #E6E4E1;
            --shadow: 0 1px 2px rgba(44,36,30,.04), 0 8px 24px rgba(44,36,30,.05);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        html, body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar { display: none; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ====== SIDEBAR — Clean White ====== */
        .admin-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: #ffffff;
            border-left: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .admin-sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sidebar-brand-logo {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: #fff;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(163,111,80,.16);
        }

        .sidebar-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sidebar-link i {
            width: 1.35rem;
            text-align: center;
            font-size: 1.15rem;
            line-height: 1;
            opacity: 0.92;
        }

        .sidebar-brand-text {
            color: var(--text);
            font-weight: 800;
            font-size: 1.05rem;
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            color: var(--muted);
            font-size: 0.73rem;
            font-weight: 600;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .sidebar-label {
            color: var(--muted);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 10px 10px 6px;
            margin-top: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            color: #5A4A40;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all .2s;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: var(--purple-50);
            color: var(--purple-700);
        }

        .sidebar-link.active {
            background: var(--purple-600);
            color: white;
            box-shadow: 0 4px 12px rgba(163,111,80,.25);
        }

        .sidebar-link .badge-count {
            margin-right: auto;
            background: var(--gold-gradient);
            color: white;
            font-size: 0.68rem;
            font-weight: 700;
            border-radius: 20px;
            padding: 2px 9px;
            min-width: 22px;
            text-align: center;
        }

        .sidebar-link.active .badge-count {
            background: rgba(255,255,255,.25);
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            font-size: 0.72rem;
            color: var(--muted);
            text-align: center;
        }

        /* User Profile in Sidebar */
        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--purple-100);
            color: var(--purple-700);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--muted);
        }

        .logout-btn {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 15px;
            border-left: 1px solid var(--border);
            margin-left: 5px;
        }

        .header-user-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
        }

        /* ====== PROFILE DROPDOWN ====== */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 10px;
            transition: background .2s;
            border: none;
            background: transparent;
            font-family: 'Cairo', sans-serif;
        }

        .profile-dropdown-toggle:hover {
            background: var(--purple-50);
        }

        .profile-dropdown-toggle .chevron-icon {
            font-size: 0.7rem;
            color: var(--muted);
            transition: transform .2s;
        }

        .profile-dropdown.open .chevron-icon {
            transform: rotate(180deg);
        }

        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
            min-width: 200px;
            padding: 6px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all .2s ease;
            z-index: 200;
        }

        .profile-dropdown.open .profile-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-dropdown-menu a,
        .profile-dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #5a5370;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }

        .profile-dropdown-menu a:hover,
        .profile-dropdown-menu button:hover {
            background: var(--purple-50);
            color: var(--purple-700);
        }

        .profile-dropdown-menu .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 8px;
        }

        .profile-dropdown-menu .dropdown-logout:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* ====== MAIN ====== */
        .admin-main {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ====== HEADER ====== */
        .admin-header {
            background: white;
            height: var(--header-h);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
        }

        .header-title span {
            color: var(--muted);
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-btn {
            background: var(--purple-50);
            border: 1px solid var(--purple-200);
            color: var(--purple-700);
            border-radius: 10px;
            padding: 7px 14px;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .2s;
        }

        .header-btn:hover {
            background: var(--purple-100);
            color: var(--purple-800);
        }

        /* ====== CONTENT ====== */
        .admin-content {
            flex: 1;
            padding: 28px;
        }

        /* ====== ALERTS ====== */
        .mc-alert {
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mc-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        .mc-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        /* ====== MOBILE TOGGLE ====== */
        .sidebar-toggle {
            display: none;
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 1.2rem;
            color: var(--purple-700);
            cursor: pointer;
            line-height: 1;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.35);
            z-index: 999;
        }

        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .admin-main { margin-right: 0; }
            .sidebar-toggle { display: inline-flex; }

            /* Header Mobile Fix */
            .admin-header { padding: 0 15px; }
            .header-user-name { display: none; }
            .header-user { border-left: none; padding-left: 0; }
            .header-title { font-size: 0.95rem; }
            .header-title span { display: none; }
            .header-btn span { display: none; }
            .header-btn { padding: 7px 10px; }
            .header-actions { gap: 8px; }
        }
    </style>

    @stack('styles')
</head>
<body class="admin-body">

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

<!-- MAIN -->
<div class="admin-main">
    <header class="admin-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="sidebar-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open');document.getElementById('sidebarOverlay').classList.toggle('active');">
                <i class="bi bi-list"></i>
            </button>
            <div class="header-title">
                @yield('page-title', 'لوحة التحكم') <span>— عجيبة</span>
            </div>
        </div>
        <div class="header-actions">
            <div class="profile-dropdown" id="profileDropdown">
                <button class="profile-dropdown-toggle" onclick="document.getElementById('profileDropdown').classList.toggle('open')">
                    <span class="header-user-name">{{ auth()->user()?->name }}</span>
                    <div class="user-avatar" style="width: 34px; height: 34px; font-size: 1rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <i class="bi bi-chevron-down chevron-icon"></i>
                </button>
                <div class="profile-dropdown-menu">
                    <a href="/user/profile">
                        <i class="bi bi-person-gear"></i>
                        الملف الشخصي
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-content">
        @if(session('success'))
            <div class="mc-alert mc-alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mc-alert mc-alert-error">
                <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Close profile dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dd = document.getElementById('profileDropdown');
        if (dd && !dd.contains(e.target)) {
            dd.classList.remove('open');
        }
    });
</script>
@stack('scripts')
</body>
</html>
