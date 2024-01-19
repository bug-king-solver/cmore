<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\RolesFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Role;
use Exception;
use Illuminate\Http\JsonResponse;

class RolesController extends BaseApiController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct(new Role());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Roles"},
     *   path="/roles",
     *   summary="Roles index - List of ACL roles",
     *   operationId="rolesIndex",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/paginate"),
     *   @OA\Parameter(ref="#/components/parameters/page"),
     *   @OA\Parameter(ref="#/components/parameters/sortBy"),
     *   @OA\Parameter(ref="#/components/parameters/sortByDesc"),
     *   @OA\Parameter(ref="#/components/parameters/select"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/RolesResponse"),
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
     * @param RolesFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Roles"},
     *   path="/roles",
     *   summary="Roles store",
     *   operationId="rolesStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new role",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/RolesFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/RolesFormRequest")
     *    ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RolesResponse") } ),
     *   ),
     * )
     */
    public function store(RolesFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            /** Default guard name. Needs to be set to 'web' because of the guard from api request is 'sanctum'             */
            if (!$request->has('guard_name')) {
                $data['guard_name'] = 'web';
            }

            $role = $this->resource::create($data);
            /**
             * Sync permissions and run a check to default roles
             */
            $this->afterSave($request, $role);

            return $this->successResponse($role);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param Role $role
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Roles"},
     *   path="/roles/{id}",
     *   summary="Roles show",
     *   operationId="rolesShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RolesResponse") } ),
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
     * Update the specified resource in storage.
     * @param RolesFormRequest $request
     * @param string $role
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Roles"},
     *   path="/roles/{id}",
     *   summary="Roles update",
     *   operationId="rolesUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *     description="Update a role",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/RolesFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/RolesFormRequest")
     *    ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RolesResponse") } ),
     *   ),
     * )
     */
    public function update(RolesFormRequest $request, string $role): ResourceCollection|JsonResponse
    {
        try {
            $role = $this->resource->findOrfail(decryptValue($role));

            if (strtolower($role->name) == 'super admin') {
                abort(403, __('You can not edit the super admin role.'));
            }

            $role->update($request->validated());

            $this->afterSave($request, $role);

            return $this->successResponse($role);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param RolesFormRequest $request - its here to validate the token permissions
     * @param string $role
     * @return JsonResponse
     * @OA\Delete(
     *   tags={"Roles"},
     *   path="/roles/{id}",
     *   summary="Roles destroy",
     *   operationId="rolesDestroy",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/RolesDeleteResponse") } ),
     *   ),
     * )
     */
    public function destroy(RolesFormRequest $request, string $role): JsonResponse
    {
        try {
            $role = $this->resource->findOrfail(decryptValue($role));
            if (strtolower($role->name) == 'super admin') {
                abort(403, __('You can not edit the super admin role.'));
            }
            $role->delete();

            return $this->destroyResponse('role');
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Sync permissions and run a check to default roles
     */
    public function afterSave($request, $role): void
    {
        if ($role && $request->has('permissions')) {
            $role->syncPermissions($request->get('permissions', []));
        }

        // Only one default role
        if ($request->get('default')) {
            $this->resource->where('default', true)
                ->where('id', '!=', $role->id)
                ->update(['default' => false]);
        }
    }
}
