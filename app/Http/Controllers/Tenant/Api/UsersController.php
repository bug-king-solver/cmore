<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\UsersFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UsersController extends BaseApiController
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * @OA\Get(
     *   tags={"Users"},
     *   path="/users",
     *   summary="Users index",
     *   operationId="usersIndex",
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
     *             @OA\Schema(ref="#/components/schemas/UsersResponse"),
     *             @OA\Schema(ref="#/components/schemas/DefaultMetaResponse"),
     *         }
     *      ),
     *   ),
     * )
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
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
     * @OA\Post(
     *   tags={"Users"},
     *   path="/users",
     *   summary="Users store",
     *   operationId="usersStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new user",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/UsersFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/UsersFormRequest")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/UsersResponse") } ),
     *   ),
     * )
     * Store a newly created resource in storage.
     * @param UsersFormRequest $request
     * @return ResourceCollection|JsonResponse
     */
    public function store(UsersFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            if (!$request->has('system')) {
                $data['system'] = false;
            }

            $user = $this->resource::create($data);

            return $this->successResponse($user);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param User $user
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Users"},
     *   path="/users/{id}",
     *   summary="Users show",
     *   operationId="usersShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/UsersResponse") } ),
     *   ),
     * )
     */
    public function show(string $idEncrypted): ResourceCollection|JsonResponse
    {
        $this->resource = $this->resource->whereId(decryptValue($idEncrypted));
        $resource = $this->parseResponseData();
        return response()->json($this->parseResourceData($resource), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param UsersFormRequest $request
     * @param string $user
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Users"},
     *   path="/users/{id}",
     *   summary="Users update",
     *   operationId="usersUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *      description="Update user",
     *      required=true,
     *      @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(ref="#/components/schemas/UsersFormRequest")
     *      ),
     *      @OA\JsonContent(ref="#/components/schemas/UsersFormRequest")
     *    ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/UsersResponse") } ),
     *   ),
     * )
     */
    public function update(UsersFormRequest $request, string $user): ResourceCollection|JsonResponse
    {
        try {
            $user = $this->resource->findOrfail(decryptValue($user));
            $user->update($request->validated());

            return $this->successResponse($user);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $user
     * @return ResourceCollection|JsonResponse
     * @throws Exception
     * @OA\Delete(
     *   path="/users/{id}",
     *   tags={"Users"},
     *   summary="Delete user",
     *   description="Delete user",
     *   operationId="deleteUser",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/UsersDeleteResponse") } ),
     *   ),
     * )
     */
    public function destroy(UsersFormRequest $request, string $user)
    {
        try {
            $user = $this->resource->findOrfail(decryptValue($user));
            if ($user->system) {
                abort(403, __('You can not delete an internal user.'));
            }
            $user->delete();

            return $this->destroyResponse('user');
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * @OA\Get(
     *   path="/logged",
     *   tags={"Users"},
     *   summary="Get logged user",
     *   operationId="loggedUser",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *   ),
     *  )
     */
    public function loggedUser(): ResourceCollection|JsonResponse
    {
        return $this->successResponse(auth()->user());
    }
}
