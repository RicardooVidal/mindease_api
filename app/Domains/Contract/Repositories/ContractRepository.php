<?php

namespace App\Domains\Contract\Repositories;

use App\Domains\Contract\Entities\Contract;
use Illuminate\Pagination\LengthAwarePaginator;

class ContractRepository
{
    public function __construct(
        private readonly Contract $contract
    ) {}

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->contract
            ->with(['patient:id,first_name,last_name'])
            ->when(!empty($filters), fn($query) => $query->where($filters))->paginate(10);
    }

    public function getById(int $id): ?Contract
    {
        return $this->contract
            ->with(['patient:id,first_name,last_name'])
            ->find($id);
    }


    public function create(array $params): Contract
    {
        return $this->contract->create($params);
    }

    public function updateByModel(Contract $contract, array $params): bool
    {
        return $contract->update($params);
    }

    public function deleteByModel(Contract $contract): void
    {
        $contract->delete();
    }
}
