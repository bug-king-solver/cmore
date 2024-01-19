<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\QuestionnaireFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Enums\PhysicalRisksRelevanceEnum;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Sdg;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Intl\Countries;

class QuestionnaireController extends BaseApiController
{
    /**
     * DataController constructor.
     */
    public function __construct()
    {
        parent::__construct(new Questionnaire());

        $this->loadedRelations = [
            'company:id,name,vat_number',
            'company.locations',
            'type:id,name',
            'taxonomy.activities',
            'taxonomy.activities.sector:id,code,name,description,nace',
            'physicalRisks.location',
            'physicalRisks.businessSector:id,name',
        ];
    }


    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Questionnaires"},
     *   path="/questionnaires",
     *   summary="Questionnaires index",
     *   operationId="questionnairesIndex",
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
     *             @OA\Schema(ref="#/components/schemas/QuestionnaireResponse"),
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
     * @param QuestionnaireFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Questionnaires"},
     *   path="/questionnaires",
     *   summary="Questionnaires store",
     *   operationId="questionnairesStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new questionnaire",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/QuestionnaireFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/QuestionnaireFormRequest")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={ @OA\Schema(ref="#/components/schemas/QuestionnaireResponse") } ),
     *   ),
     *  )
     */
    public function store(QuestionnaireFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by_user_id'] = auth()->user()->id;
            $data['questionnaire_type_id'] = $data['type_id'];

            $questionnaire = $this->resource::create($data);

            $this->resource = $this->resource->whereId($questionnaire->id);
            $resource = $this->parseResponseData();
            return response()->json($this->parseResourceData($resource), 200);
        } catch (\Exception | AccessDeniedHttpException | QueryException $ex) {
            return $this->errorResponse($ex);
        }
    }

    /**
     * Display the specified resource.
     * @param string $idEncrypted
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Questionnaires"},
     *   path="/questionnaires/{id}",
     *   summary="Questionnaires show",
     *   operationId="questionnairesShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={ @OA\Schema(ref="#/components/schemas/QuestionnaireResponse") } ),
     *   ),
     *   )
     */
    public function show(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->resource = $this->resource->whereId(decryptValue($idEncrypted));
        $resource = $this->parseResponseData();
        return response()->json($this->parseResourceData($resource), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param QuestionnaireFormRequest $request
     * @param string $questionnaire
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Questionnaires"},
     *   path="/questionnaires/{id}",
     *   summary="Questionnaires update",
     *   operationId="questionnairesUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *     description="Update the specified questionnaire",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/QuestionnaireFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/QuestionnaireFormRequest")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={ @OA\Schema(ref="#/components/schemas/QuestionnaireResponse") } ),
     *   ),
     *  )
     */
    public function update(QuestionnaireFormRequest $request, string $questionnaire): ResourceCollection|JsonResponse
    {
        try {
            $questionnaire = $this->resource->findOrfail(decryptValue($questionnaire));
            $questionnaire->update($request->validated());
            return $this->successResponse($questionnaire);
        } catch (\Exception | AccessDeniedHttpException | QueryException $ex) {
            return $this->errorResponse($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param QuestionnaireFormRequest $request  - its here to validate the token permissions
     * @param string $questionnaire
     * @return JsonResponse
     * @OA\Delete(
     *   tags={"Questionnaires"},
     *   path="/questionnaires/{id}",
     *   summary="Questionnaires delete",
     *   operationId="questionnairesDelete",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={ @OA\Schema(ref="#/components/schemas/QuestionnaireDeleteResponse") }
     *      ),
     *   ),
     *  )
     */
    public function destroy(QuestionnaireFormRequest $request, string $questionnaire): JsonResponse
    {
        try {
            $questionnaire = $this->resource->findOrfail(decryptValue($questionnaire));
            $questionnaire->delete();

            return $this->destroyResponse('questionnaire');
        } catch (\Exception | AccessDeniedHttpException | QueryException $ex) {
            return $this->errorResponse($ex);
        }
    }

    /**
     * Display the specified resource.
     * @param Questionnaire $questionnaire
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Questionnaires"},
     *   path="/questionnaires/{id}/data",
     *   summary="Questionnaire data",
     *   operationId="questionnaireData",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/QuestionnaireDataResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function data(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->loadedRelations = [
            'reportedData.indicator'
        ];

        $this->whereHasRelations = [
            'reportedData' => fn ($query) => $query,
        ];

        $this->resource = $this->resource->whereId(decryptValue($idEncrypted));


        $resource = $this->parseResponseData();

        $resource = parent::parseResourceData($resource);

        $resource->data = collect($resource->data[0]->reported_data)->map(function ($item) {
            return [
                'id' => $item->id,
                'indicator_id' => $item->indicator_id,
                'indicator_name' => $item->indicator->name,
                'answer' => $item->value,
                'reported_at' => $item->reported_at,
            ];
        });


        return response()->json($resource, 200);
    }

    /**
     * Modify the resource data to be returned
     * @param object $resource
     * @return object
     */
    public function parseResourceData(object $resource): object
    {
        $optionSimples = Simple::withoutGlobalScopes()
            ->orderBy('id')->pluck('label', 'id')->toArray();

        $countries = Countries::getAlpha3Names();

        $resource->data = collect($resource->data)->map(function ($item, $key) use ($optionSimples, $countries) {

            if (isset($item->questions)) {
                $questionsIds = collect(parseStringToArray($item->questions))
                    ->map(function ($item, $key) {
                        return decryptValue($item->id);
                    })
                    ->toArray();

                $questions = Question::whereIn('id', $questionsIds)
                    ->where("questionnaire_type_id", decryptValue($item->questionnaire_type_id))
                    ->with('questionOptions')
                    ->get();

                $answers = Answer::where('questionnaire_id', decryptValue($item->id))
                    ->whereIn('question_id', $questionsIds)
                    ->pluck('value', 'question_id');


                $item->questions = collect($questions)->map(function ($item, $key) use ($answers, $optionSimples) {

                    $answer = parseStringToArray($answers[$item->id] ?? '');

                    if (is_array($answer)) {
                        $answerInfo = [];
                        foreach ($answer as $id => $a) {
                            $tempAnswerInfoArr = [];
                            if (isset($optionSimples[$id]) && $optionSimples[$id] !== "") {
                                $tempAnswerInfoArr['key'] = $optionSimples[$id];
                                $tempAnswerInfoArr['value'] = $a;
                                $answerInfo['answerInfo'][] = $tempAnswerInfoArr;
                                $answer = $answerInfo;
                            } else {
                                $tempAnswerInfoArr['text'] = $item->answer_type == 'binary'
                                    ? collect($item->question_options)->where('option.value', $a)->first()->option->label->en ?? $a
                                    : $a;
                                $answer = $tempAnswerInfoArr;
                            }
                        }
                    }

                    return [
                        'id' => encryptValue($item->id),
                        'id_external' => encryptValue($item->id),
                        'key' => encryptValue($item->id),
                        'category_id' => encryptValue($item->category_id),
                        'category_id_external' => encryptMd5($item->category_id),
                        'text' => $item->getTranslations('description'),
                        'answer' => $answer,
                        'not_reported' => false,
                        'not_applicable' => false,
                    ];
                })->toArray();
            }

            if (isset($item->categories)) {
                $item->categories = collect($item->categories)->map(function ($item, $key) {
                    $item->name = parseStringToArray($item->name ?? '');
                    unset($item->pivot);
                    return $item;
                })->toArray();
            }

            if (isset($item->initiatives)) {
                $initiatives = Initiative::whereIn('id', $item->initiatives ?? [])->get();

                $item->initiatives = collect($initiatives)->map(function ($item, $key) {
                    return [
                        'id' => encryptValue($item->id),
                        'id_external' => encryptMd5($item->id),
                        'name' => $item->getTranslations('name'),
                    ];
                })->toArray();
            }

            if (isset($item->sdgs)) {
                $sdgs = Sdg::whereIn('id', $item->sdgs ?? [])->get();
                $item->sdgs = collect($sdgs)->map(function ($item, $key) {
                    return [
                        'id' => encryptValue($item->id),
                        'id_external' => encryptMd5($item->id),
                        'name' => $item->getTranslations('name'),
                    ];
                })->toArray();
            }

            if (isset($item->physical_risks)) {
                $item->physical_risks = collect($item->physical_risks)->map(function ($item, $key) use ($countries) {
                    $item = (array) $item;

                    $relevanceLabel = PhysicalRisksRelevanceEnum::from($item['relevant'])->label();

                    $hazards = collect($item['hazards'])->map(function ($item, $key) {
                        $item = (array) $item;
                        $item = array_merge($item, [
                            'name' => [
                                'en' => $item['name'],
                                'pt-PT' => $item['name'],
                                'pt-BR' => $item['name'],
                                'es' => $item['name'],
                                'fr' => $item['name'],
                            ],
                            'level' => [
                                'en' => $item['risk'],
                                'pt-PT' => $item['risk'],
                                'pt-BR' => $item['risk'],
                                'es' => $item['risk'],
                                'fr' => $item['risk'],
                            ],
                            'has_continuity_plan' => $item['has_continuity_plan'] ?? 0,
                            'continuity_plan_description' => $item['continuity_plan_description'] ?? '',
                            'has_contingency_plan' => $item['has_contingency_plan'] ?? 0,
                            'contingency_plan_description' => $item['contingency_plan_description'] ?? '',
                        ]);

                        unset($item['risk']);
                        unset($item['name_slug']);
                        unset($item['enabled']);
                        unset($item['risk_slug']);
                        unset($item['audits']);

                        return $item;
                    })->toArray();

                    $location = $item['location'] ?? $item['addresses'] ?? [];
                    $location = (array) $location;

                    unset($location['company_id']);
                    unset($location['country_code']);
                    unset($location['region_code']);
                    unset($location['city_code']);
                    unset($location['created_at']);
                    unset($location['updated_at']);
                    unset($location['company_id_external']);

                    $location['country_iso'] = array_filter($countries, function ($item) use ($location) {
                        return $item === ($location['country'] ?? '');
                    }, ARRAY_FILTER_USE_BOTH);

                    $location['country_iso'] = array_keys($location['country_iso'])[0] ?? null;

                    $location['country'] = $location['country'];
                    $location['region'] = $location['region'];
                    $location['city'] = $location['city'];

                    unset($location['deleted_at']);
                    unset($location['location']);

                    $item = array_merge($item, [
                        'hazards' => $hazards,
                        "relevance" => [
                            'level' => [
                                'en' => $relevanceLabel,
                                'pt-PT' => $relevanceLabel,
                                'pt-BR' => $relevanceLabel,
                                'es' => $relevanceLabel,
                                'fr' => $relevanceLabel,
                            ],
                            'justification' => $item['note'],
                        ],
                        'location' => $location,
                    ]);


                    unset($item['id']);
                    unset($item['description']);
                    unset($item['business_sector_id']);
                    unset($item['company_address_id']);
                    unset($item['id_external']);
                    unset($item['created_by_user_id_external']);
                    unset($item['questionnaire_id_external']);
                    unset($item['business_sector_id_external']);
                    unset($item['company_address_id_external']);
                    unset($item['created_by_user_id']);
                    unset($item['questionnaire_id']);

                    unset($item['note']);
                    unset($item['relevant']);
                    unset($item['completed']);
                    unset($item['completed_at']);
                    unset($item['deleted_at']);
                    unset($item['created_at']);
                    unset($item['updated_at']);
                    unset($item['addresses']);
                    return $item;
                })->toArray();
            }

            if (isset($item->taxonomy)) {
                $taxonomy = (array) $item->taxonomy;

                $safeguards = (array) $taxonomy['safeguard'];
                $safeguards['questions'] = collect($safeguards['questions'])->map(function ($item) {
                    $item = (array) $item;
                    $item['answer'] = $item['answered_value'];
                    unset($item['answered']);
                    unset($item['answered_value']);
                    unset($item['options']);
                    return $item;
                })->toArray();
                unset($safeguards['percentage']);
                unset($safeguards['arrayPosition']);
                $taxonomy['safeguards'] = $safeguards;

                $taxonomy['activities'] = collect($item->taxonomy->activities)->map(function ($activity) {
                    $contribute = parseStringToArray($activity->contribute);
                    $objectives = [];
                    foreach ($contribute->data as $cs) {
                        $questions = [];
                        foreach ($cs->questions as $question) {
                            $question = (array) $question;
                            $question['answer'] = $question['answered_value'] ?? null;
                            unset($question['sector']);
                            unset($question['options']);
                            unset($question['activity']);
                            unset($question['answered']);
                            unset($question['objective']);
                            unset($question['activity_code']);
                            unset($question['answered_value']);
                            unset($question['activity_description']);
                            $questions[] = $question;
                        }
                        $cs = (array) $cs;
                        $cs['questions'] = $questions;
                        unset($cs['imported']);
                        unset($cs['arrayPosition']);
                        $objectives[] = $cs;
                    }
                    $contribute = (array) $contribute;
                    $contribute['objectives'] = $objectives;
                    unset($contribute['data']);

                    $dnsh = parseStringToArray($activity->dnsh);
                    $objectives = [];
                    foreach ($dnsh->data as $item) {
                        $questions = [];
                        foreach ($item->questions as $question) {
                            $question = (array) $question;
                            $question['answer'] = $question['answered_value'] ?? null;
                            unset($question['sector']);
                            unset($question['options']);
                            unset($question['activity']);
                            unset($question['answered']);
                            unset($question['objective']);
                            unset($question['activity_code']);
                            unset($question['answered_value']);
                            unset($question['activity_description']);
                            $questions[] = $question;
                        }
                        $item = (array) $item;
                        $item['questions'] = $questions;
                        unset($item['imported']);
                        unset($item['arrayPosition']);
                        $objectives[] = $item;
                    }
                    $dnsh = (array) $dnsh;
                    $dnsh['objectives'] = $objectives;
                    unset($dnsh['data']);

                    $activity = (array) $activity;
                    $sector = (array) $activity['sector'];
                    $description = $sector['description'];
                    $sector['description'] = [
                        'en' => $description,
                        'pt-PT' => $description,
                        'pt-BR' => $description,
                        'es' => $description,
                        'fr' => $description,
                    ];
                    unset($sector['nace']);

                    $activity['contribute'] = $contribute;
                    $activity['dnsh'] = $dnsh;
                    $activity['sector'] = $sector;
                    unset($activity['taxonomy_id']);
                    unset($activity['business_item_id']);
                    unset($activity['created_at']);
                    unset($activity['updated_at']);
                    unset($activity['deleted_at']);
                    unset($activity['taxonomy_id_external']);
                    unset($activity['business_activities_id']);
                    unset($activity['business_activities_id_external']);
                    return $activity;
                })->toArray();

                unset($taxonomy['safeguard']);
                unset($taxonomy['id']);
                unset($taxonomy['imported_file_url']);
                unset($taxonomy['created_at']);
                unset($taxonomy['updated_at']);
                unset($taxonomy['questionnaire_id']);
                unset($taxonomy['completed']);
                unset($taxonomy['completed_at']);
                unset($taxonomy['started_at']);
                unset($taxonomy['deleted_at']);
                unset($taxonomy['id_external']);
                unset($taxonomy['questionnaire_id_external']);


                $item->taxonomy = $taxonomy;
            }

            return $item;
        });

        return $resource;
    }
}
