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
