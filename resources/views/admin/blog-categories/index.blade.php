@extends('layouts.admin')

@section('title', 'تصنيفات المدونة')
@section('page-title', 'تصنيفات المدونة')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المدونة</p>
            <h1 class="ad-page__title">تصنيفات المدونة</h1>
            <p class="ad-page__desc">صنّفي المقالات: نصائح أزياء، عناية، عروض...</p>
        </div>
        <a href="{{ route('admin.blog-categories.create') }}" class="ad-btn ad-btn--primary">
            <i class="bi bi-plus-lg"></i> إضافة تصنيف
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('admin.partials.stats-cards', ['stats' => $stats])

    <div class="ad-card ad-table-wrap">
        <table class="ad-table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الرابط</th>
                    <th>المقالات</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="ad-title-cell">{{ $category->name }}</td>
                        <td><span class="ad-code">{{ $category->slug }}</span></td>
                        <td>{{ $category->blogs_count }}</td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <span class="ad-badge {{ $category->is_active ? 'ad-badge--success' : 'ad-badge--muted' }}">
                                {{ $category->is_active ? 'ظاهر' : 'مخفي' }}
                            </span>
                        </td>
                        <td>
                            <div class="ad-actions">
                                <a href="{{ route('admin.blog-categories.edit', $category) }}" class="ad-btn ad-btn--ghost ad-btn--sm">تعديل</a>
                                <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('حذف التصنيف؟')">
                                    @csrf @method('DELETE')
                                    <button class="ad-btn ad-btn--danger ad-btn--sm">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">لا توجد تصنيفات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
