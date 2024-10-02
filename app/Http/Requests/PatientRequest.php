<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'document' => ['required', 'integer', 'digits:11', 'unique:patients'],
            'active' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500']
        ];
    }
}
