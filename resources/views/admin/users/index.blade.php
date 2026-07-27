@extends('layouts.admin')

@section('title', 'المستخدمون')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/users.css') }}?t={{ time() }}">
@endpush

@section('content')

{{-- Header --}}
<div class="page-header">
    <div class="page-header-text">
        <h1><i class="bi bi-people-fill me-2" style="color:var(--purple-500)"></i>المستخدمون</h1>
        <p>إدارة حسابات المستخدمين وصلاحياتهم</p>
    </div>
    <button onclick="openModal()" class="btn-add">
        <i class="bi bi-person-plus-fill"></i> إضافة مستخدم
    </button>
</div>

{{-- Success Banner --}}
@if (session('success'))
    <div class="alert-success-banner">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- Stats & Users Table --}}
@include('admin.users.partials.users-table')

{{-- Add User Modal --}}
@include('admin.users.partials.user-modals')

@endsection

@push('scripts')
<script>
    function openModal()  { document.getElementById('userModal').classList.add('open'); }
    function closeModal() { document.getElementById('userModal').classList.remove('open'); }

    // close on overlay click
    document.getElementById('userModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // auto-open if validation errors
    @if($errors->any())
        openModal();
    @endif

    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#usersTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endpush
