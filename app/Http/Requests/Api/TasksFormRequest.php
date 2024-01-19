<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;
use App\Models\Tenant\Task;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Tasks")
 * )
 */
class TasksFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string" , description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="name", format="string", type="string" , description="The company name", example="Task name" )  */
    public string $name;

    /** @OA\Property(property="description", format="string", type="string" , description="Task description", example="Task description" )  */
    public string $description;

    /** @OA\Property(property="weight", format="string", type="string" , description="Task weight", example=90 )  */
    public int $weight;

    /** @OA\Property(property="due_date", format="string", type="string" , description="Task due date", example="2023-01-01" )  */
    public date $due_date;

    /** @OA\Property(property="entity", format="string", type="string" , description="Task entity", example="targets" )  */
    public string $entity;

    /** @OA\Property(property="taskableId", type="string" , description="Id of the entity", example="1" ,  maxLength=255, minLength=50 )  */
    public int $taskableId;

    /** @OA\Property(
     *    property="checklist",
     *    format="array",
     *    type="array",
     *    description="Task checklist",
     *    @OA\Items(
     *      type="object",
     *      @OA\Property(property="id", format="string", type="string" , description="Checklist id to update", example="1", maxLength=255, minLength=50 ),
     *      @OA\Property(property="name", format="string", type="string" , description="Checklist name", example="Checklist 01" ),
     *      @OA\Property(property="completed", format="boolean", type="boolean" , description="Is completed", example=false ),
     *    )
     *  )
     */
    public array $checklist;


    /**
     * @OA\Property(
     *  property="owner", format="array", type="array" , description="owner",
     *  @OA\Items(type="object"),
     *  readOnly=true
     * )
     */
    public string $owner;

    /** @OA\Property(property="alert_on_complete", format="bool", type="bool" , description="The role created at", example="1", readOnly="true" )  */
    public bool $alert_on_complete;

    /** @OA\Property(property="completed", format="bool", type="bool" , description="The role created at", example="1", readOnly="true" )  */
    public bool $completed;

    /**
     * @OA\Property(
     *  property="taskables", format="array", type="array" , description="taskables",
     *  @OA\Items(type="object"),
     *  readOnly=true
     * )
     */
    public string $taskables;

    /** @OA\Property(property="created_at", format="string", type="string" , description="The role created at", example="2021-01-01", readOnly="true" )  */

    public string $created_at;

    /** @OA\Property(property="updated_at", format="string", type="string" , description="The role updated at", example="2021-01-01", readOnly="true" )  */
    public string $updated_at;

    /** @OA\Property(property="deleted_at", format="string", type="string" , description="The role updated at", example="2021-01-01", readOnly="true" )  */
    public string $deleted_at;


    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        /**
         * Return the parent authorize method to check the permissions
         */
        return parent::authorize();
    }

    /**
     *  @OA\Schema(
     *     schema="TasksResponse",
     *     title="TasksResponse",
     *     description="TasksResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/TasksFormRequest")
     *   )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *     schema="TasksDeleteResponse",
     *     title="TasksDeleteResponse",
     *     description="TasksDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The Tasks was successfully deleted ❗"
     *     ),
     *     @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *             type="string"
     *         ),
     *         example={}
     *     )
     * )
     */
    public function delete()
    {
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, mixed>
     */
    public function store(): array
    {
        $this->task = $this->task ? Task::find(decryptValue($this->task)) : null;
        $date = $this->task
            ? $this->task->created_at->addDays(1)->format('Y-m-d')
            : 'tomorrow';

        $rules = [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
            'weight' => ['integer', 'max:255', 'gt:0'],
            'due_date' => ['date', 'max:255', 'after_or_equal:' . $date],
            'entity' => ['string', 'max:255'],
            'taskableId' => ['string', 'max:255', 'required_with:entity'],
            'checklist' => 'array',
        ];

        if (!$this->task) {
            $rules['name'][] = 'required';
            $rules['description'][] = 'required';
            $rules['weight'][] = 'required';
            $rules['due_date'][] = 'required';
            $rules['entity'][] = 'required';
        }

        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, mixed>
     */
    public function update(): array
    {
        return $this->store();
    }

    /**
     * Get the error messages for the defined validation rules.
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('The :attribute field is required.'),
            'name.string' => __('The :attribute field must be a string.'),
            'name.max' => __('The :attribute field must not be greater than :max characters.'),
            'description.required' => __('The :attribute field is required.'),
            'description.string' => __('The :attribute field must be a string.'),
            'description.max' => __('The :attribute field must not be greater than :max characters.'),
            'weight.required' => __('The :attribute field is required.'),
            'weight.integer' => __('The :attribute field must be an integer.'),
            'weight.max' => __('The :attribute field must not be greater than :max characters.'),
            'weight.gt' => __('The :attribute field must be greater than :value.'),
            'due_date.required' => __('The :attribute field is required.'),
            'due_date.date' => __('The :attribute field must be a date.'),
            'due_date.max' => __('The :attribute field must not be greater than :max characters.'),
            'due_date.after_or_equal' => __('The :attribute field must be a date after or equal to :date.'),
            'entity.required' => __('The :attribute field is required.'),
            'entity.string' => __('The :attribute field must be a string.'),
            'entity.max' => __('The :attribute field must not be greater than :max characters.'),
            'taskableId.required' => __('The :attribute field is required.'),
            'taskableId.string' => __('The :attribute field must be a string.'),
            'taskableId.max' => __('The :attribute field must not be greater than :max characters.'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('Name'),
            'description' => __('Description'),
            'weight' => __('Weight'),
            'due_date' => __('Due date'),
            'entity' => __('Entity'),
            'taskableId' => __('Taskable id'),
        ];
    }
}
