@extends('layouts.admin')

@section('title', 'إدارة المنتجات')
@section('page-title', 'إدارة المنتجات')

@push('styles')
<style>
    /* ── Scroll hint — موبايل فقط ── */
    .mobile-scroll-hint {
        display: none;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .mobile-scroll-hint { display: flex; }
    }

    /* ── Table wrapper ── */
    .products-table-wrap {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow-x: auto;          /* ← السحب الأفقي */
        -webkit-overflow-scrolling: touch;
        box-shadow: var(--shadow);
        /* shadow على الجانب يوضح إن في محتوى خارج الشاشة */
        background-image: linear-gradient(to right, white 30%, rgba(255,255,255,0)),
                          linear-gradient(to left,  white 30%, rgba(255,255,255,0)),
                          radial-gradient(farthest-side at 0   50%, rgba(107,61,173,.12), transparent),
                          radial-gradient(farthest-side at 100% 50%, rgba(107,61,173,.12), transparent);
        background-position: right center, left center, right center, left center;
        background-repeat: no-repeat;
        background-size: 40px 100%, 40px 100%, 14px 100%, 14px 100%;
        background-attachment: local, local, scroll, scroll;
    }

    /* ── Table ── */
    .products-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px; /* يمنع الضغط على الأعمدة */
    }

    .products-table thead tr {
        background: var(--purple-50);
        border-bottom: 1px solid var(--border);
    }

    .products-table th {
        padding: 14px 16px;
        text-align: right;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--muted);
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .product-row {
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }

    .product-row:hover { background: var(--purple-50); }
    .product-row:last-child { border-bottom: none; }

    .product-row td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    /* ── Action buttons ── */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-edit {
        background: var(--purple-50);
        border-color: var(--purple-200);
        color: var(--purple-700);
    }
    .btn-edit:hover {
        background: var(--purple-100);
        color: var(--purple-800);
    }

    .btn-delete {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }
    .btn-delete:hover {
        background-color: #dc2626;
        color: white;
        border-color: #dc2626;
    }
    .btn-sort-arrow:hover {
        background: var(--purple-600) !important;
        color: white !important;
        border-color: var(--purple-600) !important;
    }
    .sort-order-input:focus {
        border-color: var(--purple-500) !important;
        box-shadow: 0 0 0 2px rgba(124, 77, 204, 0.1);
    }
    .selected-row {
        background: #f5edff !important;
        border-right: 4px solid var(--purple-600) !important;
    }
    .btn-sort-bar-arrow:hover {
        background: var(--purple-600) !important;
        color: white !important;
        border-color: var(--purple-600) !important;
    }
    .btn-sort-bar-close {
        transition: color 0.2s;
    }
    .btn-sort-bar-close:hover {
        color: #dc2626 !important;
    }
</style>
@endpush

@section('content')
<div class="ad-page ad-page--wide">
<div class="ad-page__head">
    <div>
        <p class="ad-page__eyebrow">المتجر</p>
        <h1 class="ad-page__title">المنتجات</h1>
        <p class="ad-page__desc">{{ $products->count() }} منتج في الكتالوج</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="ad-btn ad-btn--primary">
        <i class="bi bi-plus-lg"></i> إضافة منتج
    </a>
</div>

@include('admin.partials.stats-cards', ['stats' => $stats])

@if($products->isEmpty())
    <div style="text-align:center;padding:80px 20px;color:var(--muted);">
        <i class="bi bi-box-seam" style="font-size:3rem;display:block;margin-bottom:16px;"></i>
        <p style="font-size:1rem;font-weight:600;">لا يوجد منتجات بعد</p>
        <a href="{{ route('admin.products.create') }}" class="header-btn" style="margin-top:12px;display:inline-flex;">
            <i class="bi bi-plus-lg"></i> أضف أول منتج
        </a>
    </div>
