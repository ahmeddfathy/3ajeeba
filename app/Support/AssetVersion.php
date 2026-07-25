<?php

namespace App\Support;

class AssetVersion
{
    public static function t(string ...$paths): int
    {
        $times = [];

        foreach ($paths as $path) {
            $full = public_path($path);
            if (is_file($full)) {
                $times[] = (int) filemtime($full);
            }
        }

        return $times ? max($times) : time();
    }
}
