{{-- STATS GRID --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-bag-fill"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">إجمالي الطلبات</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-bell-fill"></i></div>
        <div><div class="stat-value">{{ $stats['new'] }}</div><div class="stat-label">طلبات جديدة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-check-circle-fill"></i></div>
        <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">مؤكدة</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="bi bi-box-seam-fill"></i></div>
        <div><div class="stat-value">{{ $stats['preparing'] }}</div><div class="stat-label">قيد التجهيز</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon indigo"><i class="bi bi-truck"></i></div>
        <div><div class="stat-value">{{ $stats['shipped'] }}</div><div class="stat-label">تم الشحن</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-house-check-fill"></i></div>
        <div><div class="stat-value">{{ $stats['delivered'] }}</div><div class="stat-label">مسلمة</div></div>
    </div>
    @if(auth()->user()?->role === 'admin')
    <div class="stat-card">
        <div class="stat-icon gold"><i class="bi bi-cash-stack"></i></div>
        <div><div class="stat-value" style="font-size:1.2rem">{{ number_format($stats['revenue']) }}</div><div class="stat-label">الإيرادات (ج.م)</div></div>
    </div>
    @endif
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-calendar-day"></i></div>
        <div><div class="stat-value">{{ $stats['today'] }}</div><div class="stat-label">طلبات اليوم</div></div>
    </div>
</div>
