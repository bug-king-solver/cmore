<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;
use App\Models\Tenant\Role;

/**
 *  @OA\Schema(
 *    @OA\Xml(name="Roles")
 *  )
 */
class RolesFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string" , description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="default", format="boolean", type="boolean" , description="Default RULE")  */
    public string $default;

    /** @OA\Property(property="name", format="string", type="string" , description="The role name", example="admin" )  */
    public string $name;

    /** @OA\Property(property="permissions", format="string", type="string" , description="The role permissions", example="1,2,3" )  */
    public string $permissions;

    //guard_name, created_at, updated_at  readOnly

    /** @OA\Property(property="guard_name", format="string", type="string" , description="The role guard name", example="web", readOnly="true" )  */
    public string $guard_name;

    /** @OA\Property(property="created_at", format="string", type="string" , description="The role created at", example="2021-01-01", readOnly="true" )  */
    public string $created_at;

    /** @OA\Property(property="updated_at", format="string", type="string" , description="The role updated at", example="2021-01-01", readOnly="true" )  */
    public string $updated_at;

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
     *     schema="RolesResponse",
     *     title="RolesResponse",
     *     description="RolesResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/RolesFormRequest")
     *   )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *     schema="RolesDeleteResponse",
     *     title="RolesDeleteResponse",
     *     description="RolesDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The Roles was successfully deleted ❗"
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
     */
    public function store(): array
    {
        $this->role = $this->role ? Role::findOrfail(decryptValue($this->role)) : null;

        $rules = [
            'default' => ['nullable', 'boolean'],
            'name' => [
                'string',
                'max:255',
                'unique:roles,name' . ($this->role ? ",{$this->role->id}" : ''),
            ],
            'users' => ['nullable', 'exists:users,id'],
            'permissions' => ['nullable', 'exists:permissions,id'],
        ];

        if (! $this->role) {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function update(): array
    {
        return $this->store();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'default.required' => __('The :attribute field is required.'),
            'default.boolean' => __('The :attribute field must be a boolean.'),
            'name.required' => __('The :attribute field is required.'),
            'name.string' => __('The :attribute field must be a string.'),
            'name.max' => __('The :attribute field may not be greater than :max characters.'),
            'name.unique' => __('The :attribute has already been taken.'),
            'users.required' => __('The :attribute field is required.'),
            'users.exists' => __('The selected :attribute is invalid.'),
            'permissions.required' => __('The :attribute field is required.'),
            'permissions.exists' => __('The selected :attribute is invalid.'),
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
            'default' => __('Default'),
            'name' => __('Name'),
            'users' => __('Users'),
            'permissions' => __('Permissions'),
        ];
    }
}
