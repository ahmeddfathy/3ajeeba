{{-- TABLE VIEW --}}
<div id="tableView">

@php
    $arabicMonths = ['1'=>'يناير','2'=>'فبراير','3'=>'مارس','4'=>'أبريل','5'=>'مايو','6'=>'يونيو','7'=>'يوليو','8'=>'أغسطس','9'=>'سبتمبر','10'=>'أكتوبر','11'=>'نوفمبر','12'=>'ديسمبر'];
    $arabicDays   = ['Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة','Saturday'=>'السبت'];
    
    $renderStats = function($orders) {
        $statsCfg = [
            'new'       => ['label' => 'جديد',   'icon' => 'bi-bell'],
            'confirmed' => ['label' => 'مؤكد',   'icon' => 'bi-check-circle'],
            'preparing' => ['label' => 'تجهيز',  'icon' => 'bi-box-seam'],
            'shipped'   => ['label' => 'شحن',    'icon' => 'bi-truck'],
            'delivered' => ['label' => 'مسلم',   'icon' => 'bi-house-check'],
            'cancelled' => ['label' => 'ملغي',   'icon' => 'bi-x-circle'],
            'returned'  => ['label' => 'مرتجع',  'icon' => 'bi-arrow-return-left'],
        ];
        $counts = $orders->groupBy('status')->map->count();
        $html = '<div class="group-stats">';
        foreach($statsCfg as $st => $cfg) {
            if($count = $counts->get($st)) {
                $html .= "<span class='stat-pill'><i class='bi {$cfg['icon']}'></i> {$cfg['label']} {$count}</span>";
            }
        }
        $html .= '</div>';
        return $html;
    };
@endphp

@if($allOrders->isNotEmpty())
<div class="orders-card" style="margin-bottom:20px">
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المحافظة</th>
                    <th>المنتجات</th>
                    <th>الإجمالي</th>
                    <th>الحالة</th>
                    <th>الوقت</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $lastMonth = null;
                    $lastDay = null;
                @endphp

                @foreach($allOrders as $order)
                    @php
                        $orderMonth = $order->created_at->format('Y-m');
                        $orderDay = $order->created_at->toDateString();
                    @endphp

                    @if($orderMonth !== $lastMonth)
                        @php
                            $lastMonth = $orderMonth;
                            $lastDay = null;
                            $monthCarbon = \Carbon\Carbon::parse($orderMonth . '-01');
                            $monthName = $arabicMonths[$monthCarbon->format('n')] . ' ' . $monthCarbon->format('Y');
                            $monthCount = $allOrders->filter(fn($o) => $o->created_at->format('Y-m') === $orderMonth)->count();
                        @endphp
                        <tr class="table-group-month-row" data-month="{{ $orderMonth }}" onclick="toggleTableMonth('{{ $orderMonth }}')">
                            <td colspan="8" class="table-group-month-cell">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-calendar-month"></i> {{ $monthName }}</span>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <span class="badge bg-white text-purple-700 px-3 py-1 rounded-pill" style="color: var(--purple-700); font-weight: 700; font-size: 0.8rem;">
                                            {{ $monthCount }} طلب
                                        </span>
                                        <i class="bi bi-chevron-down group-chevron" style="font-size:.85rem;transition:transform .25s"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    @if($orderDay !== $lastDay)
                        @php
                            $lastDay = $orderDay;
                            $dayCarbon = \Carbon\Carbon::parse($orderDay);
                            $dayName = $arabicDays[$dayCarbon->format('l')] . ' ' . $dayCarbon->format('d/m/Y');
                            $dayCount = $allOrders->filter(fn($o) => $o->created_at->toDateString() === $orderDay)->count();
                        @endphp
                        <tr class="table-group-day-row" data-month="{{ $orderMonth }}" data-day="{{ $orderDay }}" onclick="toggleTableDay('{{ $orderDay }}')">
                            <td colspan="8" class="table-group-day-cell">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-calendar3"></i> {{ $dayName }}</span>
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <span class="badge bg-purple-100 text-purple-700 px-3 py-1 rounded-pill" style="background: var(--purple-100); color: var(--purple-700); font-weight: 700; font-size: 0.75rem;">
                                            {{ $dayCount }} طلب
                                        </span>
                                        <i class="bi bi-chevron-down group-chevron" style="font-size:.8rem;transition:transform .25s"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    <tr onclick="openDrawer({{ $order->id }})" data-id="{{ $order->id }}" data-month="{{ $orderMonth }}" data-day="{{ $orderDay }}">
                        <td><div class="order-number">{{ $order->order_number }}</div></td>
                        <td>
                            <div class="customer-name">{{ $order->customer_name }}</div>
                            <div class="customer-phone">{{ $order->customer_phone }}</div>
                        </td>
                        <td>{{ $order->governorate }}</td>
                        <td>
                            <span style="background:var(--purple-50);color:var(--purple-700);border-radius:8px;padding:4px 12px;font-size:.8rem;font-weight:700">
                                {{ $order->items->count() }} منتج
                            </span>
                        </td>
                        <td>
                            <div class="total-cell">{{ number_format($order->total_amount) }} <span class="currency">ج.م</span></div>
                        </td>
                        <td>
                            <span class="status-badge badge-{{ $order->status }}">
                                <i class="bi {{ $order->status_icon }}"></i> {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="date-cell">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="actions-cell">
                                @if($order->next_status)
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->next_status }}">
                                    <button type="submit" class="action-btn advance-btn" title="{{ $order->next_status_label }}">
                                        <i class="bi bi-arrow-right-circle-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="orders-card">
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-bag-x"></i></div>
        <div class="empty-title">لا توجد طلبات</div>
        <p>لم يتم العثور على أي طلبات بالفلاتر المحددة.</p>
    </div>
</div>
@endif

</div>{{-- end #tableView --}}
