@extends('layouts.admin')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/orders-dashboard.css') }}?t={{ time() }}">
@endpush

@section('content')

<!-- WELCOME BANNER -->
<div class="welcome-banner">
    <div class="welcome-banner-content">
        <div>
            <h4 class="welcome-title">أهلاً بك، {{ auth()->user()?->name }} 👋</h4>
            <p class="welcome-subtitle">إليك ملخص سريع لحالة الطلبات اليوم في عجيبة</p>
        </div>
    </div>
    <div class="welcome-date">
        <i class="bi bi-calendar3"></i>
        {{ now()->format('Y/m/d') }}
    </div>
</div>

<!-- STATS GRID -->
@include('admin.orders.partials.stats')

<!-- FILTERS -->
@include('admin.orders.partials.filters')

<!-- VIEW SWITCHER -->
<div class="view-switcher">
    <button class="view-btn active" id="btnTable" onclick="switchView('table')">
        <i class="bi bi-list-ul"></i> جدول
    </button>
    <button class="view-btn" id="btnKanban" onclick="switchView('kanban')">
        <i class="bi bi-kanban-fill"></i> كانبان
    </button>
</div>

<!-- TABLE VIEW -->
@include('admin.orders.partials.orders-table')

<!-- KANBAN VIEW -->
@include('admin.orders.partials.kanban')

<!-- MODALS & DRAWER -->
@include('admin.orders.partials.modals')

@endsection

@push('scripts')
    @include('admin.orders.partials.scripts')
@endpush
