{{-- Card: Variants --}}
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
