<?php

namespace App\Domains\Patient\DTO\Requests;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class PatientParamsDTO extends DataTransferObject
{
    public string $first_name;
    public string $last_name;
    public ?int $document;
    public bool $active;
    public string $notes;

    public static function fromRequest(Request $request): self
    {
        return new self(...$request->all());
    }

}
