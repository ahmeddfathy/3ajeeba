<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __invoke(): View
    {
        return view('store.contact', [
            'whatsapp' => preg_replace('/\D+/', '', (string) config('store.contact.whatsapp')),
            'phone' => config('store.contact.phone'),
            'facebook' => config('store.social.facebook'),
            'currency' => config('store.currency'),
        ]);
    }
}
