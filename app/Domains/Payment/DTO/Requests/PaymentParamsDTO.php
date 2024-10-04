<?php

namespace App\Domains\Payment\DTO\Requests;

use App\Domains\Payment\Enums\PaymentStatusEnum;
use App\Domains\Payment\Enums\PaymentTypeEnum;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class PaymentParamsDTO extends DataTransferObject
{
    const STATUS = PaymentStatusEnum::PENDING->value;
    const TYPE = PaymentTypeEnum::PENDING->value;

    public int $consultation_id;
    public float $value;
    public string $status = self::STATUS;
    public ?string $notes;
    public string $type = self::TYPE;

    public static function fromRequest(Request $request): self
    {
        $request = self::checkStatus($request);

        return new self(...$request->all());
    }

    private static function checkStatus(Request $request): Request
    {
        if (
            $request->has('type')
            && $request->get('type') !== PaymentTypeEnum::PENDING->value
        ) {
            $request->merge(['status' => PaymentStatusEnum::PAID->value]);
        }

        if (
            $request->has('type')
            && $request->get('type') == PaymentTypeEnum::PENDING->value
        ) {
            $request->merge(['status' => PaymentStatusEnum::PENDING->value]);
        }

        return $request;
    }

}
