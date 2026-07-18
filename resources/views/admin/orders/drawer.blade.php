{{-- Order Header --}}
<div style="background:linear-gradient(135deg,var(--purple-900),var(--purple-600));border-radius:14px;padding:1.1rem 1.25rem;margin-bottom:14px;color:#fff">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
        <div>
            <div style="font-size:0.72rem;opacity:.7;font-weight:600;margin-bottom:2px">رقم الطلب</div>
            <div style="font-size:1.3rem;font-weight:900">{{ $order->order_number }}</div>
            <div style="font-size:0.75rem;opacity:.7;margin-top:2px">
                <i class="bi bi-calendar3"></i> {{ $order->created_at->format('d/m/Y — H:i') }}
                @if($order->source === 'website')
                    &nbsp;<span style="background:rgba(255,255,255,.15);border-radius:10px;padding:2px 8px;font-size:.7rem">🌐 موقع</span>
                @else
                    &nbsp;<span style="background:rgba(255,255,255,.15);border-radius:10px;padding:2px 8px;font-size:.7rem">👤 {{ $order->creator?->name ?? 'إدارة' }}</span>
                @endif
            </div>
        </div>
        <div style="text-align:left">
            <span class="status-badge badge-{{ $order->status }}">
                <i class="bi {{ $order->status_icon }}"></i> {{ $order->status_label }}
            </span>
            <div style="color:var(--gold-400);font-size:1.1rem;font-weight:900;margin-top:6px" id="drawerTotal">
                {{ number_format($order->total_amount) }} ج.م
            </div>
        </div>
    </div>
</div>

{{-- Workflow --}}
@php
    $steps = [
        'new'       => ['label' => 'جديد',   'icon' => 'bi-bell-fill'],
        'confirmed' => ['label' => 'مؤكد',   'icon' => 'bi-check-circle-fill'],
        'preparing' => ['label' => 'تجهيز',  'icon' => 'bi-box-seam-fill'],
        'shipped'   => ['label' => 'شحن',    'icon' => 'bi-truck'],
        'delivered' => ['label' => 'مسلّم',   'icon' => 'bi-house-check-fill'],
    ];
    $statusOrder = array_keys($steps);
    $currentIdx  = in_array($order->status, $statusOrder) ? array_search($order->status, $statusOrder) : -1;
    $isBad = in_array($order->status, ['cancelled','returned']);
@endphp

<div class="d-card">
    <div class="d-card-header"><i class="bi bi-diagram-3-fill"></i> دورة الطلب</div>
    <div class="d-card-body" style="padding:12px 16px">
        @if($isBad)
            <div style="text-align:center;color:#dc2626;font-weight:700;padding:8px">
                <i class="bi {{ $order->status_icon }}" style="font-size:1.5rem;display:block;margin-bottom:4px"></i>
                {{ $order->status_label }}
            </div>
        @else
        <div class="workflow-mini">
            @foreach($steps as $key => $step)
                @php $idx = array_search($key, $statusOrder); @endphp
                <div class="wf-step {{ $idx < $currentIdx ? 'done' : ($idx === $currentIdx ? 'current' : '') }}">
                    <div class="wf-icon"><i class="bi {{ $step['icon'] }}"></i></div>
                    <div class="wf-label">{{ $step['label'] }}</div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Customer Info --}}
<div class="d-card">
    <div class="d-card-header"><i class="bi bi-person-fill"></i> بيانات العميل</div>
    <div class="d-card-body">
        <div class="d-info-row"><span class="d-info-label">الاسم</span><span class="d-info-value">{{ $order->customer_name }}</span></div>
        <div class="d-info-row"><span class="d-info-label">الهاتف</span><span class="d-info-value" dir="ltr">{{ $order->customer_phone }}</span></div>
        <div class="d-info-row"><span class="d-info-label">المحافظة</span><span class="d-info-value">{{ $order->governorate }}</span></div>
        <div class="d-info-row"><span class="d-info-label">العنوان</span><span class="d-info-value">{{ $order->address }}</span></div>
        @if($order->notes)
        <div class="d-info-row"><span class="d-info-label">ملاحظات</span><span class="d-info-value">{{ $order->notes }}</span></div>
        @endif
    </div>
</div>

{{-- Items with quantity controls --}}
<div class="d-card">
    <div class="d-card-header"><i class="bi bi-cart-fill"></i> المنتجات</div>
    <table class="items-mini-table" id="itemsTable">
        <thead>
            <tr><th>المنتج</th><th>الكمية</th><th>الإجمالي</th></tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr id="item-row-{{ $item->id }}">
                <td style="font-weight:600;font-size:.82rem">{{ $item->product_name }}</td>
                <td style="font-weight:800;color:var(--purple-700);text-align:center">{{ $item->quantity }}</td>
                <td style="font-weight:700;color:var(--purple-600)" id="sub-{{ $item->id }}">{{ number_format($item->subtotal) }} ج.م</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" style="padding:10px 12px">الإجمالي</td>
                <td style="padding:10px 12px;font-size:.95rem" id="totalCell">{{ number_format($order->total_amount) }} ج.م</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Quick next status --}}
@if($order->next_status)
<div class="d-card" style="border-color:var(--purple-200)">
    <div class="d-card-header"><i class="bi bi-lightning-fill"></i> إجراء سريع</div>
    <div class="d-card-body">
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $order->next_status }}">
            <button type="submit" class="btn-update">
                <i class="bi bi-arrow-right-circle-fill"></i> {{ $order->next_status_label }}
            </button>
        </form>
    </div>
</div>
@endif

{{-- Status Update --}}
<div class="d-card">
    <div class="d-card-header"><i class="bi bi-pencil-fill"></i> تعديل الحالة</div>
    <div class="d-card-body">
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display:grid;gap:10px">
            @csrf @method('PATCH')
            <select name="status" class="mc-select">
                @foreach(['new' => 'جديد', 'confirmed' => 'مؤكد', 'preparing' => 'قيد التجهيز', 'shipped' => 'تم الشحن', 'delivered' => 'تم التسليم', 'cancelled' => 'ملغي', 'returned' => 'مرتجع'] as $val => $label)
                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <textarea name="admin_notes" class="mc-textarea" placeholder="ملاحظات الإدارة...">{{ $order->admin_notes }}</textarea>
            <button type="submit" class="btn-update"><i class="bi bi-check-circle-fill"></i> حفظ التحديث</button>
        </form>
    </div>
</div>






