<?php

namespace App\Http\Controllers\Tenant\Api\Compliance\Reputational;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\Compliance\Reputational\AnalysisRawDataFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\Reputational\AnalysisRawData;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AnalysisInfoRawController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(new AnalysisRawData());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Reputational Analysis Raw Data"},
     *   path="/reputational/analysis-info-raw",
     *   summary="Analysis info raw index",
     *   operationId="analysisInfoRawIndex",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
     *   @OA\Parameter(ref="#/components/parameters/sortBy"),
     *   @OA\Parameter(ref="#/components/parameters/sortByDesc"),
     *   @OA\Parameter(ref="#/components/parameters/select"),
     *   @OA\Parameter(ref="#/components/parameters/search"),
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
     * Store a newly created resource in storage.
     * @param AnalysisRawDataFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputational Analysis Raw Data"},
     *   path="/reputational/analysis-info-raw",
     *   summary="Analysis info raw store",
     *   operationId="analysisInfoRawStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new Reputational Analysis Raw Data",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/AnalysisRawDataFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/AnalysisRawDataFormRequest")
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
    public function store(AnalysisRawDataFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['reputation_analysis_info_id'] = $data['ainfo_id'];
            unset($data['ainfo_id']);
            $rawData = $this->resource::create($data);

            return $this->successResponse($rawData);
        } catch (\Exception | AccessDeniedHttpException $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * @OA\Get(
     *   tags={"Search Analysis Raw Data"},
     *   path="/reputational/analysis-info-raw/search/{extracted_at}",
     *   summary="Search for a Analysis Raw Data",
     *   description="Search for a Analysis Raw Data",
     *   operationId="searchRawData",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(
     *     name="extracted_at",
     *     in="path",
     *     description="The Analysis Raw Data type to search for 2023-05-18",
     *     required=true,
     *     @OA\Schema( type="string" )
     *    ),
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
    public function searchRawData(Request $request, $extractedAt)
    {
        try {
            if (empty($extractedAt) || $extractedAt == ' ') {
                throw new Exception(__('Missing extracted_at'), 422);
            }
            $this->resource = $this->resource->whereDate('extracted_at', $extractedAt)->get();

            return $this->successResponse($resource);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
