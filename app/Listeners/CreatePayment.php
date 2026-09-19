<?php

namespace App\Listeners;

use App\Domains\Payment\DTO\Requests\PaymentParamsDTO;
use App\Domains\Payment\Enums\PaymentStatusEnum;
use App\Domains\Payment\Enums\PaymentTypeEnum;
use App\Domains\Payment\Services\PaymentService;
use App\Events\AppointmentCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePayment
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(AppointmentCreatedEvent $event): void
    {
        $paramsDTO = PaymentParamsDTO::fromArray([
            'appointment_id' => $event->appointment->id,
            'value' => 0,
            'status' => PaymentStatusEnum::PENDING->value,
            'notes' => null,
            'type' => PaymentTypeEnum::PENDING->value
        ]);

        $this->paymentService->create($paramsDTO);
    }
}
