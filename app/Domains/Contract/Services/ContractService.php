<?php

namespace App\Domains\Contract\Services;

use App\Data\Appointment\AppointmentData;
use App\Data\Contract\ContractData;
use App\Data\Contract\IndexContractData;
use App\Domains\Contract\Entities\Contract;
use App\Domains\Contract\Repositories\ContractRepository;
use App\Domains\Contract\DTO\Requests\ContractParamsDTO;
use App\Domains\Contract\Transforms\TransformContract;
use App\Domains\Contract\Transforms\TransformContracts;
use App\Domains\Patient\Entities\Patient;

class ContractService
{
    public function __construct(
        private readonly ContractRepository $contractRepository
    ) {}

    public function create(ContractData $contractData, string $document): ContractData
    {
        return ContractData::fromModel(
            $this->contractRepository->create($contractData, $document)
        );
    }

    public function getAll(IndexContractData $filters): array
    {
        return $this->contractRepository->getAll($filters)->toArray();
    }

    public function getByUuid(string $uuid): ContractData
    {
        return ContractData::fromModel(
            $this->contractRepository->getByUuid($uuid)
        );
    }

    public function update(ContractData $contractData, Patient $patient): bool
    {
        return $this->contractRepository->update($contractData, $patient);
    }

    public function delete(string $uuid): void
    {
        $this->contractRepository->delete($uuid);
    }
}