@else
    {{-- hint للموبايل فقط --}}
    <div class="mobile-scroll-hint">
        <i class="bi bi-arrows-expand" style="transform:rotate(90deg);display:inline-block;"></i>
        اسحب يميناً لرؤية باقي الأعمدة
    </div>

    <div class="products-table-wrap">
        <table class="products-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المنتج</th>
                    <th>السعر</th>
                    <th>الـ Badge</th>
                    <th style="text-align:center;">الحالة</th>
                    <th style="text-align:center;">الترتيب</th>
                    <th style="text-align:center;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                 <tr class="product-row" data-id="{{ $product->id }}">
                    <td style="color:var(--muted);font-size:0.82rem;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:1px solid var(--border);flex-shrink:0;">
                            <div>
                                <div class="product-name" style="font-weight:700;font-size:0.9rem;color:var(--text);">{{ $product->name }}</div>
                                @if($product->description)
                                    <div style="font-size:0.75rem;color:var(--muted);">{{ $product->description }}</div>
                                @endif
                                @if($product->is_featured)
                                    <span style="font-size:0.68rem;font-weight:700;background:var(--gold-gradient);color:white;padding:2px 8px;border-radius:20px;">مميز</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:1rem;font-weight:800;color:var(--purple-700);white-space:nowrap;">{{ number_format($product->display_price) }} ج.م</div>
                        @if($product->display_original_price)
                            <div style="font-size:0.78rem;color:var(--muted);text-decoration:line-through;white-space:nowrap;">{{ number_format($product->display_original_price) }} ج.م</div>
                        @endif
                        @if($product->discount_percentage)
                            <span style="font-size:0.72rem;font-weight:700;background:#dcfce7;color:#15803d;padding:2px 7px;border-radius:20px;white-space:nowrap;">خصم {{ $product->discount_percentage }}%</span>
                        @endif
                    </td>
                    <td>
                        @if($product->ribbon_label)
                            <span style="font-size:0.78rem;font-weight:700;background:var(--purple-100);color:var(--purple-700);padding:4px 10px;border-radius:20px;white-space:nowrap;">{{ $product->ribbon_label }}</span>
                        @else
                            <span style="color:var(--muted);font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($product->is_active)
                            <span style="font-size:0.78rem;font-weight:700;background:#dcfce7;color:#15803d;padding:4px 12px;border-radius:20px;white-space:nowrap;">ظاهر</span>
                        @else
                            <span style="font-size:0.78rem;font-weight:700;background:#fee2e2;color:#b91c1c;padding:4px 12px;border-radius:20px;white-space:nowrap;">مخفي</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex; align-items:center; gap:6px; justify-content:center;">
                            <input type="number" class="sort-order-input" value="{{ $product->sort_order }}" style="width: 56px; padding: 4px 6px; text-align: center; border-radius: 8px; border: 1.5px solid var(--border); font-family: Cairo; font-weight: 700; font-size: 0.85rem; outline: none; transition: border-color 0.2s;" min="0">
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                <button type="button" onclick="moveProduct(this, 'up')" class="btn-sort-arrow" title="تحريك لأعلى" style="border: 1px solid var(--purple-200); border-radius: 4px; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; color: var(--purple-700); background: var(--purple-50); cursor: pointer; font-size: 0.7rem; transition: all 0.2s;"><i class="bi bi-chevron-up"></i></button>
                                <button type="button" onclick="moveProduct(this, 'down')" class="btn-sort-arrow" title="تحريك لأسفل" style="border: 1px solid var(--purple-200); border-radius: 4px; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; color: var(--purple-700); background: var(--purple-50); cursor: pointer; font-size: 0.7rem; transition: all 0.2s;"><i class="bi bi-chevron-down"></i></button>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;flex-wrap:nowrap;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-action btn-edit">
                                <i class="bi bi-pencil-fill"></i> تعديل
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف {{ addslashes($product->name) }}؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="bi bi-trash3-fill"></i> حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
</div>

