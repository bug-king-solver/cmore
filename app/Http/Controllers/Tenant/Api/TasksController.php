<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\Api\BaseApiController;
use App\Http\Requests\Api\TasksFormRequest;
use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TasksController extends BaseApiController
{
    /**
     * TasksController constructor.
     */
    public function __construct()
    {
        parent::__construct(new Task());

        $this->loadedRelations = [
            'checklist',
            'owner:id,name',
            'taskables'
        ];
    }

    /**
     * Display a listing of the resource.
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Tasks"},
     *   path="/tasks",
     *   summary="Tasks index",
     *   operationId="tasksIndex",
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
     *             @OA\Schema(ref="#/components/schemas/TasksResponse"),
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
     * @param TasksFormRequest $request
     * @return ResourceCollection|JsonResponse
     * @OA\Post(
     *   tags={"Tasks"},
     *   path="/tasks",
     *   summary="Tasks store",
     *   operationId="tasksStore",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\RequestBody(
     *     description="Create a new task",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/TasksFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/TasksFormRequest")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TasksResponse") } ),
     *   ),
     * )
     */
    public function store(TasksFormRequest $request): ResourceCollection|JsonResponse
    {
        try {
            $data = $request->validated();

            $data['created_by_user_id'] = auth()->user()->id;

            $resource = makeResourcAble($data['entity']);
            $model = new $resource();
            $model = $model::find(decryptValue($data['taskableId']));

            if (!$model) {
                abort(422, __('Taskable model not found'));
            }

            $task = $this->resource::create($data);
            $model->tasks()->save($task);

            if ($task) {
                foreach ($data['checklist'] as $key => $value) {
                    if ($value != '') {
                        $task->checklist()->create([
                            'name' => $value['name'],
                            'completed' => $value['completed'],
                        ]);
                    }
                }
            }

            return $this->successResponse($task);
        } catch (\Exception | AccessDeniedHttpException $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     * @param Task $task
     * @return ResourceCollection|JsonResponse
     * @OA\Get(
     *   tags={"Tasks"},
     *   path="/tasks/{id}",
     *   summary="Tasks show",
     *   operationId="tasksShow",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TasksResponse") } ),
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
     * @param TasksFormRequest $request
     * @param string $task
     * @return ResourceCollection|JsonResponse
     * @OA\Put(
     *   tags={"Tasks"},
     *   path="/tasks/{id}",
     *   summary="Tasks update",
     *   operationId="tasksUpdate",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\RequestBody(
     *     description="Update the specified task in storage",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(ref="#/components/schemas/TasksFormRequest")
     *     ),
     *     @OA\JsonContent(ref="#/components/schemas/TasksFormRequest")
     *    ),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TasksResponse") } ),
     *   ),
     * )
     */
    public function update(TasksFormRequest $request, string $task): ResourceCollection|JsonResponse
    {
        try {
            $task = $this->resource->findOrfail(decryptValue($task));
            $data = $request->validated();

            if (isset($data['entity'])) {
                $resource = makeResourcAble($data['entity']);
                $model = new $resource();
                $model = $model::find(decryptValue($data['taskableId']));
                if (!$model) {
                    abort(422, __('Taskable model not found'));
                }

                if ($task->taskables->taskables_id != decryptValue($data['taskableId'])) {
                    $task->taskables()->delete();
                }

                $model->tasks()->sync($task);
            }

            $task->update($data);

            if ($task) {
                foreach ($data['checklist'] as $key => $value) {
                    if ($value != '') {
                        $task->checklist()->updateOrCreate([
                            'id' => (isset($value['id']) && $value['id'] != "") ? decryptValue($value['id']) : null,
                        ], [
                            'name' => $value['name'],
                            'completed' => $value['completed'],
                        ]);
                    }
                }
            }

            return $this->successResponse($task);
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param TasksFormRequest $request
     * @param string $task
     * @return JsonResponse
     * @throws Exception
     * @OA\Delete(
     *   tags={"Tasks"},
     *   path="/tasks/{id}",
     *   summary="Tasks destroy",
     *   operationId="tasksDestroy",
     *   security={ {"Api Secret token":{}}, {"Tenant Secret key":{}} },
     *   @OA\Parameter(ref="#/components/parameters/id"),
     *   @OA\Response(
     *     response="200",
     *     description="Successful operation",
     *     @OA\JsonContent(allOf={@OA\Schema(ref="#/components/schemas/TasksDeleteResponse") } ),
     *   ),
     * )
     */
    public function destroy(TasksFormRequest $request, string $task): JsonResponse
    {
        try {
            $task = $this->resource->findOrfail(decryptValue($task));
            $task->checklist()->delete();
            $task->delete();
            return $this->destroyResponse('task');
        } catch (Exception $th) {
            return $this->errorResponse($th);
        }
    }
}
