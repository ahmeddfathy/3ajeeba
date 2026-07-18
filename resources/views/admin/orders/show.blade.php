@extends('layouts.admin')

@section('title', 'تفاصيل الطلب ' . $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@push('styles')
<style>
.order-header-card {
    background: linear-gradient(135deg, var(--purple-900) 0%, var(--purple-700) 100%);
    border-radius: 20px;
    padding: 28px;
    color: white;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}

.order-header-card::before {
    content: '';
    position: absolute;
    top: -40px;
    left: -40px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,.05);
    border-radius: 50%;
}

.order-num {
    font-size: 2rem;
    font-weight: 900;
    letter-spacing: 1px;
}

.order-date-badge {
    background: rgba(255,255,255,.15);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-block;
    margin-top: 8px;
}

/* ====== WORKFLOW STEPS ====== */
.workflow {
    display: flex;
    align-items: center;
    gap: 0;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding-bottom: 8px;
}

.workflow-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex: 1;
    min-width: 80px;
    position: relative;
}

.workflow-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--border);
    z-index: 0;
}

.workflow-step.done:not(:last-child)::after {
    background: var(--purple-500);
}

.workflow-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg);
    border: 2px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: var(--muted);
    position: relative;
    z-index: 1;
    transition: all .3s;
}

.workflow-step.done .workflow-icon {
    background: var(--purple-600);
    border-color: var(--purple-600);
    color: white;
}

.workflow-step.current .workflow-icon {
    background: white;
    border-color: var(--purple-500);
    color: var(--purple-600);
    box-shadow: 0 0 0 4px rgba(124,77,204,.2);
    animation: pulse-ring 2s infinite;
}

.workflow-step.cancelled .workflow-icon,
.workflow-step.returned .workflow-icon {
    background: #fef2f2;
    border-color: #fca5a5;
    color: #dc2626;
}

@keyframes pulse-ring {
    0%, 100% { box-shadow: 0 0 0 4px rgba(124,77,204,.2); }
    50%       { box-shadow: 0 0 0 8px rgba(124,77,204,.1); }
}

.workflow-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--muted);
    text-align: center;
    white-space: nowrap;
}

.workflow-step.done .workflow-label { color: var(--purple-600); }
.workflow-step.current .workflow-label { color: var(--purple-700); font-weight: 800; }

.workflow-time {
    font-size: 0.65rem;
    color: var(--muted);
    text-align: center;
}

/* ====== DETAIL CARDS ====== */
.detail-card {
    background: var(--card);
    border-radius: 16px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}

.detail-card-header {
    background: var(--purple-50);
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    color: var(--purple-700);
    font-size: 0.92rem;
}

.detail-card-body { padding: 20px; }

.info-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}

.info-row:last-child { border-bottom: none; }

.info-label {
    color: var(--muted);
    font-size: 0.82rem;
    font-weight: 600;
    min-width: 110px;
    flex-shrink: 0;
}

.info-value {
    font-weight: 600;
    font-size: 0.9rem;
}

/* ====== ITEMS TABLE ====== */
.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th {
    background: var(--purple-50);
    color: var(--purple-700);
    font-size: 0.8rem;
    font-weight: 700;
    padding: 10px 16px;
    text-align: right;
}

.items-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    font-size: 0.88rem;
}

.items-table tr:last-child td { border-bottom: none; }

.item-img {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid var(--border);
}

.order-total-row {
    background: var(--purple-50);
    font-weight: 800;
    font-size: 1rem;
    color: var(--purple-700);
}

/* ====== STATUS UPDATE FORM ====== */
.status-form {
    display: grid;
    gap: 14px;
}

.mc-select {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    font-family: 'Cairo', sans-serif;
    font-size: 0.9rem;
    color: var(--text);
    background: var(--bg);
    transition: border-color .2s;
}

.mc-select:focus { outline: none; border-color: var(--purple-400); background: white; }

.mc-textarea {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    font-family: 'Cairo', sans-serif;
    font-size: 0.88rem;
    color: var(--text);
    background: var(--bg);
    resize: vertical;
    min-height: 80px;
    transition: border-color .2s;
}

.mc-textarea:focus { outline: none; border-color: var(--purple-400); background: white; }

.btn-update {
    background: var(--gold-gradient);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 11px 24px;
    font-family: 'Cairo', sans-serif;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    width: 100%;
    justify-content: center;
    transition: opacity .2s;
}

.btn-update:hover { opacity: .9; }

