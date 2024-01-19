<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiScoutResourceTrait;
use App\Http\Resources\ResourceCollection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class BaseApiController extends Controller
{
    use ApiScoutResourceTrait;

    public int $paginate = 10;

    protected object $resource;

    /** @var mixed $loadedRelations - Relations used to load the resource */
    protected mixed $loadedRelations = [];

    /** @var mixed $whereHasRelations - Relations used to filter the resource */
    protected mixed $whereHasRelations = [];

    /** @var mixed $groupColumns - Columns to be grouped from the response */
    protected mixed $groupColumns = [];

    /** @var mixed $hiddenColumns - Columns to be hidden from the response */
    protected mixed $hiddenColumns = [];

    /**
     * BaseApiController constructor.
     * @param object $resource
     */
    public function __construct(object $resource)
    {
        $this->resource = $resource;
        $this->table = $resource->getTable();
    }


    /**
     * Fire the success response
     * @param array $relations - Relations used to load the resource
     * @return ResourceCollection|JsonResponse
     */
    public function successResponse(): ResourceCollection|JsonResponse
    {
        return new ResourceCollection(
            $this->prepareResponse(),
            $this->hiddenColumns
        );
    }

    /**
     * Fire the destroy response (success) method
     * @param string $resourceName - the resource name to be returned
     * @return JsonResponse
     */
    public function destroyResponse(null|string $resourceName): JsonResponse
    {
        return response()->json([
            'error' => false,
            'message' => __('The :resource was successfully deleted ❗', ['resource' => $resourceName ?? 'resource']),
            'data' => [],
        ], 200);
    }

    /**
     * Fire the error response
     * @param Exception $ex - the exception to be returned
     * @return JsonResponse
     */
    public function errorResponse(Exception $ex): JsonResponse
    {
        $response = [
            'error' => __('An error occurred while processing your request.'),
            'message' => $ex->getMessage(),
            'data' => [],
        ];

        /**
         * If the app is in debug mode, return the file, line and trace
         */
        if (config('app.debug')) {
            $response['data'] = [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => $ex->getTrace(),
            ];
        }

        $code = 500;
        if (method_exists($ex, 'getStatusCode')) {
            $code = $ex->getStatusCode();
        } elseif (method_exists($ex, 'getCode')) {
            $code = $ex->getCode();
        }

        return response()->json($response, $this->getErrorCode($code));
    }

    /**
     * Modify the questionnaire data to be returned
     * @return mixed
     */
    public function parseResponseData()
    {
        try {
            $resource = ResourceCollection::make(
                $this->prepareResponse()
            )->toResponse(request());

            $resource = $resource->getData();
            if (!is_object($resource)) {
                $resource = new \stdClass();
                $resource->data = [];
            }

            if (!is_array($resource->data)) {
                $data  = (object)[$resource->data ?? []];
                $resource = new \stdClass();
                $resource->data = $data;
            }

            $resource->data = collect($resource->data);
        } catch (Exception | QueryException $ex) {
            throw $ex;
        }

        return $resource;
    }

    /**
     * Get the error code and validate if its a valida HTTP code
     * @param string $code - the error code
     * @return int
     */
    public function getErrorCode(string $code): int
    {
        return array_key_exists($code, \Illuminate\Http\Response::$statusTexts)
            ? $code
            : 500;
    }

    /**
     * Modify the resource data to be returned
     * @param object $resource
     * @return object
     */
    public function parseResourceData(object $resource): object
    {
        return $resource;
    }
}
