{{-- Stats & Table --}}
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
