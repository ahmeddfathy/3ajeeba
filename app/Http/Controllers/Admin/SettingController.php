<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'checkoutMode' => Setting::checkoutMode(),
            'whatsappNumber' => Setting::get('whatsapp_number') ?: config('store.contact.whatsapp'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'checkout_mode' => ['required', Rule::in(['whatsapp', 'online', 'both'])],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
        ]);

        Setting::setMany([
            'checkout_mode' => $data['checkout_mode'],
            'whatsapp_number' => preg_replace('/\D+/', '', (string) ($data['whatsapp_number'] ?? '')) ?: '',
        ]);

        return back()->with('success', 'تم حفظ إعدادات المتجر');
    }
}
