@extends('layouts.admin')

@section('title', 'المجموعات')
@section('page-title', 'إدارة المجموعات')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">المتجر</p>
            <h1 class="ad-page__title">المجموعات</h1>
            <p class="ad-page__desc">رمضان، الربيع، المناسبات...</p>
        </div>
        <a href="{{ route('admin.collections.create') }}" class="ad-btn ad-btn--primary">
            <i class="bi bi-plus-lg"></i> إضافة مجموعة
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
                @forelse($collections as $collection)
                    <tr>
                        <td style="width:70px;">
                            @if($collection->image_url)
                                <img src="{{ $collection->image_url }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                            @else
                                <div style="width:48px;height:48px;border-radius:10px;background:var(--ad-surface-3);"></div>
                            @endif
                        </td>
                        <td class="ad-title-cell">{{ $collection->name }}</td>
                        <td><span class="ad-code">{{ $collection->slug }}</span></td>
                        <td>{{ $collection->sort_order }}</td>
                        <td>
                            <span class="ad-badge {{ $collection->is_active ? 'ad-badge--success' : 'ad-badge--muted' }}">
                                {{ $collection->is_active ? 'نشطة' : 'مخفية' }}
                            </span>
                        </td>
                        <td>
                            <div class="ad-actions">
                                <a href="{{ route('admin.collections.edit', $collection) }}" class="ad-btn ad-btn--ghost ad-btn--sm">تعديل</a>
                                <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" onsubmit="return confirm('حذف المجموعة؟')">
                                    @csrf @method('DELETE')
                                    <button class="ad-btn ad-btn--danger ad-btn--sm">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ad-empty">لا توجد مجموعات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
