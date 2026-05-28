<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class SelectData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
    ) {}
}
