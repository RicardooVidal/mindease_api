<?php

namespace App\Http\Controllers;

use App\Domains\Contract\DTO\Requests\ContractParamsDTO;
use App\Domains\Contract\Services\ContractService;
use App\Helpers\StorageHelper;
use App\Http\Requests\ContractRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('memory_limit', '128M');

class ContractController extends Controller
{
    public function __construct(
        private ContractService $contractService
    ) {}

    public function index(): JsonResponse
    {
        $data = $this->contractService->getAll();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request): JsonResponse
    {
        $paramsDto = ContractParamsDTO::fromRequest($request);
        $path = StorageHelper::upload($paramsDto->document, 'document_test.pdf', 'test');

        $path = Storage::url($path);
        dd($path);

        $data = $this->contractService->create($paramsDto);

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
    * Display the specified resource.
    */
    public function show(string $id)
    {
        $data = $this->contractService->getById($id);

        $filename = 'contract_' . $id . '_' . time() . '.pdf';

        $data['document'] = stream_get_contents($data['document']);

        Storage::disk('local')->put($filename, pg_unescape_bytea($data['document']));

        $fileUrl = Storage::url($filename);

        return response()->json(['url' => $fileUrl], Response::HTTP_OK);
    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(Consultation $consultation, ConsultationRequest $request): JsonResponse
//    {
//        $paramsDto = ConsultationParamsDTO::fromRequest($request);
//
//        $data = $this->consultationService->updateByModel($paramsDto, $consultation);
//
//        return response()->json($data, Response::HTTP_OK);
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Consultation $consultation): JsonResponse
//    {
//        $this->consultationService->deleteByModel($consultation);
//
//        return response()->json([], Response::HTTP_NO_CONTENT);
//    }
}
