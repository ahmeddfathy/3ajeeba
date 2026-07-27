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
