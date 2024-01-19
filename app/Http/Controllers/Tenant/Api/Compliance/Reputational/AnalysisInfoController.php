<?php

namespace App\Http\Controllers\Tenant\Api\Compliance\Reputational;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnalysisInfoController extends BaseApiController
{
    /**
     * AnalysisInfoController constructor.
     */
    public function __construct()
    {
        parent::__construct(new AnalysisInfo());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Reputational Analysis Info"},
     *   path="/reputational/analysis-info",
     *   summary="Analysis info index",
     *   operationId="analysisInfoIndex",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
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
        return $this->successResponse();
    }

    /**
     * Display the specified resource.
     * @param AnalysisInfo $analysisInfo
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Reputational Analysis Info"},
     *   path="/reputational/analysis-info/{analysisInfo}",
     *   summary="Reputational Analysis Info show",
     *   operationId="analysisInfoShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     * @OA\Parameter(
     *   name="analysisInfo",
     *   in="path",
     *   description="analysisInfo Id",
     *   required=true,
     *   @OA\Schema(type="integer",format="int64" ),
     *   example=1
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
    public function show(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->loadedRelations = ['rawdata'];
        $this->resource = $this->resource->whereId(decryptValue($idEncrypted))->firstOrFail();
        return $this->successResponse($resource);
    }
}
