<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseHelper
{
    public static function changeSchema(string $schema = 'public'): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        $schema = Str::replace('"', '""', $schema);
        DB::statement('SET search_path TO "' . $schema . '"');
    }
}
