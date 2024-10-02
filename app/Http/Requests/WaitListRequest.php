<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaitListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id', 'unique:wait_list']
        ];
    }

    public function messages()
    {
        return [
            'patient_id.unique' => 'Paciente já está na lista de espera',
        ];
    }
}
