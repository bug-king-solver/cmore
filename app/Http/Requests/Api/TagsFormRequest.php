<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Tags")
 * )
 */
class TagsFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string" , description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="name", format="string", type="string" , description="The company name", example="Tag name" )  */
    public string $name;

    /** @OA\Property(property="color", format="string", type="string" , description="HEX or RGB color", example="#FFFF" )  */
    public int $color;

    /** @OA\Property(property="slug", format="string", type="string" , description="The role guard name", example="tag-name", readOnly="true" )  */
    public string $slug;

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
     *     schema="TagsResponse",
     *     title="TagsResponse",
     *     description="TagsResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/TagsFormRequest")
     *   )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *     schema="TagsDeleteResponse",
     *     title="TagsDeleteResponse",
     *     description="TagsDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The Tags was successfully deleted ❗"
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
        $rules = [
            'name' => ['string', 'max:255'],
            'color' => ['string', 'max:255'],
        ];

        if (!$this->tag) {
            $rules['name'][] = 'required';
            $rules['color'][] = 'required';
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
            'color.required' => __('The :attribute field is required.'),
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
            'color' => __('Color'),
        ];
    }
}
