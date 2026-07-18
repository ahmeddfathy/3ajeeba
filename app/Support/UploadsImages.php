<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait UploadsImages
{
    protected function uploadPublicImage(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = public_path($directory);
        File::ensureDirectoryExists($path);
        $file->move($path, $filename);

        return trim($directory, '/') . '/' . $filename;
    }

    protected function deletePublicImage(?string $path, string $prefix): void
    {
        if (! $path || ! str_starts_with($path, $prefix)) {
            return;
        }

        $full = public_path($path);
        if (is_file($full)) {
            unlink($full);
        }
    }
}
