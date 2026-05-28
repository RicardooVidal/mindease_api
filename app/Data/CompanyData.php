<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CompanyData extends Data
{
    public function __construct(
        public string $uuid,
        public string $company,
    ) {}
}