<!-- FLOATING SORT CONTROL BAR -->
<div id="sort-control-bar" style="position: fixed; bottom: -80px; left: 50%; transform: translateX(-50%); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1.5px solid var(--purple-200); border-radius: 16px; padding: 12px 24px; box-shadow: 0 10px 30px rgba(163, 111, 80, 0.25); display: flex; align-items: center; gap: 16px; z-index: 9999; transition: bottom 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);">
    <button type="button" onclick="closeSortBar()" class="btn-sort-bar-close" title="إغلاق" style="background: transparent; border: none; color: var(--muted); cursor: pointer; font-size: 1rem; padding: 0; display: inline-flex; align-items: center; justify-content: center; margin-left: 4px;"><i class="bi bi-x-lg"></i></button>
    <div style="height: 20px; width: 1px; background: var(--border);"></div>
    <span style="font-family: Cairo; font-weight: 700; font-size: 0.9rem; color: var(--text); white-space: nowrap;">
        ترتيب المنتج: <span id="sort-product-name" style="color: var(--purple-700);"></span>
    </span>
    <div style="height: 20px; width: 1px; background: var(--border);"></div>
    <button type="button" onclick="moveSelectedProduct('up')" class="btn-sort-bar-arrow" style="background: var(--purple-50); border: 1px solid var(--purple-200); color: var(--purple-700); border-radius: 10px; padding: 6px 16px; font-family: Cairo; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; white-space: nowrap;">
        <i class="bi bi-chevron-up"></i> تحريك لأعلى
    </button>
    <button type="button" onclick="moveSelectedProduct('down')" class="btn-sort-bar-arrow" style="background: var(--purple-50); border: 1px solid var(--purple-200); color: var(--purple-700); border-radius: 10px; padding: 6px 16px; font-family: Cairo; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; white-space: nowrap;">
        <i class="bi bi-chevron-down"></i> تحريك لأسفل
    </button>
</div>

@push('scripts')
<script>
let selectedRow = null;

function closeSortBar() {
    document.getElementById('sort-control-bar').style.bottom = '-80px';
    document.querySelectorAll('.product-row').forEach(r => r.classList.remove('selected-row'));
    selectedRow = null;
}

function selectRow(row) {
    document.querySelectorAll('.product-row').forEach(r => r.classList.remove('selected-row'));
    selectedRow = row;
    selectedRow.classList.add('selected-row');
    
    const nameEl = selectedRow.querySelector('.product-name');
    if (nameEl) {
        document.getElementById('sort-product-name').innerText = nameEl.innerText;
        document.getElementById('sort-control-bar').style.bottom = '30px';
    }
}

function moveProduct(btn, direction) {
    const row = btn.closest('.product-row');
    selectRow(row);
    
    if (direction === 'up') {
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('product-row')) {
            row.parentNode.insertBefore(row, prev);
            saveNewOrder();
        }
    } else if (direction === 'down') {
        const next = row.nextElementSibling;
        if (next && next.classList.contains('product-row')) {
            row.parentNode.insertBefore(next, row);
            saveNewOrder();
        }
    }
}

function moveSelectedProduct(direction) {
    if (!selectedRow) return;
    
    if (direction === 'up') {
        const prev = selectedRow.previousElementSibling;
        if (prev && prev.classList.contains('product-row')) {
            selectedRow.parentNode.insertBefore(selectedRow, prev);
            saveNewOrder();
            selectedRow.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    } else if (direction === 'down') {
        const next = selectedRow.nextElementSibling;
        if (next && next.classList.contains('product-row')) {
            selectedRow.parentNode.insertBefore(next, selectedRow);
            saveNewOrder();
            selectedRow.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }
}

function saveNewOrder() {
    const rows = document.querySelectorAll('.product-row');
    const orders = [];
    
    rows.forEach((row, index) => {
        const id = row.getAttribute('data-id');
        const sortOrder = index + 1;
        
        const input = row.querySelector('.sort-order-input');
        if (input) {
            input.value = sortOrder;
        }
        
        orders.push({ id: id, sort_order: sortOrder });
    });
    
    fetch('{{ route('admin.products.reorder') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ orders: orders })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            alert('حدث خطأ أثناء حفظ الترتيب');
        }
    })
    .catch(() => {
        alert('حدث خطأ في الاتصال بالسيرفر');
    });
}

document.querySelectorAll('.product-row').forEach(row => {
    row.addEventListener('click', function(e) {
        if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input') || e.target.closest('form')) {
            return;
        }
        selectRow(this);
    });
});

document.querySelectorAll('.sort-order-input').forEach(input => {
    input.addEventListener('change', function() {
        const tbody = document.querySelector('.products-table tbody');
        const rows = Array.from(tbody.querySelectorAll('.product-row'));
        
        rows.sort((a, b) => {
            const valA = parseInt(a.querySelector('.sort-order-input').value) || 0;
            const valB = parseInt(b.querySelector('.sort-order-input').value) || 0;
            return valA - valB;
        });
        
        rows.forEach(row => tbody.appendChild(row));
        saveNewOrder();
    });
    
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur();
        }
    });
});
</script>
@endpush
@endsection
