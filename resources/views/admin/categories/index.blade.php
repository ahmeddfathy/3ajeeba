@extends('layouts.admin')

@section('title', 'الفئات')
@section('page-title', 'إدارة الفئات')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المتجر</p>
            <h1 class="ad-page__title">فئات المنتجات</h1>
            <p class="ad-page__desc">عبايات، حجابات، خمر، إكسسوارات...</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="ad-btn ad-btn--primary">
            <i class="bi bi-plus-lg"></i> إضافة فئة
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
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>الرابط</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td style="width:70px;">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                            @else
                                <div style="width:48px;height:48px;border-radius:10px;background:var(--ad-surface-3);"></div>
                            @endif
                        </td>
                        <td class="ad-title-cell">{{ $category->name }}</td>
                        <td><span class="ad-code">{{ $category->slug }}</span></td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            <span class="ad-badge {{ $category->is_active ? 'ad-badge--success' : 'ad-badge--muted' }}">
                                {{ $category->is_active ? 'نشطة' : 'مخفية' }}
                            </span>
                        </td>
                        <td>
                            <div class="ad-actions">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="ad-btn ad-btn--ghost ad-btn--sm">تعديل</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('حذف الفئة؟')">
                                    @csrf @method('DELETE')
                                    <button class="ad-btn ad-btn--danger ad-btn--sm">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">لا توجد فئات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
