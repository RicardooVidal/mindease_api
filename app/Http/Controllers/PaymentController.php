<?php

namespace App\Http\Controllers;

use App\Domains\Payment\DTO\Requests\PaymentParamsDTO;
use App\Domains\Payment\Entities\Payment;
use App\Domains\Payment\Services\PaymentService;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function index(): JsonResponse
    {
        $data = $this->paymentService->getAll();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $paramsDto = PaymentParamsDTO::fromRequest($request);

        $data = $this->paymentService->create($paramsDto);

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->paymentService->getById($id);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, UpdatePaymentRequest $request): JsonResponse
    {
        $paramsDto = PaymentParamsDTO::fromRequest($request);

        $data = $this->paymentService->update($id, $paramsDto);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->paymentService->delete($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
