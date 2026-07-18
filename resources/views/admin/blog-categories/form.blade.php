@extends('layouts.admin')

@section('title', $category->exists ? 'تعديل تصنيف' : 'إضافة تصنيف')
@section('page-title', $category->exists ? 'تعديل تصنيف المدونة' : 'إضافة تصنيف للمدونة')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المدونة</p>
            <h1 class="ad-page__title">{{ $category->exists ? 'تعديل التصنيف' : 'تصنيف جديد' }}</h1>
        </div>
        <a href="{{ route('admin.blog-categories.index') }}" class="ad-btn ad-btn--ghost">رجوع</a>
    </div>

    <form method="POST" class="ad-card ad-form"
          action="{{ $category->exists ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div class="ad-form__section">
            <div class="ad-grid ad-grid--2">
                <div class="ad-field ad-field--span2">
                    <label for="name">اسم التصنيف *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                    @error('name') <span class="ad-error">{{ $message }}</span> @enderror
                </div>
                <div class="ad-field">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" placeholder="نصائح-ازياء">
                    <span class="ad-hint">اختياري — يُولَّد تلقائياً</span>
                </div>
                <div class="ad-field">
                    <label for="sort_order">الترتيب</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                </div>
                <div class="ad-field ad-field--span2">
                    <label for="description">وصف قصير</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $category->description) }}">
                </div>
            </div>
        </div>

        <div class="ad-form__footer">
            <label class="ad-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                <span>ظاهر في المدونة</span>
            </label>
            <button class="ad-btn ad-btn--primary">حفظ</button>
        </div>
    </form>
</div>
@endsection
