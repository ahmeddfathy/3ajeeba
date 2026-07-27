{{-- FILTERS --}}
<form action="{{ route('admin.orders.dashboard') }}" method="GET" class="filters-bar">
    <div class="filter-group" style="max-width:260px">
        <label class="filter-label">البحث</label>
        <div class="filter-search-wrap">
            <input type="text" name="search" placeholder="اسم / هاتف / رقم الطلب" value="{{ request('search') }}" onchange="this.form.submit()">
        </div>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label class="filter-label">الحالة</label>
        <div class="custom-select-wrap">
            <select name="status" onchange="this.form.submit()">
                <option value="">كل الحالات</option>
                @foreach(['new' => 'جديد', 'confirmed' => 'مؤكد', 'preparing' => 'قيد التجهيز', 'shipped' => 'تم الشحن', 'delivered' => 'مسلم', 'cancelled' => 'ملغي', 'returned' => 'مرتجع'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>
    <div class="filter-group" style="max-width:160px">
        <label class="filter-label">المحافظة</label>
        <div class="custom-select-wrap">
            <select name="governorate" onchange="this.form.submit()">
                <option value="">كل المحافظات</option>
                @foreach($governorates as $gov)
                    <option value="{{ $gov }}" {{ request('governorate') === $gov ? 'selected' : '' }}>{{ $gov }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>
    <div class="filter-group" style="max-width:140px">
        <label class="filter-label">الشهر</label>
        <div class="custom-select-wrap">
            <select name="month" onchange="this.form.submit()">
                <option value="">كل الشهور</option>
                @foreach(['1'=>'يناير','2'=>'فبراير','3'=>'مارس','4'=>'أبريل','5'=>'مايو','6'=>'يونيو','7'=>'يوليو','8'=>'أغسطس','9'=>'سبتمبر','10'=>'أكتوبر','11'=>'نوفمبر','12'=>'ديسمبر'] as $num => $name)
                    <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>
    <div class="filter-group" style="max-width:110px">
        <label class="filter-label">السنة</label>
        <div class="custom-select-wrap">
            <select name="year" onchange="this.form.submit()">
                <option value="">كل السنوات</option>
                @foreach(range(date('Y'), 2024) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down select-arrow"></i>
        </div>
    </div>

    @if(request()->hasAny(['search', 'status', 'governorate', 'month', 'year']))
        <a href="{{ route('admin.orders.dashboard') }}" class="btn-reset"><i class="bi bi-x-lg"></i> مسح</a>
    @endif
</form>
