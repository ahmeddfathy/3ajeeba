@extends('layouts.admin')

@section('title', 'سجل النشاطات')
@section('page-title', 'سجل النشاطات')

@push('styles')
<style>
.orders-card { background: var(--card); border-radius: 14px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
.orders-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.orders-table th { background: linear-gradient(135deg, var(--purple-900), var(--purple-700)); color: rgba(255,255,255,0.85); font-weight: 700; font-size: 0.76rem; padding: 14px 16px; text-align: right; }
.orders-table td { padding: 14px 16px; border-bottom: 1px solid #f0eef4; font-size: 0.88rem; vertical-align: middle; }
.order-number { font-weight: 800; color: var(--purple-600); text-decoration: none; }
.date-cell { color: #5a5370; font-size: 0.82rem; white-space: nowrap; }
.date-cell .time { color: var(--muted); font-size: 0.75rem; }
.bg-purple-100 { background-color: var(--purple-100); }
.text-purple-700 { color: var(--purple-700); }
.pagination-wrap { padding: 16px 20px; }
</style>
@endpush

@section('content')
<div class="orders-card">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">سجل نشاطات الطلبات (الزيادة، النقص، تغيير الحالة)</h5>
        <span class="badge bg-purple-100 text-purple-700 fw-bold px-3 py-2 rounded-pill">إجمالي {{ $logs->total() }} عملية</span>
    </div>
    
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>الوقت</th>
                    <th>بواسطة</th>
                    <th>الطلب</th>
                    <th>العملية</th>
                    <th>التفاصيل</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="date-cell">
                        {{ $log->created_at->format('Y/m/d') }}<br>
                        <span class="time">{{ $log->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $log->user?->name ?? 'نظام تلقائي' }}</div>
                        <div class="text-muted small">{{ $log->user?->role === 'admin' ? 'مدير' : ($log->user ? 'موديتور' : '') }}</div>
                    </td>
                    <td>
                        @if($log->order)
                            <a href="{{ route('admin.orders.dashboard', ['search' => $log->order->order_number]) }}" class="order-number">
                                {{ $log->order->order_number }}
                            </a>
                        @else
                            <span class="text-muted">محذوف</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $actionLabels = [
                                'status_change' => ['label' => 'تغيير حالة', 'bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                'quantity_increase' => ['label' => 'زيادة كمية', 'bg' => '#f0fdf4', 'color' => '#15803d'],
                                'quantity_decrease' => ['label' => 'تقليل كمية', 'bg' => '#fff7ed', 'color' => '#c2410c'],
                                'note_update' => ['label' => 'تحديث ملاحظة', 'bg' => '#f3f4f6', 'color' => '#374151'],
                            ];
                            $cfg = $actionLabels[$log->action] ?? ['label' => $log->action, 'bg' => '#f3f4f6', 'color' => '#374151'];
                        @endphp
                        <span class="badge" style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};padding:5px 10px;font-size:0.75rem">
                            {{ $cfg['label'] }}
                        </span>
                    </td>
                    <td style="max-width: 300px;">
                        <div class="text-wrap fw-600" style="font-size: 0.85rem; line-height: 1.5; color: #4b5563;">
                            {{ $log->description }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        لا توجد سجلات نشاط حالياً.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $logs->links() }}
    </div>
</div>
@endsection
