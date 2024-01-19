<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;

/**
 *  @OA\Schema(
 *    @OA\Xml(name="Users")
 *  )
 */
class UsersFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string" , description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="name", format="string", type="string" , description="The user name", example="John Doe" )  */
    public string $name;

    /** @OA\Property(format="string", description="The user email", example="john@example.com")   */
    public string $email;

    /** @OA\Property(format="string", description="The user password", example="12345678")  */
    public string $password;

    /** @OA\Property(format="boolean", description="The user enabled", example="true")  */
    public bool $enabled;

    /** @OA\Property(format="string", description="The user type", example="admin")  */
    public string $type;

    /** @OA\Property(format="boolean", description="The user system", example="true")  */
    public bool $system;

    /** @OA\Property(type="null",  description="The user email_verified_at", example="2021-08-31 00:00:00")  */
    public string $email_verified_at;

    /** @OA\Property(property="created_at", format="string", type="string" , description="created at", example="2021-01-01", readOnly="true" )  */
    public string $created_at;

    /** @OA\Property(property="updated_at", format="string", type="string" , description="updated at", example="2021-01-01", readOnly="true" )  */
    public string $updated_at;

    /** @OA\Property(property="locale", format="string", type="string" , description="locale", example="en", readOnly="true" )  */
    // public string $locale;

    /** @OA\Property(property="username", format="string", type="string" , description="username", example="johndoe", readOnly="true" )  */
    public string $username;

    /** @OA\Property(property="two_factor_code", format="string", type="string" , description="two_factor_code", example="123456", readOnly="true" )  */
    public string $two_factor_code;

    /** @OA\Property(property="two_factor_expires_at", format="string", type="string" , description="two_factor_expires_at", example="2021-01-01", readOnly="true" )  */
    public string $two_factor_expires_at;

    /** @OA\Property(property="phone", format="string", type="string" , description="phone", example="123456789", readOnly="true" )  */
    public string $phone;

     /** @OA\Property(property="google2fa_secret", format="string", type="string" , description="google2fa_secret", example="123456", readOnly="true" )  */
    public string $google2fa_secret;

    /** @OA\Property(property="use_2fa", format="string", type="string" , description="use_2fa", example="123456", readOnly="true" )  */
    public string $use_2fa;

    /** @OA\Property(property="send_2fa_to_phone", format="string", type="string" , description="send_2fa_to_phone", example="", readOnly="true" )  */
    public string $send_2fa_to_phone;

    /** @OA\Property(property="recovery_codes", format="string", type="string" , description="recovery_codes", example="", readOnly="true" )  */
    public string $recovery_codes;

    /** @OA\Property(property="send_2fa_to_email", format="string", type="string" , description="send_2fa_to_email", example="", readOnly="true" )  */
    public string $send_2fa_to_email;


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
     *     schema="UsersResponse",
     *     title="UsersResponse",
     *     description="UsersResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/UsersFormRequest")
     *   )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *     schema="UsersDeleteResponse",
     *     title="UsersDeleteResponse",
     *     description="UsersDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The Users was successfully deleted ❗"
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
        $rules = [
            'name' => [
                'string', 'max:255',
            ],
            'email' => [
                'string',
                'max:255',
                'email',
                'unique:users,email' . ($this->user ? ",{$this->user->id}" : ''),
            ],
            'password' => ['string', 'min:8'],
            'enabled' => ['nullable', 'boolean'],
            'type' => ['nullable', 'string', 'max:255'],
            'system' => ['nullable', 'boolean'],
            'email_verified_at' => ['nullable', 'date'],
        ];

        if (!$this->user) {
            $rules['name'][] = 'required';
            $rules['email'][] = 'required';
            $rules['password'][] = 'required';
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
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('The :attribute field is required.'),
            'name.string' => __('The :attribute field must be a string.'),
            'name.max' => __('The :attribute field may not be greater than :max characters.'),
            'email.required' => __('The :attribute field is required.'),
            'email.string' => __('The :attribute field must be a string.'),
            'email.email' => __('The :attribute field must be a valid email address.'),
            'email.max' => __('The :attribute field may not be greater than :max characters.'),
            'email.unique' => __('The :attribute has already been taken.'),
            'password.required' => __('The :attribute field is required.'),
            'password.string' => __('The :attribute field must be a string.'),
            'password.min' => __('The :attribute field must be at least :min characters.'),
            'user.required' => __('You must send the user id to delete.'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('Name'),
            'email' => __('Email'),
            'password' => __('Password'),
        ];
    }
}
