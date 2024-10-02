<?php

namespace App\Http\Controllers;

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

    public function store(WaitListRequest $request): JsonResponse
    {
        $data = $this->waitListService->create($request->get('patient_id'));

        return response()->json($data, Response::HTTP_CREATED);
    }

    public function destroy(int $patientId): JsonResponse
    {
        $this->waitListService->delete($patientId);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
