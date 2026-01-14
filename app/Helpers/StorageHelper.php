<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class StorageHelper
{
    public static function upload(mixed $file, string $name = null, string $path = '', string $disk = 'app_files'): string
    {
        if (!$name) {
            $name = Uuid::uuid4();
        }

        return Storage::disk($disk)->putFileAs($path, $file, $name);
    }
}