<?php

namespace App\Http\Controllers;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\DTO\Requests\ConsultationParamsDTO;
use App\Domains\Consultation\Services\ConsultationService;
use App\Http\Requests\ConsultationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ConsultationController extends Controller
{
    public function __construct(
        private ConsultationService $consultationService
    ) {}

    public function index(): JsonResponse
    {
        $data = $this->consultationService->getAll();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsultationRequest $request)
    {
        $paramsDto = ConsultationParamsDTO::fromRequest($request);

        $data = $this->consultationService->create($paramsDto);

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->consultationService->getById($id);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Consultation $consultation, ConsultationRequest $request): JsonResponse
    {
        $paramsDto = ConsultationParamsDTO::fromRequest($request);

        $data = $this->consultationService->updateByModel($paramsDto, $consultation);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation): JsonResponse
    {
        $this->consultationService->deleteByModel($consultation);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
