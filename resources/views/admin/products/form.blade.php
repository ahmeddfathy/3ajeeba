@extends('layouts.admin')

@section('title', $product->exists ? 'تعديل المنتج' : 'إضافة منتج')
@section('page-title', $product->exists ? 'تعديل المنتج' : 'إضافة منتج جديد')

@push('styles')
<style>
    .form-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 28px;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }

    .form-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--muted);
        letter-spacing: 1px;
        text-transform: uppercase;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 20px;
    }

    .field-group {
        margin-bottom: 18px;
    }

    .field-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .field-group .hint {
        font-size: 0.74rem;
        color: var(--muted);
        margin-top: 4px;
    }

    .field-group input[type="text"],
    .field-group input[type="number"],
    .field-group select,
    .field-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-family: 'Cairo', sans-serif;
        font-size: 0.9rem;
        color: var(--text);
        background: white;
        transition: border-color .2s;
        outline: none;
        resize: vertical;
    }

    .field-group input:focus,
    .field-group select:focus,
    .field-group textarea:focus {
        border-color: var(--purple-400);
    }

    .field-group input.is-invalid,
    .field-group select.is-invalid {
        border-color: #dc2626;
    }

    .invalid-feedback {
        font-size: 0.78rem;
        color: #dc2626;
        margin-top: 4px;
        font-weight: 600;
    }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .toggle-wrap input[type="checkbox"] {
        width: 40px;
        height: 22px;
        accent-color: var(--purple-600);
        cursor: pointer;
    }

    /* Image preview */
    #imgPreviewWrap {
        margin-top: 12px;
        display: none;
    }

    #imgPreviewWrap.visible {
        display: block;
    }

    #imgPreview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid var(--border);
    }

    .img-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 24px;
        cursor: pointer;
        transition: all .2s;
        color: var(--muted);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .img-upload-label:hover {
        border-color: var(--purple-400);
        background: var(--purple-50);
        color: var(--purple-700);
    }

    .img-upload-label i {
        font-size: 2rem;
    }

    input[type="file"] {
        display: none;
    }

    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 600px) {
        .two-col { grid-template-columns: 1fr; }
    }

    .btn-submit {
        background: var(--purple-600);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 12px;
        font-family: 'Cairo', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s;
    }

    .btn-submit:hover {
        background: var(--purple-700);
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: var(--purple-50);
        color: var(--purple-700);
        border: 1px solid var(--purple-200);
        padding: 12px 24px;
        border-radius: 12px;
        font-family: 'Cairo', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s;
    }

    .btn-cancel:hover {
        background: var(--purple-100);
    }

    .variant-row {
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 14px;
        background: #fafafa;
    }

    .variant-row input[type="color"] {
        width: 100%;
        height: 42px;
        padding: 4px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        background: white;
    }
</style>
@endpush

@section('content')

