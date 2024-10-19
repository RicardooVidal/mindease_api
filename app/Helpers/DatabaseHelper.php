<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    public static function changeSchema(string $schema = 'public'): void
    {
        DB::statement("SET search_path TO $schema");
    }
}