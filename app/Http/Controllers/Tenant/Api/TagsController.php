<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\TagsFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Tag;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TagsController extends BaseApiController
{
    /**
     * TagsController constructor.
     */
    public function __construct()
    {
        parent::__construct(new Tag());
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Tags"},
     *   path="/tags",
     *   summary="Tags index",
     *   operationId="tagsIndex",
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
     *             @OA\Schema(ref="#/components/schemas/TagsResponse"),
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
     * @param TagsFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Tags"},
     *   path="/tags",
     *   summary="Tags store",
     *   operationId="tagsStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new tag",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/TagsFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/TagsFormRequest")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TagsResponse") } ),
     *   ),
     * )
     */
    public function store(TagsFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();
            $tag = $this->resource::create($data);
            return $this->successResponse($tag);
        } catch (\Exception | AccessDeniedHttpException $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param Tag $tag
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Tags"},
     *   path="/tags/{id}",
     *   summary="Tags show",
     *   operationId="tagsShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TagsResponse") } ),
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
     * @param TagsFormRequest $request
     * @param string $tag
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Tags"},
     *   path="/tags/{id}",
     *   summary="Tags update",
     *   operationId="tagsUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *     description="Update the specified tag",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/TagsFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/TagsFormRequest")
     *    ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TagsResponse") } ),
     *   ),
     * )
     */
    public function update(TagsFormRequest $request, string $tag): ResourceCollection|JsonResponse
    {
        try {
            $tag = $this->resource->findOrfail(decryptValue($tag));
            $tag->update($request->validated());

            return $this->successResponse($tag);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param TagsFormRequest $request
     * @param string $tag
     * @return JsonResponse
     * @throws Exception
     * @OA\Delete(
     *   tags={"Tags"},
     *   path="/tags/{id}",
     *   summary="Tags destroy",
     *   operationId="tagsDestroy",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *      @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TagsDeleteResponse") } ),
     *   ),
     * )
     */
    public function destroy(TagsFormRequest $request, string $tag): JsonResponse
    {
        try {
            $tag = $this->resource->findOrfail(decryptValue($tag));
            $tag->delete();
            return $this->destroyResponse('tag');
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
