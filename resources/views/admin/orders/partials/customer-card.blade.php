{{-- CUSTOMER INFO --}}
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
