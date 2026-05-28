<?php

namespace App\Http\Requests;

use App\Data\Consultation\ConsultationData;
use Illuminate\Foundation\Http\FormRequest;

class ConsultationRequest extends FormRequest
{
    public function rules(): array
    {
        return ConsultationData::rules($this->getValidationContext());
    }

    private function getValidationContext()
    {
        return new \Spatie\LaravelData\Support\Validation\ValidationContext(
            payload: $this->all(),
            fullPayload: $this->all(),
            dataClass: ConsultationData::class
        );
    }
}
