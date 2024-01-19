<?php

namespace App\Http\Controllers\Tenant\Api\Compliance;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\Compliance\DocumentAnalysisResultRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use Exception;
use Illuminate\Http\JsonResponse;

class DocumentAnalysisController extends BaseApiController
{
    /**
     * DocumentAnalysisController constructor.
     */
    public function __construct()
    {
        parent::__construct(new Result());
        $this->loadedRelations = ['type.domains', 'domains', 'snippets'];
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Document Analysis"},
     *   path="/compliance/document_analysis",
     *   summary="Get all unstarted document analysis",
     *   description="Get all unstarted document analysis",
     *   operationId="documentAnalysisIndex",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
     *   @OA\Parameter(ref="#/components/parameters/sortBy"),
     *   @OA\Parameter(ref="#/components/parameters/sortByDesc"),
     *   @OA\Parameter(ref="#/components/parameters/select"),
     *   @OA\Response(
     *     response="200", description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function index(): ResourceCollection|JsonResponse
    {
        return $this->successResponse($this->resource->where('started_at', null));
    }

    /**
     * Display the specified resource.
     * @param Result $result
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Document Analysis"},
     *   path="/compliance/document_analysis/{document_analysis}/",
     *   summary="Get for a specific document analysis",
     *   description="Get for a specific document analysis by id",
     *   operationId="documentAnalysisShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/document_analysis"),
     *   @OA\Response(
     *     response="200", description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function show(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->resource = $this->resource->whereId(decryptValue($idEncrypted))->firstOrFail();
        return $this->successResponse($resource);
    }

    /**
     * Update the specified resource in storage.
     * @param DocumentAnalysisResultRequest $request
     * @param Result $result
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Document Analysis"},
     *   path="/compliance/document_analysis/{document_analysis}/start",
     *   summary="Start document analysis",
     *   description="Start document analysis. After this operation, document analysis will be started and you can't change it",
     *   operationId="documentAnalysisStart",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/document_analysis"),
     *   @OA\Response(
     *     response="200", description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function start(DocumentAnalysisResultRequest $request, int $result): ResourceCollection|JsonResponse
    {
        try {
            $result = Result::findOrfail($result);
            if ($result->started_at != null) {
                abort(422, 'Document analysis already started');
            }

            $data['started_at'] = carbon()->now();

            $result->update($data);

            return $this->successResponse($result);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param Result $result
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Document Analysis"},
     *   path="/compliance/document_analysis/{document_analysis}/file",
     *   summary="Document analysis download",
     *   description="Find a specific document analysis by id and download it",
     *   operationId="documentAnalysisDownload",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/document_analysis"),
     *   @OA\Response(
     *     response="200", description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function download(int $result)
    {
        $result = Result::where('id', $result)->with($this->loadedRelations)->first();
        if (!$result) {
            abort(404, 'Document analysis not found');
        }
        $media = $result->media->first();
        if (!$media) {
            abort(404, 'Document analysis hasn\'t media file');
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    /**
     * Update the specified resource in storage.
     * @param DocumentAnalysisResultRequest $request
     * @param int $result
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Document Analysis"},
     *   path="/compliance/document_analysis/{document_analysis}/finish",
     *   summary="Finish document analysis",
     *   description="Finish document analysis. Insert the snippet of the document analysis and the domains.Analysis will be finished and you can't change it",
     *   operationId="documentAnalysisFinish",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/document_analysis"),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/DocumentAnalysisResultRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/DocumentAnalysisResultRequest")
     *   ),
     *   @OA\Response(
     *     response="200", description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Property(property="data", type="array", @OA\Items()),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function finish(DocumentAnalysisResultRequest $request, int $result): ResourceCollection|JsonResponse
    {
        try {
            $result = Result::findOrfail($result);
            $data = $request->validated();
            if (!isset($data['ended_at']) && $result->ended_at == null) {
                $data['ended_at'] = carbon()->now();
            }

            $result->update($data);

            foreach ($data['snippets'] as $snippet) {
                $snippet['document_analysis_domain_id'] = $snippet['domain_id'];
                $result->snippets()->create($snippet);
            }
            return $this->successResponse($result);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
