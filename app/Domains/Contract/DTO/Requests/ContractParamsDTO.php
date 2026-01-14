<?php

namespace App\Domains\Contract\DTO\Requests;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class ContractParamsDTO extends DataTransferObject
{
    public int $patient_id;
    public string $description;
    public ?string $valid_until;
    public UploadedFile $document;

    public static function fromRequest(Request $request): self
    {
        return new self(
            patient_id: $request->input('patient_id'),
            description: $request->input('description'),
            valid_until: $request->input('valid_until'),
            document: $request->file('document')
        );
    }
}
