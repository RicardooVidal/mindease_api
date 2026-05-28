<?php

namespace App\Data\Contract;

use App\Data\Casts\CarbonCast;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class IndexContractData extends Data
{
    public function __construct(
        public ?string $uuid = null,
        public ?string $description = null,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $validUntil = null,
        public ?string $patientUuid = null,
        public ?string $documentUrl = null,
    )
    {}
}
