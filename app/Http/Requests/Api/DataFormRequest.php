<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Data")
 * )
 */
class DataFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="company_id", format="string", type="string", description="The company id", example=1, maxLength=255, minLength=50 )  */
    public int $company_id;

    /** @OA\Property(property="indicator_id", format="string", type="string", description="The indicator id", example=1, maxLength=255, minLength=50 )  */
    public int $indicator_id;

    /** @OA\Property(property="questionnaire_id", format="string", type="string", description="The questionnaire id", example=1, maxLength=255, minLength=50 )  */
    public int $questionnaire_id;

    /** @OA\Property(property="value", format="string", type="string", description="The data answer", example=1, maxLength=99999, minLength=0 )  */
    public float $value;

    /** @OA\Property(property="reported_at", format="date-time", type="date-time" , description="The data reported at date", example="1997-01-01T00:00:00.000000Z", maxLength=255, minLength=0 )  */
    public string $reported_at;

    /** @OA\Property(property="created_at", format="date-time", type="date-time" , description="The data created at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255, minLength=0)  */
    public string $created_at;

    /** @OA\Property(property="updated_at", format="date-time", type="date-time" , description="The data updated at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255, minLength=0 )  */
    public string $updated_at;

    /** @OA\Property(property="id_external", type="string", format="string", description="The external id", example=1, maxLength=64, minLength=30 )  */
    public int $id_external;

    /** @OA\Property(property="company_id_external", type="string", format="string", description="The company external id", example=1, maxLength=64, minLength=30 )  */
    public int $company_id_external;

    /** @OA\Property(property="indicator_id_external", type="string", format="string", description="The indicator external id", example=1, maxLength=64, minLength=30 )  */
    public int $indicator_id_external;

    /** @OA\Property(property="questionnaire_id_external", type="string", format="string", description="The questionnaire external id", example=1, maxLength=64, minLength=30 )  */
    public int $questionnaire_id_external;

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
     *     schema="DataResponse",
     *     title="DataResponse",
     *     description="DataResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/DataFormRequest")
     *   )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *    schema="DataIndicatorsResponse",
     *    title="DataIndicatorsResponse",
     *    description="DataIndicatorsResponse",
     *    @OA\Property(
     *      property="data",
     *      type="array",
     *         @OA\Items(
     *         type="object",
     *         @OA\Property(
     *          property="indicator_id",
     *          type="string",
     *          example="1",
     *          readOnly="true",
     *          maxLength=255,
     *          minLength=50,
     *         ),
     *          @OA\Property(
     *              property="indicator",
     *              type="object",
     *              readOnly="true",
     *              @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *          ),
     *         @OA\Property(
     *          property="value",
     *          type="string",
     *          format="string",
     *          example="9999",
     *          readOnly="true", maxLength=255, minLength=0
     *         ),
     *         @OA\Property(
     *          property="reported_at",
     *          type="string",
     *          format="date-time",
     *          example="1997-01-01T00:00:00.000000Z",
     *          readOnly="true", maxLength=255, minLength=0
     *         ),
     *         @OA\Property(property="indicator_id_external", type="string", format="string", example="1", readOnly="true", maxLength=64, minLength=30),
     *     )
     *   )
     * )
     */
    public function dataIndicators()
    {
    }

    /**
     * @OA\Schema(
     *    schema="DataWithDetailsResponse",
     *    title="DataWithDetailsResponse",
     *    description="DataWithDetailsResponse",
     *    @OA\Property(
     *      property="data",
     *      type="array",
     *         @OA\Items(
     *         type="object",
     *         @OA\Property(
     *          property="indicator_id",
     *          type="string",
     *          example="1",
     *          readOnly="true",
     *          maxLength=255,
     *          minLength=50,
     *         ),
     *          @OA\Property(
     *              property="indicator",
     *              type="object",
     *              readOnly="true",
     *              @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *          ),
     *         @OA\Property(
     *          property="value",
     *          type="string",
     *          format="string",
     *          example="9999",
     *          readOnly="true", maxLength=255, minLength=0
     *         ),
     *         @OA\Property(
     *          property="reported_at",
     *          type="string",
     *          format="date-time",
     *          example="1997-01-01T00:00:00.000000Z",
     *          readOnly="true", maxLength=255, minLength=0
     *         ),
     *         @OA\Property(property="indicator_id_external", type="string", format="string", example="1", readOnly="true", maxLength=64, minLength=30),
     *     )
     *   )
     * )
     */
    public function dataWithDetails()
    {
    }

    /**
     * @OA\Schema(
     *     schema="DataDeleteResponse",
     *     title="DataDeleteResponse",
     *     description="DataDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The DATA was successfully deleted ❗"
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
            'company_id' => ['exists:companies,id'],
            'indicator_id' => ['exists:indicators,id'],
            'value' => ['string'],
            'reported_at_date' => ['date_format:Y-m-d'],
            'reported_at_time' => ['nullable', 'date_format:H:i'],
        ];

        if (!$this->data) {
            $rules['company_id'][] = 'required';
            $rules['indicator_id'][] = 'required';
            $rules['value'][] = 'required';
            $rules['reported_at_date'][] = 'required';
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
            'company_id.required' => __('The :attribute field is required.'),
            'indicator_id.required' => __('The :attribute field is required.'),
            'value.required' => __('The :attribute field is required.'),
            'reported_at_date.required' => __('The :attribute field is required.'),
            'company_id.exists' => __('The selected :attribute is invalid.'),
            'indicator_id.exists' => __('The selected :attribute is invalid.'),
            'value.string' => __('The :attribute must be a number.'),
            'reported_at_date.date_format' => __('The :attribute does not match the format Y-m-d.'),
            'reported_at_time.date_format' => __('The :attribute does not match the format H:i.'),
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
            'company_id' => __('Company'),
            'indicator_id' => __('Indicator'),
            'value' => __('Value'),
            'reported_at_date' => __('Reported at date'),
            'reported_at_time' => __('Reported at time'),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $mergeArr = [];
        if ($this->has('company_id')) {
            $mergeArr['company_id'] = $this->get('company_id') ?
                decryptValue($this->get('company_id')) : null;
        }

        if ($this->has('indicator_id')) {
            $mergeArr['indicator_id'] = $this->get('indicator_id') ?
                decryptValue($this->get('indicator_id')) : null;
        }
        $this->merge($mergeArr);
    }
}
