{{-- ORDER ITEMS --}}
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
