@extends('layouts.admin')

@section('title', 'المستخدمون')

@section('content')

<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
        background: var(--card);
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        padding: 1.25rem 1.5rem;
    }

    .page-header-text h1 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--purple-900);
        margin-bottom: 0.2rem;
    }

    .page-header-text p {
        color: var(--muted);
        font-size: 0.875rem;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.4rem;
        background: var(--gold-gradient);
        color: #fff;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(224,168,32,0.35);
        transition: transform 0.2s, box-shadow 0.2s;
        white-space: nowrap;
        border: none;
        cursor: pointer;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(224,168,32,0.45);
        color: #fff;
    }

    /* Modal */
    .mc-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(30,10,60,0.45);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s;
    }

    .mc-modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .mc-modal {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        transform: translateY(20px) scale(0.97);
        transition: transform 0.25s;
        overflow: hidden;
    }

    .mc-modal-overlay.open .mc-modal {
        transform: translateY(0) scale(1);
    }

    .mc-modal-header {
        background: linear-gradient(135deg, var(--purple-900), var(--purple-600));
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .mc-modal-header h3 {
        color: #fff;
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mc-modal-close {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .mc-modal-close:hover { background: rgba(255,255,255,0.25); }

    .mc-modal-body {
        padding: 1.25rem 1.5rem;
        overflow-y: auto;
        flex: 1;
        scrollbar-width: none;
        -ms-overflow-style: none;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }

    .mc-modal-body::-webkit-scrollbar {
        display: none;
    }

    .field-group { margin-bottom: 0.75rem; }

    .field-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 0.35rem;
    }

    .field-group label .required { color: #ef4444; margin-right: 2px; }

    .input-wrap { position: relative; }

    .input-wrap .input-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--purple-400);
        font-size: 0.95rem;
        pointer-events: none;
    }

    .input-wrap input,
    .input-wrap select {
        width: 100%;
        padding: 0.55rem 2.4rem 0.55rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 11px;
        font-size: 0.875rem;
        font-family: 'Cairo', sans-serif;
        color: var(--text);
        background: var(--purple-50);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-wrap input:focus,
    .input-wrap select:focus {
        border-color: var(--purple-500);
        box-shadow: 0 0 0 3px rgba(124,77,204,0.1);
        background: #fff;
    }

    .role-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.6rem;
    }

    .role-option { position: relative; }

    .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
    }

    .role-option label {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.7rem 0.9rem;
        border: 1.5px solid var(--border);
        border-radius: 11px;
        cursor: pointer;
        background: var(--purple-50);
        transition: all 0.2s;
        margin: 0;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .role-option input[type="radio"]:checked + label {
        border-color: var(--purple-500);
        background: var(--purple-100);
        box-shadow: 0 0 0 3px rgba(124,77,204,0.1);
    }

    .role-option label i { color: var(--purple-500); font-size: 1rem; }

    .modal-actions {
        display: flex;
        gap: 0.6rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }

    .btn-save {
        flex: 1;
        padding: 0.7rem;
        background: var(--gold-gradient);
        color: #fff;
        border: none;
        border-radius: 11px;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Cairo', sans-serif;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(224,168,32,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(224,168,32,0.4); }

    .btn-cancel-modal {
        padding: 0.7rem 1.2rem;
        background: var(--purple-50);
        color: var(--purple-700);
        border: 1.5px solid var(--purple-200);
        border-radius: 11px;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel-modal:hover { background: var(--purple-100); }

    .field-error {
        font-size: 0.78rem;
        color: #ef4444;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: var(--card);
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .stat-icon.purple { background: var(--purple-100); color: var(--purple-600); }
    .stat-icon.gold   { background: #fef9ec; color: var(--gold-500); }
    .stat-icon.green  { background: #f0fdf4; color: #16a34a; }

    .stat-info strong {
        display: block;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
    }

    .stat-info span {
        font-size: 0.78rem;
        color: var(--muted);
    }

    /* Table Card */
    .table-card {
        background: var(--card);
        border-radius: 14px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .table-card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .table-card-header h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .table-card-header h2 i { color: var(--purple-500); }

    .search-wrap {
        position: relative;
    }

    .search-wrap i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 0.9rem;
    }

    .search-wrap input {
        padding: 0.5rem 2.2rem 0.5rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Cairo', sans-serif;
        color: var(--text);
        background: var(--purple-50);
        outline: none;
        width: 220px;
        transition: border-color 0.2s;
    }

    .search-wrap input:focus {
        border-color: var(--purple-400);
        background: #fff;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table thead th {
        padding: 14px 16px;
        font-size: 0.76rem;
        font-weight: 700;
        color: rgba(255,255,255,0.85);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--purple-900);
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
    }

    .users-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    .users-table tbody tr:last-child { border-bottom: none; }
    .users-table tbody tr:hover { background: var(--purple-50); }

    .users-table tbody td {
        padding: 14px 16px;
        font-size: 0.88rem;
        color: var(--text);
        vertical-align: middle;
        border-bottom: 1px solid #f0eef4;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--purple-400), var(--purple-600));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .user-info { display: flex; align-items: center; gap: 0.75rem; }

    .user-info .user-name {
        font-weight: 700;
        color: var(--text);
        display: block;
    }

    .user-info .user-email {
        font-size: 0.78rem;
        color: var(--muted);
    }

    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .badge-role.admin {
        background: var(--purple-100);
        color: var(--purple-700);
    }

    .badge-role.moderator {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .date-text {
        font-size: 0.8rem;
        color: var(--muted);
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.4rem 0.85rem;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        font-family: 'Cairo', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--muted);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--purple-200);
        display: block;
        margin-bottom: 0.75rem;
    }

    .empty-state p { font-size: 0.9rem; }

    .alert-success-banner {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        border-radius: 12px;
        padding: 0.85rem 1.1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.9rem;
        font-weight: 600;
    }
</style>

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

{{-- Success --}}
@if (session('success'))
    <div class="alert-success-banner">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <strong>{{ $users->count() }}</strong>
            <span>إجمالي المستخدمين</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="bi bi-shield-fill-check"></i></div>
        <div class="stat-info">
            <strong>{{ $users->where('role','admin')->count() }}</strong>
            <span>المديرون</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-fill"></i></div>
        <div class="stat-info">
            <strong>{{ $users->where('role','moderator')->count() }}</strong>
            <span>المودريتورز</span>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-card-header">
        <h2><i class="bi bi-list-ul"></i> قائمة المستخدمين</h2>
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="بحث...">
        </div>
    </div>

    @if($users->isEmpty())
        <div class="empty-state">
            <i class="bi bi-people"></i>
            <p>لا يوجد مستخدمون بعد</p>
        </div>
    @else
        <div style="overflow-x:auto">
            <table class="users-table" id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>الصلاحية</th>
                        <th>تاريخ الإنشاء</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td style="color:var(--muted); font-size:0.8rem">{{ $loop->iteration }}</td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                                <div>
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-email">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge-role admin"><i class="bi bi-shield-fill-check"></i> مدير</span>
                            @else
                                <span class="badge-role moderator"><i class="bi bi-eye-fill"></i> مودريتور</span>
                            @endif
                        </td>
                        <td>
                            <span class="date-text">{{ $user->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="bi bi-trash3"></i> حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Add User Modal --}}
<div class="mc-modal-overlay" id="userModal">
    <div class="mc-modal">
        <div class="mc-modal-header">
            <h3><i class="bi bi-person-plus-fill"></i> إضافة مستخدم جديد</h3>
            <button class="mc-modal-close" onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="mc-modal-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="field-group">
                    <label>الاسم <span class="required">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="اسم المستخدم">
                    </div>
                    @error('name')<div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label>البريد الإلكتروني <span class="required">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" dir="ltr" style="text-align:right">
                    </div>
                    @error('email')<div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label>كلمة المرور <span class="required">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" placeholder="••••••••">
                    </div>
                    @error('password')<div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                    <label>تأكيد كلمة المرور <span class="required">*</span></label>
                    <div class="input-wrap">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>

                <div class="field-group">
                    <label>الصلاحية <span class="required">*</span></label>
                    <div class="role-options">
                        <div class="role-option">
                            <input type="radio" name="role" id="role_admin" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                            <label for="role_admin"><i class="bi bi-shield-fill-check"></i> مدير</label>
                        </div>
                        <div class="role-option">
                            <input type="radio" name="role" id="role_moderator" value="moderator" {{ old('role','moderator') == 'moderator' ? 'checked' : '' }}>
                            <label for="role_moderator"><i class="bi bi-eye-fill"></i> مودريتور</label>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> حفظ</button>
                    <button type="button" class="btn-cancel-modal" onclick="closeModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

@endsection
