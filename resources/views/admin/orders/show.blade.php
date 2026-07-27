@extends('layouts.admin')

@section('title', 'تفاصيل الطلب ' . $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/orders-show.css') }}?t={{ time() }}">
@endpush

@section('content')

<!-- BACK BUTTON -->
<a href="{{ route('admin.orders.dashboard') }}" style="display:inline-flex;align-items:center;gap:7px;color:var(--muted);font-weight:600;font-size:.88rem;text-decoration:none;margin-bottom:20px;transition:color .2s" onmouseover="this.style.color='var(--purple-600)'" onmouseout="this.style.color='var(--muted)'">
    <i class="bi bi-arrow-right-circle"></i> العودة للطلبات
</a>

<!-- HEADER & WORKFLOW -->
@include('admin.orders.partials.order-header')

<div class="row g-4">
    <!-- LEFT COLUMN -->
    <div class="col-lg-8">
        <!-- CUSTOMER INFO -->
        @include('admin.orders.partials.customer-card')

        <!-- ORDER ITEMS -->
        @include('admin.orders.partials.order-items')
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
