<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class DocumentHelper
{
    public static function removeMask(string $document): string
    {
        $document = Str::replace('.', '', $document);
        $document = Str::replace('-', '', $document);
        return Str::replace('/', '', $document);
    }
}
