<?php

namespace App\Http\Controllers;

use App\Data\Consultation\ConsultationData;
use App\Data\Consultation\IndexConsultationData;
use App\Domains\Consultation\DTO\Requests\ConsultationParamsDTO;
use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Services\ConsultationService;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Services\PatientService;
use App\Exceptions\PatientNotActiveException;
use App\Http\Requests\ConsultationRequest;
use Http\Controllers\ConsultationController\IndexTest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConsultationController extends Controller
{
    public function __construct(
        private ConsultationService $consultationService,
        private PatientService $patientService,
    ) {}

    /**
     * @param IndexConsultationData $request
     * @return JsonResponse
     * @see IndexTest
     */
    public function index(IndexConsultationData $filters): JsonResponse
    {
        $data = $this->consultationService->getAll($filters);

        return response()->json(['data' => $data]);
    }

    /**
     * @throws PatientNotActiveException
     */
    public function store(ConsultationData $request): ConsultationData
    {
        $patient = Patient::query()->where('uuid', $request->patient->uuid)->first();

        if (!$patient->active) {
            throw new PatientNotActiveException();
        }

        return $this->consultationService->create($request, $patient)->wrap('data');
    }

    public function show(string $uuid): ConsultationData
    {
        return $this->consultationService->getByUuid($uuid)->wrap('data');
    }

    public function update(ConsultationData $request): ConsultationData
    {
        $patient = Patient::query()->where('uuid', $request->patient->uuid)->first();

        if (!$patient->active) {
            throw new PatientNotActiveException();
        }

        return $this->consultationService->update($request, $patient)->wrap('data');
    }

    public function destroy(string $uuid): void
    {
        $this->consultationService->delete($uuid);
    }
}
