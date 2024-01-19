<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Events\CreatedCompany;
use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\CompaniesFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Enums\CompanySize;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Company;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CompaniesController extends BaseApiController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(new Company());

        $this->loadedRelations = [
            'parent:id,name',
            'questionnaires:id,company_id,questionnaire_type_id',
            'questionnaires.type:id,name',
            'business_sector:id,name',
            'businessSectorSecondary:id,name',
            'locations'
        ];
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Companies"},
     *   path="/companies",
     *   summary="Companies index",
     *   operationId="companiesIndex",
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
     *             @OA\Schema(ref="#/components/schemas/CompaniesResponse"),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     * )
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
     * @param CompaniesFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Companies"},
     *   path="/companies",
     *   summary="Companies store",
     *   operationId="companiesStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new companies",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/CompaniesFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/CompaniesFormRequest")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/CompaniesResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function store(CompaniesFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by_user_id'] = auth()->user()->id;
            $data['parent_id'] = $data['parent_id'] ?? null;

            $company = $this->resource::create($data);

            if ($company) {
                event(new CreatedCompany(auth()->user(), $company));
            }

            return $this->successResponse($company);
        } catch (\Exception | AccessDeniedHttpException $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param string $idEncrypted
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Companies"},
     *   path="/companies/{id}",
     *   summary="Companies show",
     *   operationId="companiesShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/CompaniesResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function show(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->resource = $this->resource->whereId(decryptValue($idEncrypted));
        $resource = $this->parseResponseData();
        return response()->json($this->parseResourceData($resource), 200);
    }

    /**
     * Display the specified resource.
     * @param string $vatNumber
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Companies"},
     *   path="/companies/vat_number/{vat_number}",
     *   summary="Companies vat number",
     *   operationId="companiesShowVatNumber",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/vat_number"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/CompaniesResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function showVatNumber(string $vatNumber): ResourceCollection|JsonResponse
    {
        $resource = $this->resource::whereRaw("LOWER(vat_number) = ?", strtolower($vatNumber));
        $resource = $this->parseResponseData();
        return response()->json($this->parseResourceData($resource), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param CompaniesFormRequest $request
     * @param string $company
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Companies"},
     *   path="/companies/{id}",
     *   summary="Companies update",
     *   operationId="companiesUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *     description="Update the specified companies",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/CompaniesFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/CompaniesFormRequest")
     *    ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/CompaniesResponse"),
     *         }
     *      ),
     *   ),
     *   )
     */
    public function update(CompaniesFormRequest $request, string $company): ResourceCollection|JsonResponse
    {
        try {
            $company = $this->resource->findOrfail(decryptValue($company));
            $data = $request->validated();
            $company->update($data);

            return $this->successResponse($company);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param CompaniesFormRequest $request
     * @param string $company
     * @return JsonResponse
     * @throws Exception
     * @OA\Delete(
     *   tags={"Companies"},
     *   path="/companies/{id}",
     *   summary="Companies destroy",
     *   operationId="companiesDestroy",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/CompaniesDeleteResponse"),
     *         }
     *      ),
     *   ),
     *  )
     */
    public function destroy(CompaniesFormRequest $request, string $company): JsonResponse
    {
        try {
            $company = $this->resource::find(decryptValue($company));
            if (!$company || !$company->exists()) {
                return abort(404, 'Company not found');
            }
            $company->delete();
            return $this->destroyResponse('company');
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Modify the resource data to be returned
     * @param object $resource
     * @return object
     */
    public function parseResourceData(object $resource): object
    {
        $companySize = CompanySize::casesArray();

        $resource->data = $resource->data->map(function ($item, $key) use ($companySize) {

            $item->size = isset($item->size) && isset($companySize[$item->size])
                ? $companySize[$item->size]
                : null;

            $item->parent = $item->parent ?? [];

            if (isset($item->business_sector)) {
                $sector = (array)$item->business_sector;
                unset($item->business_sector);
                $item->business_sectors = [];
                $item->business_sectors[] = array_merge($sector, ['main' => true]);
            }

            if (isset($item->business_sector_secondary)) {
                foreach ($item->business_sector_secondary as $key => $sector) {
                    $sector = (array)$sector;
                    unset($sector['pivot']);
                    $item->business_sectors[] = array_merge($sector, ['main' => false]);
                }
                unset($item->business_sector_secondary);
            }

            return $item;
        });

        return $resource;
    }
}
