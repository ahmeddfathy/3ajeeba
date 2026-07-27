{{-- Card 1: Basic Info --}}
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
