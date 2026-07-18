@extends('layouts.admin')

@section('title', $collection->exists ? 'تعديل مجموعة' : 'إضافة مجموعة')
@section('page-title', $collection->exists ? 'تعديل مجموعة' : 'إضافة مجموعة')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المتجر</p>
            <h1 class="ad-page__title">{{ $collection->exists ? 'تعديل المجموعة' : 'مجموعة جديدة' }}</h1>
        </div>
        <a href="{{ route('admin.collections.index') }}" class="ad-btn ad-btn--ghost">رجوع</a>
    </div>

    <form method="POST" enctype="multipart/form-data" class="ad-card ad-form"
          action="{{ $collection->exists ? route('admin.collections.update', $collection) : route('admin.collections.store') }}">
        @csrf
        @if($collection->exists) @method('PUT') @endif

        <div class="ad-form__section">
            <div class="ad-grid ad-grid--2">
                <div class="ad-field ad-field--span2">
                    <label for="name">اسم المجموعة *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $collection->name) }}" required>
                </div>
                <div class="ad-field">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $collection->slug) }}" placeholder="ramadan">
                </div>
                <div class="ad-field">
                    <label for="label">Label</label>
                    <input type="text" name="label" id="label" value="{{ old('label', $collection->label ?? 'مجموعة') }}" placeholder="مجموعة">
                </div>
                <div class="ad-field ad-field--span2">
                    <label for="description">وصف قصير</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $collection->description) }}">
                </div>
                <div class="ad-field">
                    <label for="sort_order">الترتيب</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $collection->sort_order ?? 0) }}" min="0">
                </div>
                <div class="ad-field">
                    <label for="image">صورة المجموعة</label>
                    @if($collection->image_url)
                        <div class="ad-thumb-preview"><img src="{{ $collection->image_url }}" alt=""></div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*">
                </div>
            </div>
        </div>

        <div class="ad-form__footer">
            <label class="ad-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $collection->is_active ?? true) ? 'checked' : '' }}>
                <span>ظاهرة في الموقع</span>
            </label>
            <button class="ad-btn ad-btn--primary">حفظ</button>
        </div>
    </form>
</div>
@endsection
