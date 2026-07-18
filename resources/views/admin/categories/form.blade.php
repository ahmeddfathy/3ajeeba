@extends('layouts.admin')

@section('title', $category->exists ? 'تعديل فئة' : 'إضافة فئة')
@section('page-title', $category->exists ? 'تعديل فئة' : 'إضافة فئة')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المتجر</p>
            <h1 class="ad-page__title">{{ $category->exists ? 'تعديل الفئة' : 'فئة جديدة' }}</h1>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="ad-btn ad-btn--ghost">رجوع</a>
    </div>

    <form method="POST" enctype="multipart/form-data" class="ad-card ad-form"
          action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div class="ad-form__section">
            <div class="ad-grid ad-grid--2">
                <div class="ad-field ad-field--span2">
                    <label for="name">اسم الفئة *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                    @error('name') <span class="ad-error">{{ $message }}</span> @enderror
                </div>
                <div class="ad-field">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" placeholder="abayas">
                </div>
                <div class="ad-field">
                    <label for="sort_order">الترتيب</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                </div>
                <div class="ad-field ad-field--span2">
                    <label for="description">وصف قصير</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $category->description) }}">
                </div>
                <div class="ad-field ad-field--span2">
                    <label for="image">صورة الفئة</label>
                    @if($category->image_url)
                        <div class="ad-thumb-preview"><img src="{{ $category->image_url }}" alt=""></div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*">
                </div>
            </div>
        </div>

        <div class="ad-form__footer">
            <label class="ad-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                <span>ظاهرة في الموقع</span>
            </label>
            <button class="ad-btn ad-btn--primary">حفظ</button>
        </div>
    </form>
</div>
@endsection
