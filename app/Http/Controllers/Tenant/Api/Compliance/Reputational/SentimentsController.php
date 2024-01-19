<?php

namespace App\Http\Controllers\Tenant\Api\Compliance\Reputational;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\Compliance\Reputational\KeywordFrequencyRequest;
use App\Http\Requests\Api\Compliance\Reputational\MonthlyRequest;
use App\Http\Requests\Api\Compliance\Reputational\SentimentsDailyRequest;
use App\Http\Requests\Api\Compliance\Reputational\WeeklyRequest;
use App\Http\Requests\Api\Compliance\Reputational\YearlyRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentiments;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsMonthly;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsWeekly;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsYearly;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SentimentsController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(new AnalysisSentiments());
    }

    /**
     * Store a newly created resource in storage.
     * @param KeywordFrequencyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Sentiments"},
     *   path="/reputational/sentiments",
     *   summary="Sentiments store",
     *   operationId="sentimentsStore",
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
            $resArr = AnalysisSentiments::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param SentimentsDailyRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Reputation Analysis Sentiments"},
     *   path="/reputational/sentiments-daily",
     *   summary="Sentiments daily store",
     *   operationId="sentimentsStoreDaily",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new emotion daily",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/SentimentsDailyRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/SentimentsDailyRequest")
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
    public function storeDaily(SentimentsDailyRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisSentimentsDaily::create($data);

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
     *   tags={"Reputation Analysis Sentiments"},
     *   path="/reputational/sentiments-weekly",
     *   summary="Sentiments weekly store",
     *   operationId="sentimentsStoreWeekly",
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
            $resArr = AnalysisSentimentsWeekly::create($data);

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
     *   tags={"Reputation Analysis Sentiments"},
     *   path="/reputational/sentiments-monthly",
     *   summary="Sentiments monthly store",
     *   operationId="sentimentsStoreMonthly",
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
            $resArr = AnalysisSentimentsMonthly::create($data);

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
     *   tags={"Reputation Analysis Sentiments"},
     *   path="/reputational/sentiments-yearly",
     *   summary="Sentiments yearly store",
     *   operationId="sentimentsStoreYearly",
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
            $data['data'] = json_decode($data['data'], true);
            $resArr = AnalysisSentimentsYearly::create($data);

            return $this->successResponse($resArr);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
