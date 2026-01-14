<?php

namespace App\Http\Controllers;

use App\Data\Patient\IndexPatientData;
use App\Data\Patient\PatientData;
use App\Domains\Patient\DTO\Requests\PatientParamsDTO;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Services\PatientService;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Http\Controllers\PatientController\IndexTest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PatientController extends Controller
{
    public function __construct(
        private readonly PatientService $patientService
    ) {}

    /**
     * @param IndexPatientData $request
     * @return JsonResponse
     * @see IndexTest
     */
    public function index(IndexPatientData $request): JsonResponse
    {
        $data = $this->patientService->getAll($request);

        return response()->json($data);
    }

   public function store(PatientData $request): PatientData
    {
        return $this->patientService->create($request)->wrap('data');
    }

    public function show(string $uuid): PatientData
    {
        return $this->patientService->getByUuid($uuid)->wrap('data');
    }

    public function update(string $uuid, PatientData $request): PatientData
    {
        $request->uuid = $uuid;
        $this->patientService->update($request);

        return $this->show($request->uuid);
    }

    public function destroy(string $uuid): void
    {
        $this->patientService->delete($uuid);
    }
}
