<?php

namespace App\Support;

class ArabicSlug
{
    public static function make(?string $text): string
    {
        $slug = trim((string) $text);
        $slug = preg_replace('/\s+/u', '-', $slug) ?? '';
        $slug = preg_replace('/[^\x{0600}-\x{06FF}a-zA-Z0-9\-_]/u', '', $slug) ?? '';
        $slug = preg_replace('/-+/', '-', $slug) ?? '';

        return trim($slug, '-') ?: 'post-'.uniqid();
    }
}
