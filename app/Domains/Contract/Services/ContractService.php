<?php

namespace App\Domains\Contract\Services;

use App\Domains\Contract\Entities\Contract;
use App\Domains\Contract\Repositories\ContractRepository;
use App\Domains\Contract\DTO\Requests\ContractParamsDTO;
use App\Domains\Contract\Transforms\TransformContract;
use App\Domains\Contract\Transforms\TransformContracts;

class ContractService
{
    public function __construct(
        private readonly ContractRepository $contractRepository
    ) {}

    public function create(ContractParamsDTO $paramsDTO): array
    {
        return $this->contractRepository
            ->create($paramsDTO->toArray())
            ->toArray();
    }

    public function getAll(array $filters = []): array
    {
        return $this->contractRepository->getAll($filters)->toArray();
    }

    public function getById(int $id): ?array
    {
        $contract = $this->contractRepository->getById($id);

        if ($contract) {
            return TransformContract::execute($contract);
        }

        return [];
    }

    public function updateByModel(ContractParamsDTO $paramsDTO, Contract $contract): bool
    {
        return $this->contractRepository
            ->updateByModel($contract, $paramsDTO->toArray());
    }

    public function deleteByModel(Contract $contract): void
    {
        $this->contractRepository->deleteByModel($contract);
    }
}
