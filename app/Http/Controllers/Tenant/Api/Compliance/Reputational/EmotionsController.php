<?php

namespace App\Http\Controllers\Tenant\Api\Compliance\Reputational;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\Compliance\Reputational\EmotionsDailyRequest;
use App\Http\Requests\Api\Compliance\Reputational\KeywordFrequencyRequest;
use App\Http\Requests\Api\Compliance\Reputational\MonthlyRequest;
use App\Http\Requests\Api\Compliance\Reputational\WeeklyRequest;
use App\Http\Requests\Api\Compliance\Reputational\YearlyRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotions;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsMonthly;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsWeekly;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsYearly;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EmotionsController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(new AnalysisEmotions());
    }

    /**
     * Store a newly created resource in storage.
     * @param KeywordFrequencyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Emotions"},
     *   path="/reputational/emotions",
     *   summary="Emotions store",
     *   operationId="emotionsStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new emotion",
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
            $resArr = AnalysisEmotions::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param EmotionsDailyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Emotions"},
     *   path="/reputational/emotions-daily",
     *   summary="Emotions daily store",
     *   operationId="emotionsStoreDaily",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new emotion daily",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/EmotionsDailyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/EmotionsDailyRequest")
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
    public function storeDaily(EmotionsDailyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisEmotionsDaily::create($data);

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
     *   tags={"Reputation Analysis Emotions"},
     *   path="/reputational/emotions-weekly",
     *   summary="Emotions weekly store",
     *   operationId="emotionsStoreWeekly",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new emotion weekly",
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
            $resArr = AnalysisEmotionsWeekly::create($data);

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
     *   tags={"Reputation Analysis Emotions"},
     *   path="/reputational/emotions-monthly",
     *   summary="Emotions monthly store",
     *   operationId="emotionsStoreMonthly",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new emotion monthly",
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
            $resArr = AnalysisEmotionsMonthly::create($data);

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
     *   tags={"Reputation Analysis Emotions"},
     *   path="/reputational/emotions-yearly",
     *   summary="Emotions yearly store",
     *   operationId="emotionsStoreYearly",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new emotion yearly",
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
            $resArr = AnalysisEmotionsYearly::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
