{{-- HEADER CARD --}}
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

{{-- WORKFLOW --}}
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
