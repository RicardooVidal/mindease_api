<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexPatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'nullable',
                'uuid',
            ],
            'first_name' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
            ],
            'document' => [
                'nullable',
                'string',
                'max:11',
            ],
        ];
    }
}
