{{-- KANBAN VIEW --}}
<div id="kanbanView" style="display:none">
@php
    $allCols = [
        'new'       => ['label'=>'جديد',        'icon'=>'bi-bell-fill',         'color'=>'#2563eb', 'bg'=>'#eff6ff'],
        'confirmed' => ['label'=>'مؤكد',        'icon'=>'bi-check-circle-fill', 'color'=>'#7c4dcc', 'bg'=>'#f3eeff'],
        'preparing' => ['label'=>'قيد التجهيز', 'icon'=>'bi-box-seam-fill',     'color'=>'#ea580c', 'bg'=>'#fff7ed'],
        'shipped'   => ['label'=>'تم الشحن',    'icon'=>'bi-truck',              'color'=>'#4338ca', 'bg'=>'#eef2ff'],
        'delivered' => ['label'=>'مسلّم',        'icon'=>'bi-house-check-fill',  'color'=>'#16a34a', 'bg'=>'#f0fdf4'],
        'cancelled' => ['label'=>'ملغي',         'icon'=>'bi-x-circle-fill',     'color'=>'#b91c1c', 'bg'=>'#fef2f2'],
        'returned'  => ['label'=>'مرتجع',        'icon'=>'bi-arrow-return-left', 'color'=>'#6b7280', 'bg'=>'#f9fafb'],
    ];
    $chunks = array_chunk($allCols, 3, true);
    $arabicMonths = ['1'=>'يناير','2'=>'فبراير','3'=>'مارس','4'=>'أبريل','5'=>'مايو','6'=>'يونيو','7'=>'يوليو','8'=>'أغسطس','9'=>'سبتمبر','10'=>'أكتوبر','11'=>'نوفمبر','12'=>'ديسمبر'];
    $arabicDays   = ['Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة','Saturday'=>'السبت'];
@endphp

@foreach($chunks as $chunkIndex => $cols)
<div class="kanban-board kanban-row-3" style="{{ $chunkIndex > 0 ? 'margin-top:14px' : '' }}">
    @foreach($cols as $status => $col)
    @php 
        $colOrders = $allOrders->where('status', $status)->sortByDesc('created_at');
        $monthGroups = $colOrders->groupBy(fn($o) => $o->created_at->format('Y-m'));
    @endphp
    <div class="kanban-col">
        <div class="kanban-col-header">
            <div class="kanban-col-title">
                <i class="bi {{ $col['icon'] }}" style="color:{{ $col['color'] }};filter:brightness(1.8)"></i>
                {{ $col['label'] }}
            </div>
            <span class="kanban-col-count" style="background:{{ $col['bg'] }};color:{{ $col['color'] }}">
                {{ $colOrders->count() }}
            </span>
        </div>
        <div class="kanban-col-body">
            @forelse($monthGroups as $monthKey => $mOrders)
                @php
                    $mDate = \Carbon\Carbon::parse($monthKey . '-01');
                    $mId = "kb-{$status}-" . str_replace('-', '', $monthKey);
                    $dayGroups = $mOrders->groupBy(fn($o) => $o->created_at->toDateString());
                @endphp
                
                <div class="kb-month-header" onclick="toggleGroup('{{ $mId }}')">
                    <span><i class="bi bi-calendar-month"></i> {{ $arabicMonths[$mDate->format('n')] }} {{ $mDate->format('Y') }}</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="font-size:0.65rem;font-weight:700;background:var(--purple-100);padding:1px 6px;border-radius:10px">{{ $mOrders->count() }}</span>
                        <i class="bi bi-chevron-up" id="chevron-{{ $mId }}" style="transition:transform .2s"></i>
                    </div>
                </div>

                <div id="group-{{ $mId }}">
                    @foreach($dayGroups as $dayKey => $dOrders)
                        @php
                            $dDate = \Carbon\Carbon::parse($dayKey);
                            $dId = "kb-{$status}-" . str_replace('-', '', $dayKey);
                        @endphp
                        <div class="kb-day-header" onclick="toggleGroup('{{ $dId }}')">
                            <span>{{ $arabicDays[$dDate->format('l')] }} {{ $dDate->format('d/m') }}</span>
                            <div style="display:flex;align-items:center;gap:8px">
                                <span style="font-size:0.65rem">{{ $dOrders->count() }}</span>
                                <i class="bi bi-chevron-up" id="chevron-{{ $dId }}" style="transition:transform .2s"></i>
                            </div>
                        </div>

                        <div id="group-{{ $dId }}" class="kb-day-body">
                            @foreach($dOrders as $order)
                            <div class="kanban-card" onclick="openDrawer({{ $order->id }})" data-id="{{ $order->id }}">
                                <div class="kanban-card-num">{{ $order->order_number }}</div>
                                <div class="kanban-card-name">{{ $order->customer_name }}</div>
                                <div class="kanban-card-phone">{{ $order->customer_phone }}</div>
                                <div class="kanban-card-footer">
                                    <span class="kanban-card-total">{{ number_format($order->total_amount) }} ج.م</span>
                                    <span class="kanban-card-time">{{ $order->created_at->format('d/m H:i') }}</span>
                                </div>
                                @if($order->next_status)
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->next_status }}">
                                    <button type="submit" class="kanban-advance-btn">
                                        <i class="bi bi-arrow-right-circle-fill"></i> {{ $order->next_status_label }}
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="kanban-empty"><i class="bi bi-inbox"></i></div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>
@endforeach
</div>
