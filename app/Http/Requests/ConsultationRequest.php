<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'time' => ['nullable', 'string', 'date_format:H:i'],
        ];
    }
}
