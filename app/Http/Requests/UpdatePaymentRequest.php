<?php

namespace App\Http\Requests;

use App\Domains\Payment\Enums\PaymentStatusEnum;
use App\Domains\Payment\Enums\PaymentTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'between:0,999999.99'],
            'status' => ['nullable', 'string', Rule::in(array_column(PaymentStatusEnum::cases(), 'value'))],
            'notes' => ['nullable', 'string', 'max:500'],
            'type' => ['nullable' , 'string', Rule::in(array_column(PaymentTypeEnum::cases(), 'value'))],
        ];
    }
}
