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
            ->with(['appointment:id,patient_id', 'appointment.patient:id,first_name,last_name'])
            ->when(!empty($filters), fn($query) => $query->where($filters))->paginate(10);
    }

    public function getById(int $id): ?Payment
    {
        return $this->payment
            ->with(['appointment:id,patient_id', 'appointment.patient:id,first_name,last_name'])
            ->find($id);
    }

    public function create(array $params): Payment
    {
        return $this->payment->create($params);
    }

    public function update(int $id, array $params): bool
    {
        return $this->payment->findOrFail($id)->update($params);
    }

    public function delete(int $id): void
    {
        $this->payment->findOrFail($id)->delete();
    }
}
