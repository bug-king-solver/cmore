<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant;
use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use Illuminate\Http\JsonResponse;

class TenantController extends BaseApiController
{
    /**
     * TenantController constructor.
     */
    public function __construct()
    {
        parent::__construct(new Tenant());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Tenant"},
     *   path="/tenants/{token}/{feature}/",
     *   summary="Lista all tenants",
     *   operationId="tenantindex",
     *   @OA\Parameter(
     *      name="token",
     *      in="path",
     *      description="Access token to fetch tenant data",
     *      required=true,
     *      @OA\Schema(type="string",format="string")
     *   ),
     *  @OA\Parameter(
     *      name="feature",
     *      in="path",
     *      description="compliance or reputation",
     *      required=false,
     *      @OA\Schema(type="string",format="string")
     *   ),
     *        *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(),
     *              ),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function index(string $token, string $feature = 'compliance'): ResourceCollection|JsonResponse
    {
        if ($token != config('app.cmore_central_token_access_api')) {
            abort(403, 'Invalid token');
        }

        $searchString = 'data->features->compliance->enabled';
        if ($feature == 'reputation') {
            $searchString = 'data->features->reputation->enabled';
        }
        $this->resource = $this->resource->whereJsonContains($searchString, true)
            ->get();

        return new ResourceCollection($this->resource);
    }
}
