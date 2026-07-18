@extends('layouts.admin')

@section('title', 'إعدادات المتجر')
@section('page-title', 'إعدادات المتجر')

@section('content')
<div class="ad-page">
    <div class="ad-page__head">
        <div>
            <p class="ad-page__eyebrow">النظام</p>
            <h1 class="ad-page__title">إعدادات المتجر</h1>
            <p class="ad-page__desc">تحكّمي بطريقة إتمام الطلب ورقم واتساب الظهور في الموقع.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="ad-card ad-form">
        @csrf
        @method('PUT')

        <div class="ad-form__section">
            <h2 class="ad-form__section-title"><i class="bi bi-bag-check"></i> طريقة إتمام الطلب</h2>
            <p class="ad-hint" style="margin-bottom:14px;">
                الافتراضي لـ عجيبة: واتساب فقط. تقدري تفعّلي الطلب الأونلاين (بيانات العميل داخل الموقع) لو احتجتِه لاحقًا.
            </p>

            <div class="ad-settings-options">
                <label class="ad-settings-option {{ $checkoutMode === 'whatsapp' ? 'is-active' : '' }}">
                    <input type="radio" name="checkout_mode" value="whatsapp" {{ $checkoutMode === 'whatsapp' ? 'checked' : '' }}>
                    <span class="ad-settings-option__title">واتساب فقط</span>
                    <span class="ad-settings-option__desc">السلة بتحوّل العميل لواتساب — بدون فورم بيانات داخل الموقع.</span>
                </label>

                <label class="ad-settings-option {{ $checkoutMode === 'online' ? 'is-active' : '' }}">
                    <input type="radio" name="checkout_mode" value="online" {{ $checkoutMode === 'online' ? 'checked' : '' }}>
                    <span class="ad-settings-option__title">طلب أونلاين فقط</span>
                    <span class="ad-settings-option__desc">العميل يملأ الاسم والهاتف والعنوان، والطلب يتسجل في لوحة الإدارة.</span>
                </label>

                <label class="ad-settings-option {{ $checkoutMode === 'both' ? 'is-active' : '' }}">
                    <input type="radio" name="checkout_mode" value="both" {{ $checkoutMode === 'both' ? 'checked' : '' }}>
                    <span class="ad-settings-option__title">الاتنين معًا</span>
                    <span class="ad-settings-option__desc">يظهر زر واتساب + فورم الطلب الأونلاين في السلة.</span>
                </label>
            </div>
            @error('checkout_mode') <span class="ad-error">{{ $message }}</span> @enderror
        </div>

        <div class="ad-form__section">
            <h2 class="ad-form__section-title"><i class="bi bi-whatsapp"></i> رقم واتساب الطلبات</h2>
            <div class="ad-field" style="max-width:420px;">
                <label for="whatsapp_number">رقم واتساب (بدون +)</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" dir="ltr"
                       value="{{ old('whatsapp_number', $whatsappNumber) }}"
                       placeholder="201098926184">
                <span class="ad-hint">لو فاضي هيستخدم الرقم من ملف الإعدادات/.env</span>
                @error('whatsapp_number') <span class="ad-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="ad-form__footer">
            <span class="ad-hint">التغييرات تظهر فورًا على المتجر</span>
            <button type="submit" class="ad-btn ad-btn--primary">حفظ الإعدادات</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .ad-settings-options {
        display: grid;
        gap: 12px;
    }
    .ad-settings-option {
        display: grid;
        gap: 4px;
        padding: 16px 18px;
        border: 1.5px solid var(--ad-line);
        border-radius: 14px;
        background: #fff;
        cursor: pointer;
        transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .ad-settings-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .ad-settings-option.is-active,
    .ad-settings-option:has(input:checked) {
        border-color: var(--ad-brand);
        background: var(--ad-brand-soft);
        box-shadow: 0 0 0 3px var(--ad-brand-glow);
    }
    .ad-settings-option__title {
        font-weight: 800;
        color: var(--ad-ink);
    }
    .ad-settings-option__desc {
        font-size: 0.88rem;
        color: var(--ad-muted);
        font-weight: 600;
        line-height: 1.55;
    }
</style>
@endpush
