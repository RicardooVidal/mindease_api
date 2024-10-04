<?php

namespace App\Domains\Payment\Services;

use App\Domains\Payment\DTO\Requests\PaymentParamsDTO;
use App\Domains\Payment\Entities\Payment;
use App\Domains\Payment\Repositories\PaymentRepository;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository
    ) {}

    public function getAll(array $filters = []): array
    {
        return $this->paymentRepository->getAll($filters)->toArray();
    }

    public function getById(int $id): ?array
    {
        return $this->paymentRepository->getById($id)?->toArray();
    }

    public function create(PaymentParamsDTO $paramsDTO): array
    {
        return $this->paymentRepository
            ->create($paramsDTO->toArray())
            ->toArray();
    }

    public function updateByModel(PaymentParamsDTO $paramsDTO, Payment $payment): bool
    {
        return $this->paymentRepository
            ->updateByModel($payment, $paramsDTO->toArray());
    }

    public function deleteByModel(Payment $payment): void
    {
        $this->paymentRepository->deleteByModel($payment);
    }
}
