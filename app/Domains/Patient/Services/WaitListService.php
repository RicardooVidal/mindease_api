<?php

namespace App\Domains\Patient\Services;

use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Repositories\WaitListRepository;

class WaitListService
{
    public function __construct(
        private readonly WaitListRepository $waitListRepository
    ) {}

    public function create(int $patientId): array
    {
        return $this->waitListRepository
            ->create($patientId)
            ->toArray();
    }

    public function delete(int $patientId): void
    {
        $this->waitListRepository->delete($patientId);
    }
}
