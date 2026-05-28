<?php

namespace App\Data;

use Illuminate\Support\Enumerable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CompanyDataCollection extends DataCollection
{
    public function __construct(string $dataClass, Enumerable|array|DataCollection|null $items)
    {
        parent::__construct($dataClass, $items);
    }
}