<div class="admin-form-shell">

    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif

        {{-- ── Card 1: Basic Info ──────────────────────────── --}}
        <div class="form-card">
            <div class="form-section-title"><i class="bi bi-info-circle"></i> البيانات الأساسية</div>

            <div class="field-group">
                <label for="name">اسم المنتج *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                       placeholder="مثال: عبوة 200 جرام"
                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="field-group">
                <label for="description">الوصف القصير</label>
                <input type="text" id="description" name="description" value="{{ old('description', $product->description) }}"
                       placeholder="مثال: لحد نص الظهر"
                       class="{{ $errors->has('description') ? 'is-invalid' : '' }}">
                <div class="hint">يظهر أسفل الاسم في كارت المنتج</div>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="field-group">
                <label for="details">تفاصيل المنتج الكاملة</label>
                <textarea id="details" name="details" rows="10">{{ old('details', $product->details) }}</textarea>
                <div class="hint">محرر CKEditor — تنسيق النص، القوائم، والروابط تظهر في صفحة المنتج</div>
                @error('details') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="field-group">
                <label for="ribbon_label">نص الـ Badge (Ribbon)</label>
                <input type="text" id="ribbon_label" name="ribbon_label" value="{{ old('ribbon_label', $product->ribbon_label) }}"
                       placeholder="مثال: عرض خاص / الأكثر طلباً / خصم 50%">
                <div class="hint">اتركه فارغاً لإخفاء الـ badge</div>
                @error('ribbon_label') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- ── Card 2: Pricing ─────────────────────────────── --}}
        <div class="form-card">
            <div class="form-section-title"><i class="bi bi-tag"></i> التسعير والخصم</div>

            <div class="two-col">
                <div class="field-group">
                    <label for="price">السعر الأساسي (ج.م)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}"
                           placeholder="اختياري لو فيه فاريانتس" min="1"
                           class="{{ $errors->has('price') ? 'is-invalid' : '' }}"
                           oninput="recalcDiscount()">
                    <div class="hint">اختياري — لو هتحطي فاريانتس، السعر بيتحدد من أقل فاريانت</div>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label for="original_price">السعر قبل الخصم (ج.م)</label>
                    <input type="number" id="original_price" name="original_price"
                           value="{{ old('original_price', $product->original_price) }}"
                           placeholder="1000" min="1"
                           oninput="recalcDiscount()">
                    <div class="hint">للمنتجات بدون فاريانتس — اتركيه فارغ لو مفيش سعر مشطوب</div>
                </div>
            </div>
            @error('variants') <div class="invalid-feedback" style="display:block;margin-bottom:12px;">{{ $message }}</div> @enderror

            {{-- حقول مخفية تُملأ تلقائياً --}}
            <input type="hidden" name="discount_type"  id="discount_type"  value="{{ old('discount_type',  $product->discount_type  ?? 'percentage') }}">
            <input type="hidden" name="discount_value" id="discount_value" value="{{ old('discount_value', $product->discount_value) }}">

            {{-- عرض الخصم المحسوب --}}
            <div id="discountBadge" style="display:none; margin-top:4px; padding:10px 14px; background:var(--purple-50); border:1.5px solid var(--purple-200); border-radius:10px; font-size:0.88rem; font-weight:700; color:var(--purple-700);">
                <i class="bi bi-arrow-down-circle-fill"></i>
                <span id="discountBadgeText"></span>
                <span style="font-size:0.75rem; font-weight:400; color:var(--muted); margin-right:6px;">(تقريبي — يُحسب من السعرين)</span>
            </div>
        </div>

        {{-- ── Card 3: Image ───────────────────────────────── --}}
        <div class="form-card">
            <div class="form-section-title"><i class="bi bi-image"></i> صورة المنتج</div>

            @if($product->exists && $product->image)
            <div style="margin-bottom:16px;">
                <p style="font-size:0.82rem;color:var(--muted);margin-bottom:8px;font-weight:600;">الصورة الحالية:</p>
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                     style="width:100px;height:100px;object-fit:cover;border-radius:12px;border:1px solid var(--border);">
            </div>
            @endif

            <label class="img-upload-label" for="image">
                <i class="bi bi-cloud-upload"></i>
                <span id="uploadText">{{ $product->exists ? 'اختر صورة جديدة (اختياري)' : 'اضغط لرفع صورة المنتج *' }}</span>
                <span style="font-size:0.75rem;">PNG, JPG, WEBP — حد أقصى 4MB</span>
            </label>
            <input type="file" id="image" name="image" accept="image/*"
                   onchange="previewImage(this)">

            <div id="imgPreviewWrap">
                <p style="font-size:0.82rem;color:var(--muted);margin:0 0 8px;font-weight:600;">معاينة:</p>
                <img id="imgPreview" src="#" alt="معاينة الصورة">
            </div>

            @error('image') <div class="invalid-feedback" style="display:block;margin-top:8px;">{{ $message }}</div> @enderror
        </div>

        {{-- ── Card: Categories & Collections ─────────────── --}}
        <div class="form-card">
            <div class="form-section-title"><i class="bi bi-grid-1x2"></i> الفئات والمجموعات</div>
            <div class="two-col">
                <div class="field-group">
                    <label>الفئات</label>
                    <div style="display:grid;gap:8px;padding:12px;border:1.5px solid var(--border);border-radius:12px;">
                        @forelse(($categories ?? []) as $category)
                            <label class="toggle-wrap" style="justify-content:flex-start;">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, old('category_ids', $product->categories->pluck('id')->all())) ? 'checked' : '' }}>
                                <span style="font-size:0.88rem;font-weight:700;">{{ $category->name }}</span>
                            </label>
                        @empty
                            <span class="hint">أضيفي فئات من قائمة الفئات أولًا</span>
                        @endforelse
                    </div>
                </div>
                <div class="field-group">
                    <label>المجموعات</label>
                    <div style="display:grid;gap:8px;padding:12px;border:1.5px solid var(--border);border-radius:12px;">
                        @forelse(($collections ?? []) as $collection)
                            <label class="toggle-wrap" style="justify-content:flex-start;">
                                <input type="checkbox" name="collection_ids[]" value="{{ $collection->id }}"
                                    {{ in_array($collection->id, old('collection_ids', $product->collections->pluck('id')->all())) ? 'checked' : '' }}>
                                <span style="font-size:0.88rem;font-weight:700;">{{ $collection->name }}</span>
                            </label>
                        @empty
                            <span class="hint">أضيفي مجموعات من قائمة المجموعات أولًا</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Card: Variants ─────────────────────────────── --}}
        <div class="form-card">
            <div class="form-section-title" style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                <span><i class="bi bi-layers"></i> الفاريانتس (مقاس / لون / سعر)</span>
                <button type="button" class="btn-cancel" id="addVariantBtn" style="padding:8px 14px;font-size:0.82rem;">
                    <i class="bi bi-plus-lg"></i> إضافة فاريانت
                </button>
            </div>
            <p class="hint" style="margin:-8px 0 16px;">كل صف = مقاس و/أو لون بسعر مستقل. تقدري تسيبي السعر الأساسي فاضي وتعتمدي على أسعار الفاريانتس فقط.</p>

            <div id="variantsList" style="display:grid;gap:12px;">
                @php
                    $oldVariants = old('variants');
                    $variantRows = is_array($oldVariants)
                        ? $oldVariants
                        : ($product->variants?->values() ?? collect());
                @endphp

                @forelse ($variantRows as $index => $variant)
                    @php
                        $v = is_array($variant) ? $variant : [
                            'id' => $variant->id,
                            'size' => $variant->size,
                            'color' => $variant->color,
                            'color_hex' => $variant->color_hex,
                            'price' => $variant->price,
                            'original_price' => $variant->original_price,
                            'sku' => $variant->sku,
                            'stock' => $variant->stock,
                            'is_active' => $variant->is_active,
                            'sort_order' => $variant->sort_order,
                        ];
                    @endphp
                    <div class="variant-row" data-variant-row>
                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $v['id'] ?? '' }}">
                        <input type="hidden" name="variants[{{ $index }}][sort_order]" value="{{ $v['sort_order'] ?? $index }}" data-sort-order>
                        <div class="two-col">
                            <div class="field-group" style="margin:0;">
                                <label>المقاس</label>
                                <input type="text" name="variants[{{ $index }}][size]" value="{{ $v['size'] ?? '' }}" placeholder="مثال: M أو 52">
                            </div>
                            <div class="field-group" style="margin:0;">
                                <label>اللون</label>
                                <input type="text" name="variants[{{ $index }}][color]" value="{{ $v['color'] ?? '' }}" placeholder="مثال: بيج">
                            </div>
                        </div>
                        <div class="two-col" style="margin-top:10px;">
                            <div class="field-group" style="margin:0;">
                                <label>كود اللون</label>
                                <input type="color" name="variants[{{ $index }}][color_hex]" value="{{ $v['color_hex'] ?? '#A36F50' }}">
                            </div>
                            <div class="field-group" style="margin:0;">
                                <label>السعر *</label>
                                <input type="number" name="variants[{{ $index }}][price]" value="{{ $v['price'] ?? '' }}" min="1" placeholder="599">
                            </div>
                        </div>
                        <div class="two-col" style="margin-top:10px;">
                            <div class="field-group" style="margin:0;">
                                <label>السعر قبل الخصم</label>
                                <input type="number" name="variants[{{ $index }}][original_price]" value="{{ $v['original_price'] ?? '' }}" min="1">
                            </div>
                            <div class="field-group" style="margin:0;">
                                <label>المخزون</label>
                                <input type="number" name="variants[{{ $index }}][stock]" value="{{ $v['stock'] ?? '' }}" min="0" placeholder="اختياري">
                            </div>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;gap:10px;">
                            <label class="toggle-wrap">
                                <input type="hidden" name="variants[{{ $index }}][is_active]" value="0">
                                <input type="checkbox" name="variants[{{ $index }}][is_active]" value="1" {{ !isset($v['is_active']) || $v['is_active'] ? 'checked' : '' }}>
                                <span style="font-size:0.84rem;font-weight:700;">نشط</span>
                            </label>
                            <button type="button" class="btn-cancel" data-remove-variant style="padding:7px 12px;font-size:0.8rem;color:#b91c1c;border-color:#fecaca;background:#fef2f2;">
                                حذف
                            </button>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>

        <template id="variantRowTemplate">
            <div class="variant-row" data-variant-row>
                <input type="hidden" name="variants[__INDEX__][id]" value="">
                <input type="hidden" name="variants[__INDEX__][sort_order]" value="__INDEX__" data-sort-order>
                <div class="two-col">
                    <div class="field-group" style="margin:0;">
                        <label>المقاس</label>
                        <input type="text" name="variants[__INDEX__][size]" placeholder="مثال: M أو 52">
                    </div>
                    <div class="field-group" style="margin:0;">
                        <label>اللون</label>
                        <input type="text" name="variants[__INDEX__][color]" placeholder="مثال: بيج">
                    </div>
                </div>
                <div class="two-col" style="margin-top:10px;">
                    <div class="field-group" style="margin:0;">
                        <label>كود اللون</label>
                        <input type="color" name="variants[__INDEX__][color_hex]" value="#A36F50">
                    </div>
                    <div class="field-group" style="margin:0;">
                        <label>السعر *</label>
                        <input type="number" name="variants[__INDEX__][price]" min="1" placeholder="599">
                    </div>
                </div>
                <div class="two-col" style="margin-top:10px;">
                    <div class="field-group" style="margin:0;">
                        <label>السعر قبل الخصم</label>
                        <input type="number" name="variants[__INDEX__][original_price]" min="1">
                    </div>
                    <div class="field-group" style="margin:0;">
                        <label>المخزون</label>
                        <input type="number" name="variants[__INDEX__][stock]" min="0" placeholder="اختياري">
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;gap:10px;">
                    <label class="toggle-wrap">
                        <input type="hidden" name="variants[__INDEX__][is_active]" value="0">
                        <input type="checkbox" name="variants[__INDEX__][is_active]" value="1" checked>
                        <span style="font-size:0.84rem;font-weight:700;">نشط</span>
                    </label>
                    <button type="button" class="btn-cancel" data-remove-variant style="padding:7px 12px;font-size:0.8rem;color:#b91c1c;border-color:#fecaca;background:#fef2f2;">
                        حذف
                    </button>
                </div>
            </div>
        </template>

        {{-- ── Card 4: Display Options ─────────────────────── --}}
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

        {{-- ── Actions ──────────────────────────────────────── --}}
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

@push('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 240px;
        direction: rtl;
        text-align: right;
    }
    .ck.ck-editor {
        width: 100%;
    }
</style>
@endpush

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

            // حفظ القيمة في الحقل المخفي
            hiddenType.value = 'percentage';
            hiddenVal.value  = pct;
        } else {
            badge.style.display = 'none';
            hiddenType.value = '';
            hiddenVal.value  = '';
        }
    }

    // تهيئة عند تحميل الصفحة
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


