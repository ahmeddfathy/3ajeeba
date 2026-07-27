{{-- Card 3: Image --}}
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

{{-- Card: Categories & Collections --}}
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
