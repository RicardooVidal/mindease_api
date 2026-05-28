<?php

namespace App\Http\Controllers;

use App\Data\WaitList\WaitListData;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Services\PatientService;
use App\Domains\Patient\Services\WaitListService;
use App\Http\Requests\WaitListRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WaitListController extends Controller
{
    public function __construct(
        private readonly WaitListService $waitListService
    ) {}

    public function store(WaitListData $request): WaitListData
    {
        return $this->waitListService->create($request);
    }

    public function destroy(string $uuid): void
    {
        $this->waitListService->delete($uuid);
    }
}
