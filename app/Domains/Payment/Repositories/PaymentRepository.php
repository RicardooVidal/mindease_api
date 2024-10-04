<?php

namespace App\Domains\Payment\Repositories;

use App\Domains\Payment\Entities\Payment;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentRepository
{
    public function __construct(
        private readonly Payment $payment
    ) {}

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->payment
            ->with(['consultation:id,patient_id', 'consultation.patient:id,first_name,last_name'])
            ->when(!empty($filters), fn($query) => $query->where($filters))->paginate(10);
    }

    public function getById(int $id): ?Payment
    {
        return $this->payment
            ->with(['consultation:id,patient_id', 'consultation.patient:id,first_name,last_name'])
            ->find($id);
    }

    public function create(array $params): Payment
    {
        return $this->payment->create($params);
    }

    public function updateByModel(Payment $payment, array $params): bool
    {
        return $payment->update($params);
    }

    public function deleteByModel(Payment $payment): void
    {
        $payment->delete();
    }
}