/* ====== QUICK NEXT STATUS ====== */
.next-status-box {
    background: linear-gradient(135deg, var(--purple-50) 0%, white 100%);
    border: 1.5px solid var(--purple-200);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.next-status-icon {
    width: 44px;
    height: 44px;
    background: var(--purple-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.btn-next-status {
    background: var(--purple-600);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 9px 20px;
    font-family: 'Cairo', sans-serif;
    font-weight: 700;
    font-size: 0.88rem;
    cursor: pointer;
    transition: background .2s;
    margin-right: auto;
    white-space: nowrap;
}

.btn-next-status:hover { background: var(--purple-700); }
</style>
@endpush

@section('content')

<!-- BACK BUTTON -->
<a href="{{ route('admin.orders.dashboard') }}" style="display:inline-flex;align-items:center;gap:7px;color:var(--muted);font-weight:600;font-size:.88rem;text-decoration:none;margin-bottom:20px;transition:color .2s" onmouseover="this.style.color='var(--purple-600)'" onmouseout="this.style.color='var(--muted)'">
    <i class="bi bi-arrow-right-circle"></i> العودة للطلبات
</a>

<!-- HEADER CARD -->
<div class="order-header-card">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div style="font-size:.82rem;opacity:.7;margin-bottom:4px;font-weight:600;">رقم الطلب</div>
            <div class="order-num">{{ $order->order_number }}</div>
            <div class="order-date-badge">
                <i class="bi bi-calendar3"></i>
                {{ $order->created_at->format('d/m/Y — H:i') }}
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <span class="status-badge badge-{{ $order->status }}" style="font-size:.95rem;padding:8px 20px;">
                <i class="bi {{ $order->status_icon }}"></i>
                {{ $order->status_label }}
            </span>
            <div style="margin-top:12px;color:rgba(255,255,255,.7);font-size:.82rem;font-weight:600;">
                إجمالي الطلب:
                <span style="color:var(--gold-500);font-size:1.3rem;font-weight:900;">
                    {{ number_format($order->total_amount) }} ج.م
                </span>
            </div>
        </div>
    </div>
</div>

<!-- WORKFLOW -->
<div class="detail-card" style="margin-bottom:24px">
    <div class="detail-card-header">
        <i class="bi bi-diagram-3-fill"></i> دورة حياة الطلب
    </div>
    <div class="detail-card-body">
        @php
            $steps = [
                'new'       => ['label' => 'جديد',       'icon' => 'bi-bell-fill',         'time' => $order->created_at],
                'confirmed' => ['label' => 'مؤكد',       'icon' => 'bi-check-circle-fill', 'time' => $order->confirmed_at],
                'preparing' => ['label' => 'قيد التجهيز','icon' => 'bi-box-seam-fill',     'time' => null],
                'shipped'   => ['label' => 'تم الشحن',   'icon' => 'bi-truck',              'time' => $order->shipped_at],
                'delivered' => ['label' => 'مسلّم',       'icon' => 'bi-house-check-fill',  'time' => $order->delivered_at],
            ];
            $statusOrder = array_keys($steps);
            $currentIdx  = in_array($order->status, $statusOrder) ? array_search($order->status, $statusOrder) : -1;
            $isCancelledOrReturned = in_array($order->status, ['cancelled', 'returned']);
        @endphp

        @if($isCancelledOrReturned)
            <div style="text-align:center;padding:16px;color:#dc2626;font-weight:700;">
                <i class="bi {{ $order->status_icon }}" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
                الطلب {{ $order->status_label }}
            </div>
        @else
        <div class="workflow">
            @foreach($steps as $key => $step)
                @php
                    $stepIdx = array_search($key, $statusOrder);
                    $isDone    = $stepIdx < $currentIdx;
                    $isCurrent = $stepIdx === $currentIdx;
                    $cls = $isDone ? 'done' : ($isCurrent ? 'current' : '');
                @endphp
                <div class="workflow-step {{ $cls }}">
                    <div class="workflow-icon">
                        <i class="bi {{ $step['icon'] }}"></i>
                    </div>
                    <div class="workflow-label">{{ $step['label'] }}</div>
                    @if($step['time'] && ($isDone || $isCurrent))
                        <div class="workflow-time">{{ $step['time']->format('d/m H:i') }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- LEFT COLUMN -->
    <div class="col-lg-8">
        <!-- CUSTOMER INFO -->
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="bi bi-person-fill"></i> بيانات العميل
            </div>
            <div class="detail-card-body">
                <div class="info-row">
                    <span class="info-label">الاسم</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الهاتف</span>
                    <span class="info-value" dir="ltr">{{ $order->customer_phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المحافظة</span>
                    <span class="info-value">{{ $order->governorate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">العنوان</span>
                    <span class="info-value">{{ $order->address }}</span>
                </div>
                @if($order->notes)
                <div class="info-row">
                    <span class="info-label">ملاحظات العميل</span>
                    <span class="info-value">{{ $order->notes }}</span>
                </div>
                @endif
                @if($order->admin_notes)
                <div class="info-row">
                    <span class="info-label">ملاحظات الإدارة</span>
                    <span class="info-value" style="color:var(--purple-600)">{{ $order->admin_notes }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- ORDER ITEMS -->
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="bi bi-cart-fill"></i> المنتجات المطلوبة
            </div>
            <div class="table-responsive">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="item-img" onerror="this.style.display='none'">
                                    @else
                                        <div style="width:44px;height:44px;border-radius:10px;background:var(--purple-50);display:flex;align-items:center;justify-content:center;color:var(--purple-400);font-size:1.2rem;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    @endif
                                    <span style="font-weight:700">{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($item->price) }} ج.م</td>
                            <td>
                                <span style="background:var(--purple-50);color:var(--purple-700);border-radius:6px;padding:2px 10px;font-weight:700">
                                    × {{ $item->quantity }}
                                </span>
                            </td>
                            <td style="font-weight:800;color:var(--purple-600)">{{ number_format($item->subtotal) }} ج.م</td>
                        </tr>
                        @endforeach
                        <tr class="order-total-row">
                            <td colspan="3" style="text-align:left;padding:14px 16px">الإجمالي</td>
                            <td style="padding:14px 16px;font-size:1.1rem">{{ number_format($order->total_amount) }} ج.م</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4">
        <!-- NEXT STATUS QUICK ACTION -->
        @if($order->next_status)
        <div class="detail-card" style="margin-bottom:20px">
            <div class="detail-card-header">
                <i class="bi bi-lightning-fill"></i> إجراء سريع
            </div>
            <div class="detail-card-body">
                <div class="next-status-box">
                    <div class="next-status-icon">
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:800;font-size:.9rem">{{ $order->next_status_label }}</div>
                        <div style="font-size:.78rem;color:var(--muted)">الخطوة التالية في دورة الطلب</div>
                    </div>
                </div>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $order->next_status }}">
                    <button type="submit" class="btn-next-status" style="width:100%">
                        <i class="bi bi-check2-circle"></i> {{ $order->next_status_label }}
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- STATUS UPDATE -->
        <div class="detail-card">
            <div class="detail-card-header">
                <i class="bi bi-pencil-fill"></i> تعديل الحالة
            </div>
            <div class="detail-card-body">
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="status-form">
                    @csrf @method('PATCH')
                    <div>
                        <label class="filter-label" style="display:block;margin-bottom:5px">الحالة الجديدة</label>
                        <select name="status" class="mc-select">
                            @foreach(['new' => 'جديد', 'confirmed' => 'مؤكد', 'preparing' => 'قيد التجهيز', 'shipped' => 'تم الشحن', 'delivered' => 'تم التسليم', 'cancelled' => 'ملغي', 'returned' => 'مرتجع'] as $val => $label)
                                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="filter-label" style="display:block;margin-bottom:5px">ملاحظات الإدارة</label>
                        <textarea name="admin_notes" class="mc-textarea" placeholder="أضف ملاحظات للطلب...">{{ $order->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn-update">
                        <i class="bi bi-check-circle-fill"></i> حفظ التحديث
                    </button>
                </form>
            </div>
        </div>

        <!-- DANGER ZONE -->
        <div class="detail-card" style="border-color:#fecaca;">
            <div class="detail-card-header" style="background:#fef2f2;color:#b91c1c;">
                <i class="bi bi-exclamation-triangle-fill"></i> منطقة الخطر
            </div>
            <div class="detail-card-body">
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('سيتم حذف الطلب {{ $order->order_number }} نهائياً ولا يمكن التراجع. هل أنت متأكد؟')"
                        style="width:100%;background:#fef2f2;color:#b91c1c;border:1.5px solid #fecaca;border-radius:10px;padding:10px;font-family:'Cairo',sans-serif;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .2s"
                        onmouseover="this.style.background='#dc2626';this.style.color='white'"
                        onmouseout="this.style.background='#fef2f2';this.style.color='#b91c1c'">
                        <i class="bi bi-trash-fill"></i> حذف هذا الطلب
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
