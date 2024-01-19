<?php

namespace App\Http\Controllers\Tenant\Api\Compliance\Reputational;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\Compliance\Reputational\KeywordFrequencyDailyRequest;
use App\Http\Requests\Api\Compliance\Reputational\KeywordFrequencyRequest;
use App\Http\Requests\Api\Compliance\Reputational\MonthlyRequest;
use App\Http\Requests\Api\Compliance\Reputational\WeeklyRequest;
use App\Http\Requests\Api\Compliance\Reputational\YearlyRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequency;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyMonthly;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyWeekly;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyYearly;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class KeywordFrequencyController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(new AnalysisKeywordsFrequency());
    }

    /**
     * Store a newly created resource in storage.
     * @param KeywordFrequencyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Keyword Frequency"},
     *   path="/reputational/keywords-frequency",
     *   summary="Keyword frequency store",
     *   operationId="keywordsFrequencyStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new keyword frequency",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/KeywordFrequencyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/KeywordFrequencyRequest")
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
    public function store(KeywordFrequencyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $resArr = AnalysisKeywordsFrequency::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param KeywordFrequencyDailyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Keyword Frequency"},
     *   path="/reputational/keywords-frequency-daily",
     *   summary="Keyword Frequency daily store",
     *   operationId="keywordFrequencyStoreDaily",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new keyword frequency daily",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/KeywordFrequencyDailyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/KeywordFrequencyDailyRequest")
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
    public function storeDaily(KeywordFrequencyDailyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisKeywordsFrequencyDaily::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param WeeklyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Keyword Frequency"},
     *   path="/reputational/keywords-frequency-weekly",
     *   summary="Keyword Frequency weekly store",
     *   operationId="keywordFrequencyStoreWeekly",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new keyword frequency weekly",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/WeeklyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/WeeklyRequest")
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
    public function storeWeekly(WeeklyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisKeywordsFrequencyWeekly::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param MonthlyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Keyword Frequency"},
     *   path="/reputational/keywords-frequency-monthly",
     *   summary="Keyword Frequency monthly store",
     *   operationId="keywordFrequencyStoreMonthly",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new keyword frequency monthly",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/MonthlyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/MonthlyRequest")
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
    public function storeMonthly(MonthlyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisKeywordsFrequencyMonthly::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param YearlyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Keyword Frequency"},
     *   path="/reputational/keywords-frequency-yearly",
     *   summary="Keyword Frequency yearly store",
     *   operationId="keywordFrequencyStoreYearly",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new keyword frequency yearly",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/YearlyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/YearlyRequest")
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
    public function storeYearly(YearlyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisKeywordsFrequencyYearly::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
