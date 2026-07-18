@extends('layouts.admin')

@section('title', $blog->exists ? 'تعديل مقال' : 'مقال جديد')
@section('page-title', $blog->exists ? 'تعديل مقال' : 'إضافة مقال جديد')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المدونة</p>
            <h1 class="ad-page__title">{{ $blog->exists ? 'تعديل المقال' : 'مقال جديد' }}</h1>
            <p class="ad-page__desc">اكتب محتوى منسّق يظهر مباشرة في صفحة المدونة.</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="ad-btn ad-btn--ghost">
            <i class="bi bi-arrow-right"></i> رجوع للقائمة
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data" class="ad-card ad-form"
          action="{{ $blog->exists ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
          id="blogForm">
        @csrf
        @if($blog->exists) @method('PUT') @endif

        <div class="ad-form__section">
            <h2 class="ad-form__section-title"><i class="bi bi-type"></i> العنوان والتصنيف</h2>
            <div class="ad-grid ad-grid--2">
                <div class="ad-field ad-field--span2">
                    <label for="title">عنوان المقال *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" required>
                    @error('title') <span class="ad-error">{{ $message }}</span> @enderror
                </div>
                <div class="ad-field">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" dir="ltr" value="{{ old('slug', $blog->slug) }}" placeholder="auto">
                    <span class="ad-hint">يُولَّد تلقائياً ويدعم العربية</span>
                    @error('slug') <span class="ad-error">{{ $message }}</span> @enderror
                </div>
                <div class="ad-field">
                    <label for="blog_category_id">التصنيف</label>
                    <select name="blog_category_id" id="blog_category_id">
                        <option value="">بدون تصنيف</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('blog_category_id', $blog->blog_category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ad-field">
                    <label for="published_at">تاريخ النشر</label>
                    <input type="datetime-local" name="published_at" id="published_at"
                           value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="ad-field">
                    <label for="tags">الوسوم</label>
                    <input type="text" name="tags" id="tags"
                           value="{{ old('tags', is_array($blog->tags) ? implode(', ', $blog->tags) : '') }}"
                           placeholder="عبايات, نصائح, ستايل">
                    <span class="ad-hint">افصلي بين الوسوم بفاصلة</span>
                </div>
            </div>
        </div>

        <div class="ad-form__section">
            <h2 class="ad-form__section-title"><i class="bi bi-text-paragraph"></i> المحتوى</h2>
            <div class="ad-field">
                <label for="excerpt">ملخص قصير</label>
                <textarea name="excerpt" id="excerpt" rows="2" maxlength="500">{{ old('excerpt', $blog->excerpt) }}</textarea>
            </div>
            <div class="ad-field">
                <label for="content">محتوى المقال *</label>
                <textarea id="content" name="content" rows="12">{{ old('content', $blog->content) }}</textarea>
                <span class="ad-hint">محرر CKEditor — التنسيق يظهر في صفحة المقال</span>
                @error('content') <span class="ad-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="ad-form__section">
            <h2 class="ad-form__section-title"><i class="bi bi-image"></i> الغلاف وSEO</h2>
            <div class="ad-grid ad-grid--2">
                <div class="ad-field">
                    <label for="featured_image">صورة الغلاف</label>
                    @if($blog->featured_image_url)
                        <div class="ad-thumb-preview mb-2">
                            <img src="{{ $blog->featured_image_url }}" alt="">
                        </div>
                    @endif
                    <input type="file" name="featured_image" id="featured_image" accept="image/*">
                </div>
                <div class="ad-field">
                    <label for="featured_image_alt">نص بديل للصورة</label>
                    <input type="text" name="featured_image_alt" id="featured_image_alt" value="{{ old('featured_image_alt', $blog->featured_image_alt) }}">
                </div>
                <div class="ad-field">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $blog->meta_title) }}">
                </div>
                <div class="ad-field">
                    <label for="meta_description">Meta Description</label>
                    <input type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', $blog->meta_description) }}">
                </div>
            </div>
        </div>

        <div class="ad-form__footer">
            <label class="ad-switch">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                <span>نشر المقال على الموقع</span>
            </label>
            <div class="ad-form__actions">
                @if($blog->exists && $blog->is_published)
                    <a href="{{ route('blog.show', $blog) }}" class="ad-btn ad-btn--ghost" target="_blank">معاينة</a>
                @endif
                <button type="submit" class="ad-btn ad-btn--primary">حفظ المقال</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .ck-editor__editable_inline { min-height: 280px; direction: rtl; text-align: right; }
    .ck.ck-editor { width: 100%; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let editor = null;
        const form = document.getElementById('blogForm');

        if (window.ClassicEditor && document.querySelector('#content')) {
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', 'blockQuote', '|',
                        'undo', 'redo',
                    ],
                })
                .then((instance) => {
                    editor = instance;
                    instance.editing.view.change((writer) => {
                        writer.setAttribute('dir', 'rtl', instance.editing.view.document.getRoot());
                    });
                })
                .catch((error) => console.error(error));
        }

        form?.addEventListener('submit', () => {
            if (editor) document.querySelector('#content').value = editor.getData();
        });
    });
</script>
@endpush
