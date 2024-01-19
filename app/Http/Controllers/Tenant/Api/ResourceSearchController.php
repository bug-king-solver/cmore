<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Resources\ResourceCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceSearchController extends BaseApiController
{
    /**
     * ResourceSearchController constructor.
     */
    public function __construct()
    {
        $this->paginate = 30;

        parent::__construct(
            $this->searchAble(request()->route()->parameter('type'))
        );
    }

    /**
     * Search for a resource
     * @param string $resource
     * @return object
     */
    public function searchAble(string $resource): object
    {
        /** return the resource model */
        $original = $resource;
        $resource = makeResourcAble($resource);

        /** double check if resource exists */
        if (class_exists($resource)) {
            return new $resource();
        }

        throw new Exception(__('Unknown search resource [' . $original . ']'), 404);
    }

    /**
     * Search for a resource
     * @param Request $request
     * @param string $type
     * @return JsonResponse|ResourceCollection
     * @OA\Get(
     *   tags={"Dynamic Search"},
     *   path="/search/{type}",
     *   summary="Search for a resource -  This is a dynamic search that will search for any resource",
     *   description="Search for a resource that is or is not present in the api endpoints.
     *      This is a dynamic search that will search for any resource (if exists ) in our software
     * ",
     *   operationId="search",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(
     *     name="type",
     *     in="path",
     *     description="The resource type to search for",
     *     required=true,
     *     @OA\Schema( type="string" )
     *    ),
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
     * )
     */
    public function searchResource(Request $request, string $type): JsonResponse|ResourceCollection
    {
        try {
            if (empty($type) || $type == ' ') {
                throw new Exception(__('Missing search type'), 422);
            }

            return $this->successResponse();
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
