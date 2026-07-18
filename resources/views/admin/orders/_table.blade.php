<div class="orders-card" style="margin-bottom:0">
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
                @foreach($orders as $order)
                <tr onclick="openDrawer({{ $order->id }})" data-id="{{ $order->id }}">
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
