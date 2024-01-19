<?php

namespace App\Http\Controllers\Tenant\Api\Compliance;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use Illuminate\Http\JsonResponse;

class DomainsController extends BaseApiController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(new Domain());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponses
     * @OA\Get(
     *   tags={"Document Analysis Domain"},
     *   path="/compliance/domains",
     *   summary="Get all available domains for documents",
     *   description="Get all available domains for documents",
     *   operationId="documentAnalysisDomain",
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
        return $this->successResponse($this->resource);
    }

    /**
     * Display the specified resource.
     * @param Domain $domain
     * @return ResourceCollection|JsonResponse
     *   * @OA\Get(
     *   tags={"Document Analysis Domain"},
     *   path="/compliance/domains/{domain}/",
     *   summary="Get a domain by id",
     *   description="Get a domain by id",
     *   operationId="documentAnalysisDomainShow",
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
}
