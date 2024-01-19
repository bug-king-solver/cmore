<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\DataFormRequest;
use App\Http\Requests\Api\QuestionnaireFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Company;
use App\Models\Tenant\Data;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DataController extends BaseApiController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(new Data());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Data"},
     *   path="/data",
     *   summary="Data index - Lista all data`s ",
     *   operationId="dataIndex",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
     *   @OA\Parameter(ref="#/components/parameters/sortBy"),
     *   @OA\Parameter(ref="#/components/parameters/sortByDesc"),
     *   @OA\Parameter(ref="#/components/parameters/select"),
     *   @OA\Parameter(ref="#/components/parameters/search"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataResponse"),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function index(): ResourceCollection|JsonResponse
    {
        try {
            $resource = $this->parseResponseData();
            return response()->json($this->parseResourceData($resource), 200);
        } catch (Exception | AccessDeniedHttpException | QueryException $ex) {
            return $this->errorResponse($ex);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param DataFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Data"},
     *   path="/data",
     *   summary="Data store - create a new data entry",
     *   operationId="dataStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new data",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/DataFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/DataFormRequest")
     *    ),
     *    @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataResponse"),
     *         }
     *      ),
     *    ),
     *  )
     */
    public function store(DataFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $requestData = $request->validated();
            $requestData['reported_at'] = $request->get('reported_at_date') . ($request->get('reported_at_time') ?: '00:00');
            $data = $this->resource::create($requestData);
            return $this->successResponse();
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param Data $data
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Data"},
     *   path="/data/{id}",
     *   summary="Data show - See a specific data entry",
     *   operationId="dataShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *    @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataResponse"),
     *         }
     *      ),
     *    ),
     *  )
     */
    public function show(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->resource = $this->resource->whereId(decryptValue($idEncrypted));
        $resource = $this->parseResponseData();
        return response()->json($this->parseResourceData($resource), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param DataFormRequest $request
     * @param string $data
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Data"},
     *   path="/data/{id}",
     *   summary="Data update - Update a specific data entry",
     *   operationId="dataUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *     description="Update the data",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/DataFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/DataFormRequest")
     *   ),
     *    @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataResponse"),
     *         }
     *      ),
     *    ),
     *  )
     */
    public function update(DataFormRequest $request, string $data): ResourceCollection|JsonResponse
    {
        try {
            $data = $this->resource->findOrfail(decryptValue($data));
            $requestData = $request->validated();
            $requestData['reported_at'] = $requestData['reported_at_date'] ?: $data->reported_at->format('Y-m-d');
            if (!isset($requestData['reported_at_time'])) {
                $requestData['reported_at'] .= $data->reported_at->format('H:i');
            } else {
                $requestData['reported_at'] .= $requestData['reported_at_time'] . ':00';
            }

            $data->update($requestData);
            return $this->successResponse();
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param DataFormRequest $request - its not used, but it is required to pass the validation
     * @param string $data
     * @return JsonResponse
     * @OA\Delete(
     *   tags={"Data"},
     *   path="/data/{id}",
     *   summary="Data destroy - Delete a specific data entry",
     *   operationId="dataDestroy",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *    @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataDeleteResponse"),
     *         }
     *      ),
     *    ),
     *  )
     */
    public function destroy(DataFormRequest $request, string $data): JsonResponse
    {
        try {
            $data = $this->resource::find(decryptValue($data));
            if (!$data || !$data->exists()) {
                return abort(404, 'Data not found');
            }
            $data->delete();

            return $this->destroyResponse('data');
        } catch (\Exception $th) {
            return $this->errorResponse($th);
        }
    }


    /**
     * Display the specified resource.
     * @param string $vatNumber
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Data"},
     *   path="/data/vat_number/{vat_number}",
     *   summary="Indicators data by company - See all data entries for a specific company , with the last indicators reported",
     *   operationId="dataIndicators",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/vat_number"),
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataIndicatorsResponse"),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function dataIndicators(string $vatNumber): ResourceCollection|JsonResponse
    {
        $this->loadedRelations = [
            'indicator'
        ];

        $this->whereHasRelations = [
            'company' => function ($query) use ($vatNumber) {
                $query->where('vat_number', $vatNumber);
            },
        ];

        $this->groupColumns = ['data.indicator_id', 'data.reported_at', 'data.id'];
        $this->resource = $this->resource->distinct('data.indicator_id');
        $resource = $this->parseResponseData();

        $resource->data = $resource->data->sortBy('reported_at')->map(function ($item) {
            return [
                'id' => $item->id,
                'indicator_id' => $item->indicator->id,
                'indicator_id_external' => $item->indicator_id_external,
                'indicator' => parseStringToArray($item->indicator->name),
                'value' => (string)$item->value,
                'reported_at' => $item->reported_at ? carbon()->parse($item->reported_at) : null,
            ];
        })->unique("indicator_id")->values()->all();

        return response()->json($resource, 200);
    }

    /**
     * Display the specified resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Data"},
     *   path="/data/with/details",
     *   summary="Indicators data by company - See all data entries for a specific company , with the last indicators reported",
     *   operationId="dataWithDetails",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/DataWithDetailsResponse"),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function dataWithDetails(): ResourceCollection|JsonResponse
    {
        $this->loadedRelations = [
            'indicator',
            'company',
        ];

        $this->whereHasRelations = [
            'questionnaire' => fn ($query) => $query,
        ];

        $resource = $this->parseResponseData();
        $resource->data = $resource->data->groupBy("company_id");

        $response = [];
        $resource->data->sortBy('reported_at')->map(function ($items, $companyId) use (&$response) {

            $response[$companyId]['company'] = $items[0]->company->name;
            $response[$companyId]['reported_at'] = $items[0]->reported_at;

            foreach ($items as $data) {
                $indicatorName = parseStringToArray($data->indicator->name);
                $response[$companyId][$indicatorName->en] = (string)$data->value;
            }
        })->values()->all();

        $resource->data = collect($response)->values()->all();

        // $tempArray['company'] = $company->name;
        // $tempArray['reported_at'] = $latestQuestionnaire->submitted_at;

        // collect($resource['data'])->map(function ($item) use (&$tempArray) {
        //     if (isset($item['indicator'])) {
        //         $tempArray[$item['indicator']['name']['en']] = (string)$item['value'];
        //     }
        // });
        return response()->json($resource, 200);
    }

    /**
     * Modify the resource data to be returned
     * @param object $resource
     * @return object
     */
    public function parseResourceData(object $resource): object
    {
        $resource->data = $resource->data->transform(function ($item, $key) {
            $item->value = (string) $item->value;
            return $item;
        });
        return $resource;
    }
}
