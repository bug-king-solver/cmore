<?php

namespace App\Http\Requests\Api\Compliance\Reputational;

use App\Http\Requests\Api\BaseFormRequest;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Weekly")
 * )
 * @OA\Parameter(
 *   name="weekly",
 *   in="path",
 *   description="id",
 *   required=true,
 *   @OA\Schema(type="integer",format="int64" ),
 *   example=1
 * )
 */
class WeeklyRequest extends BaseFormRequest
{
    /** @OA\Property(property="ainfo_id", format="integer", type="integer" , description="Analysis info id", example=1 )  */
    public int $ainfo_id;

    /** @OA\Property(property="data", format="json", type="json" , description="Data json", example={"name":"John", "age":30, "car":null} )  */
    public string $data;

    /** @OA\Property(property="year", format="string", type="string" , description="year", example="2022" )  */
    public string $year;

    /** @OA\Property(property="month", format="string", type="string" , description="year", example="12" )  */
    public string $month;

    /** @OA\Property(property="week_of_year", format="string", type="string" , description="year", example="5" )  */
    public string $week_of_year;

    /** @OA\Property(property="extracted_at", format="date", type="date" , description="extracted at date", example="2021-01-01" )  */
    public string $extracted_at;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /**
         * Return the parent authorize method to check the permissions
         */
        return parent::authorize();
    }

    public function store(): array
    {
        $rules = [
            'ainfo_id' => [
                'required',
                'exists:reputation_analysis_info,id',
            ],
            'data' => [
                'json', 'required',
            ],
            'year' => ['string', 'required'],
            'month' => ['string', 'required'],
            'week_of_year' => ['string', 'required'],
            'extracted_at' => ['required', 'date'],
        ];

        return $rules;
    }

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
            'ainfo_id.required' => __('The :attribute field is required.'),
            'ainfo_id.exists' => __('The selected :attribute is invalid.'),
            'data.required' => __('The :attribute field is required.'),
            'data.json' => __('The :attribute field must be a valid JSON.'),
            'year.required' => __('The :attribute field is required.'),
            'month.required' => __('The :attribute field is required.'),
            'week_of_year.required' => __('The :attribute field is required.'),
            'year.string' => __('The :attribute field must be a valid string.'),
            'month.string' => __('The :attribute field must be a valid string.'),
            'week_of_year.string' => __('The :attribute field must be a valid string.'),
            'extracted_at.required' => __('The :attribute field is required.'),
            'extracted_at.date' => __('The :attribute field must be a valid date'),
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
            'ainfo_id' => __('analysis id'),
            'data' => __('data'),
            'year' => __('year'),
            'month' => __('month'),
            'week_of_year' => __('week of year'),
            'extracted_at' => __('extracted At'),
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
        if ($this->has('ainfo_id')) {
            $mergeArr['ainfo_id'] = $this->get('ainfo_id') ? decryptValue($this->get('ainfo_id')) : null;
        }
        $this->merge($mergeArr);
    }
}
