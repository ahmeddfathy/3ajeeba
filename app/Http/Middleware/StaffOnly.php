<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffOnly
{
    /**
     * يسمح فقط لـ admin و moderator بالدخول.
     * أي مستخدم بـ role = 'user' (أو بدون role) يتم رفضه.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'moderator'])) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
