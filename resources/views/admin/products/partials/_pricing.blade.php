{{-- Card 2: Pricing --}}
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
