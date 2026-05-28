<?php

namespace App\Domains\Patient\Services;

use App\Data\WaitList\WaitListData;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Repositories\WaitListRepository;

class WaitListService
{
    public function __construct(
        private readonly WaitListRepository $waitListRepository
    ) {}

    public function create(WaitListData $waitListData): WaitListData
    {
        return WaitListData::from([
            'uuid' => $this->waitListRepository->create($waitListData)->uuid,
            'patient_uuid' => $waitListData->patientUuid,
        ]);
    }

    public function delete(string $uuid): void
    {
        $this->waitListRepository->delete($uuid);
    }
}
