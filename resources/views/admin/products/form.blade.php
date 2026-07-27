@extends('layouts.admin')

@section('title', $product->exists ? 'تعديل المنتج' : 'إضافة منتج')
@section('page-title', $product->exists ? 'تعديل المنتج' : 'إضافة منتج جديد')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/products-form.css') }}?t={{ time() }}">
@endpush

@section('content')

<div class="admin-form-shell">

    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif

        {{-- Basic Info Card --}}
        @include('admin.products.partials._basic_info')

        {{-- Pricing Card --}}
        @include('admin.products.partials._pricing')

        {{-- Media & Categories Card --}}
        @include('admin.products.partials._media')

        {{-- Variants Card --}}
        @include('admin.products.partials._variants')

        {{-- Display Options Card --}}
        <div class="form-card">
            <div class="form-section-title"><i class="bi bi-sliders"></i> خيارات العرض</div>

            <div class="two-col">
                <div class="field-group">
                    <label for="sort_order">ترتيب العرض</label>
                    <input type="number" id="sort_order" name="sort_order"
                           value="{{ old('sort_order', $product->sort_order ?? 0) }}"
                           min="0" placeholder="0">
                    <div class="hint">الرقم الأصغر يظهر أولاً</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:16px;padding-top:4px;">
                    <label class="toggle-wrap">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <span style="font-size:0.88rem;font-weight:700;">منتج مميز (Featured)</span>
                    </label>
                    <label class="toggle-wrap">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <span style="font-size:0.88rem;font-weight:700;">ظاهر في الموقع</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-circle-fill"></i>
                {{ $product->exists ? 'حفظ التعديلات' : 'إضافة المنتج' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                <i class="bi bi-arrow-right"></i> رجوع
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    // ── Image preview ────────────────────────────────────────
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('imgPreview').src = e.target.result;
                document.getElementById('imgPreviewWrap').classList.add('visible');
                document.getElementById('uploadText').textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ── Discount badge (read-only, calculated from prices) ───
    function recalcDiscount() {
        const price    = parseFloat(document.getElementById('price').value)          || 0;
        const original = parseFloat(document.getElementById('original_price').value) || 0;
        const badge    = document.getElementById('discountBadge');
        const badgeText = document.getElementById('discountBadgeText');
        const hiddenVal  = document.getElementById('discount_value');
        const hiddenType = document.getElementById('discount_type');

        if (price > 0 && original > price) {
            const pct = Math.round((1 - price / original) * 100);

            badge.style.display = 'block';
            badgeText.textContent = `خصم ${pct}%`;

            hiddenType.value = 'percentage';
            hiddenVal.value  = pct;
        } else {
            badge.style.display = 'none';
            hiddenType.value = '';
            hiddenVal.value  = '';
        }
    }

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', () => {
        recalcDiscount();

        const list = document.getElementById('variantsList');
        const template = document.getElementById('variantRowTemplate');
        const addBtn = document.getElementById('addVariantBtn');
        const form = document.querySelector('form[enctype="multipart/form-data"]');
        let detailsEditor = null;

        if (window.ClassicEditor && document.querySelector('#details')) {
            ClassicEditor
                .create(document.querySelector('#details'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', 'blockQuote', '|',
                        'undo', 'redo',
                    ],
                })
                .then((editor) => {
                    detailsEditor = editor;
                    editor.editing.view.change((writer) => {
                        writer.setAttribute('dir', 'rtl', editor.editing.view.document.getRoot());
                    });
                })
                .catch((error) => console.error(error));
        }

        form?.addEventListener('submit', () => {
            if (detailsEditor) {
                document.querySelector('#details').value = detailsEditor.getData();
            }
        });

        function reindexVariants() {
            [...list.querySelectorAll('[data-variant-row]')].forEach((row, index) => {
                row.querySelectorAll('input, select, textarea').forEach((input) => {
                    if (!input.name) return;
                    input.name = input.name.replace(/variants\[\d+\]/, `variants[${index}]`);
                });
                const sort = row.querySelector('[data-sort-order]');
                if (sort) sort.value = String(index);
            });
        }

        addBtn?.addEventListener('click', () => {
            const index = list.querySelectorAll('[data-variant-row]').length;
            const html = template.innerHTML.replaceAll('__INDEX__', String(index));
            list.insertAdjacentHTML('beforeend', html);
        });

        list?.addEventListener('click', (event) => {
            const btn = event.target.closest('[data-remove-variant]');
            if (!btn) return;
            btn.closest('[data-variant-row]')?.remove();
            reindexVariants();
        });
    });
</script>
@endpush
