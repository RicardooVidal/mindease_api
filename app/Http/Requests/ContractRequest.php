<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id', 'unique:contracts'],
            'description' => ['required', 'string'],
            'valid_until' => ['required', 'date', 'date_format:Y-m-d'],
            'document' => ['nullable', 'file', 'mimes:pdf,docx'],
        ];
    }
}
