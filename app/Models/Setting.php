<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::allCached();

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) ($value ?? '')]
        );

        Cache::forget('store_settings');
    }

    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) ($value ?? '')]
            );
        }

        Cache::forget('store_settings');
    }

    public static function allCached(): array
    {
        try {
            return Cache::rememberForever('store_settings', function () {
                return static::query()->pluck('value', 'key')->all();
            });
        } catch (\Throwable) {
            return [];
        }
    }

    public static function checkoutMode(): string
    {
        $mode = static::get('checkout_mode', 'whatsapp');

        return in_array($mode, ['whatsapp', 'online', 'both'], true) ? $mode : 'whatsapp';
    }

    public static function allowsWhatsAppCheckout(): bool
    {
        return in_array(static::checkoutMode(), ['whatsapp', 'both'], true);
    }

    public static function allowsOnlineCheckout(): bool
    {
        return in_array(static::checkoutMode(), ['online', 'both'], true);
    }

    public static function whatsappNumber(): string
    {
        $fromSettings = preg_replace('/\D+/', '', (string) static::get('whatsapp_number', ''));
        if ($fromSettings) {
            return $fromSettings;
        }

        return preg_replace('/\D+/', '', (string) config('store.contact.whatsapp', '')) ?: '';
    }
}
