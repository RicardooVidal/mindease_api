<?php

namespace App\Domains\Consultation\Repositories;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Entities\Contract;
use Illuminate\Pagination\LengthAwarePaginator;

class ConsultationRepository
{
    public function __construct(
        private readonly Consultation $consultation
    ) {}

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->consultation
            ->with(['patient:id,first_name,last_name'])
            ->when(!empty($filters), fn($query) => $query->where($filters))->paginate(10);
    }

    public function getById(int $id): ?Consultation
    {
        return $this->consultation
            ->with(['patient:id,first_name,last_name'])
            ->find($id);
    }

    public function create(array $params): Consultation
    {
        return $this->consultation->create($params);
    }

    public function update(int $id, array $params): bool
    {
        return $this->consultation->findOrFail($id)->update($params);
    }

    public function delete(int $id): void
    {
        $this->consultation->findOrFail($id)->delete();
    }
}
