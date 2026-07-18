@extends('layouts.admin')

@section('title', 'المدونة')
@section('page-title', 'إدارة المدونة')

@section('content')
<div class="ad-page ad-page--wide">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المحتوى</p>
            <h1 class="ad-page__title">المقالات</h1>
            <p class="ad-page__desc">إدارة مقالات المدونة المنشورة على الموقع</p>
        </div>
        <div class="ad-page__actions">
            <a href="{{ route('admin.blog-categories.index') }}" class="ad-btn ad-btn--ghost">التصنيفات</a>
            <a href="{{ route('admin.blogs.create') }}" class="ad-btn ad-btn--primary">
                <i class="bi bi-plus-lg"></i> مقال جديد
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('admin.partials.stats-cards', ['stats' => $stats])

    <form method="GET" class="ad-toolbar">
        <select name="category_id" class="form-select" style="max-width:220px" onchange="this.form.submit()">
            <option value="">كل التصنيفات</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select" style="max-width:180px" onchange="this.form.submit()">
            <option value="">كل الحالات</option>
            <option value="published" @selected(request('status') === 'published')>منشور</option>
            <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
        </select>
    </form>

    <div class="ad-card ad-table-wrap">
        <table class="ad-table">
            <thead>
                <tr>
                    <th>الغلاف</th>
                    <th>العنوان</th>
                    <th>التصنيف</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td style="width:70px;">
                            @if($blog->featured_image_url)
                                <img src="{{ $blog->featured_image_url }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                            @else
                                <div style="width:48px;height:48px;border-radius:10px;background:var(--ad-surface-3);"></div>
                            @endif
                        </td>
                        <td class="ad-title-cell">{{ $blog->title }}</td>
                        <td>{{ $blog->category?->name ?? '—' }}</td>
                        <td>
                            <span class="ad-badge {{ $blog->is_published ? 'ad-badge--success' : 'ad-badge--muted' }}">
                                {{ $blog->is_published ? 'منشور' : 'مسودة' }}
                            </span>
                        </td>
                        <td>{{ optional($blog->published_at ?? $blog->created_at)->format('Y-m-d') }}</td>
                        <td>
                            <div class="ad-actions">
                                <form action="{{ route('admin.blogs.toggle-status', $blog) }}" method="POST">
                                    @csrf
                                    <button class="ad-btn ad-btn--ghost ad-btn--sm">{{ $blog->is_published ? 'إخفاء' : 'نشر' }}</button>
                                </form>
                                <a href="{{ route('admin.blogs.edit', $blog) }}" class="ad-btn ad-btn--ghost ad-btn--sm">تعديل</a>
                                <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('حذف المقال؟')">
                                    @csrf @method('DELETE')
                                    <button class="ad-btn ad-btn--danger ad-btn--sm">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">لا توجد مقالات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($blogs->hasPages())
        <div class="mt-3">{{ $blogs->links() }}</div>
    @endif
</div>
@endsection
