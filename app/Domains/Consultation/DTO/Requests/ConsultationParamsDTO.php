<?php

namespace App\Domains\Consultation\DTO\Requests;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class ConsultationParamsDTO extends DataTransferObject
{
    public int $patient_id;
    public string $date;
    public ?string $time;
    public static function fromRequest(Request $request): self
    {
        return new self(...$request->all());
    }

}
