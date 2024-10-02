<?php

namespace App\Http\Controllers;

use App\Domains\Patient\DTO\Requests\PatientParamsDTO;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Services\PatientService;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PatientController extends Controller
{
    public function __construct(
        private readonly PatientService $patientService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = $this->patientService->getAll();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request)
    {
        $paramsDto = PatientParamsDTO::fromRequest($request);

        $data = $this->patientService->create($paramsDto);

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->patientService->getById($id);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Patient $patient, UpdatePatientRequest $request): JsonResponse
    {
        $paramsDto = PatientParamsDTO::fromRequest($request);

        $data = $this->patientService->updateByModel($paramsDto, $patient);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $this->patientService->deleteByModel($patient);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
