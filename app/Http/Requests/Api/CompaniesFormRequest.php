<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;
use App\Models\Enums\Companies\Relation;
use App\Models\Enums\CompanySize;
use App\Models\Enums\ExternalCompanyTypeEnum;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Company;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Companies")
 * )
 */
class CompaniesFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly=true, maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="created_by_user_id", type="string", format="string", description="The company created by user id", example=1, readOnly=true, maxLength=255, minLength=50 )  */
    public int $created_by_user_id;

    /** @OA\Property(property="logo", format="string", type="string", description="The company logo", example="logo", readOnly=true, maxLength=3000)  */
    public string $logo;

    /** @OA\Property(property="name", type="string", format="string", description="The company name", example="Company name",  minLength=1,  maxLength=255 )  */
    public string $name;

    /** @OA\Property(property="parent_id", type="string", format="string", description="The company parent id - the Id of a company", example="1", nullable=true , maxLength=255, minLength=50 )  */
    public int $parent_id;

    /** @OA\Property(property="business_sector_id", type="string", format="string", description="The company business sector id", example="1" ,  maxLength=255, minLength=50 )  */
    public int $business_sector_id;

    /** @OA\Property(property="country", format="string", type="string", description="The company country  - ISO 3166 Alpha-3", example="PRT,BRA" , maxLength=3, minLength=2)  */
    public string $country;

    /** @OA\Property(property="headquarters_eu", type="integer" , description="country is eu", example="1" , maximum=1, minimum=0)  */
    public string $headquarters_eu;

    /** @OA\Property(property="vat_country", format="string", type="string", description="The company vat country  - ISO 3166 Alpha-3", example="PRT,BRA", maxLength=3, minLength=2 )  */
    public string $vat_country;

    /** @OA\Property(property="vat_number", format="string", type="string", description="The company vat number", example="12345678A" , minLength=0, maxLength=15)  */
    public string $vat_number;

    /** @OA\Property(property="size", format="string", type="string", description="The company size", enum={"Micro", "Small", "Medium", "Large"}, minLength=0, maxLength=10 )  */
    public string $size;

    /** @OA\Property(property="color", type="string", format="string", description="The company color", example="#FFFFFF" , maxLength=7, minLength=5 , pattern="/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/")  */
    public string $color;

    /** @OA\Property(property="founded_at", format="date-time", type="date-time", description="The company founded at date", example="1997-01-01T00:00:00.000000Z", minLength=1, maxLength=255 )  */
    public string $founded_at;

    /** @OA\Property(property="type", format="string", type="string", description="The company type", enum={"client", "supplier"}, minLength=0, maxLength=255 )  */
    public string $type;

    /** @OA\Property(property="created_at", format="date-time", type="date-time", description="The company created at date", example="1997-01-01T00:00:00.000000Z", readOnly=true , minLength=1, maxLength=255)  */
    public string $created_at;

    /** @OA\Property(property="updated_at", format="date-time", type="date-time", description="The company updated at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, minLength=1, maxLength=255 )  */
    public string $updated_at;

    /**
     * @OA\Property(
     *  property="questionnaires",
     * type="array",
     * readOnly=true,
     * @OA\Items(
     *  @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly=true, maxLength=255, minLength=50 ),
     *  @OA\Property(property="company_id", type="string", format="string", description="The questionnaire company id", example="1", readOnly=true,  maxLength=255, minLength=50 ),
     *  @OA\Property(property="questionnaire_type_id", type="string", format="string", description="The questionnaire type id",  example="1", readOnly=true,  maxLength=255, minLength=50 ),
     *  @OA\Property(property="data", format="object", type="object" , description="The data", example={}, readOnly=true ),
     *  @OA\Property( property="type", type="object", readOnly=true,
     *          @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly=true, maxLength=255, minLength=50),
     *          @OA\Property(
     *              property="name",
     *              type="object",
     *              readOnly="true",
     *              description="The name object",
     *              @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *          ),
     *          @OA\Property(property="data", format="object", type="object" , description="The data", example={}, readOnly=true ),
     *          @OA\Property(property="id_external", format="string", type="string", description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *  ),
     *  @OA\Property(property="id_external", format="string", type="string", description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *  @OA\Property(property="company_id_external", format="string", type="string", description="company id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *  @OA\Property(property="questionnaire_type_id_external", format="string", type="string", description="questionnaire type id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *      ),
     *    )
     */
    public array $questionnaires;

    /** @OA\Property(property="commercial_name", type="string", format="string", description="The commercial name", example="Company name",  minLength=1,  maxLength=255, readOnly=true )  */
    public string $commercial_name;

    /** @OA\Property(property="data", type="string", format="string", description="The data", nullable=true, maxLength=3000, readOnly=true )  */
    public string $data;

    /** @OA\Property(property="cus_categories", format="string", type="string", description="company category", example="credit-institutions" , maxLength=255)  */
    public string $cus_categories;

    /** @OA\Property(property="cus_is_issuer_of_securities", format="boolean", type="boolean" , description="is customer is issuer of securities", example="1" , maxLength=255)  */
    public string $cus_is_issuer_of_securities;


    /**
     * @OA\Property(
     *  property="business_sectors",
     * type="array",
     * readOnly=true,
     * @OA\Items(
     *  @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly=true, maxLength=255, minLength=50),
     *  @OA\Property(
     *       property="name",
     *       type="object",
     *       readOnly="true",
     *       description="The name object",
     *        @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *        @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *        @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *        @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *    ),
     *  @OA\Property(property="main", type="integer" , description="main", readOnly=true, minimum=0, maximum=1),
     *  @OA\Property(property="id_external", format="string", type="string", description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     * )
     * )
     */
    public array $business_sectors;

    /**
     * @OA\Property(
     *  property="parent",
     * type="array",
     * readOnly=true,
     * @OA\Items(
     *  @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly=true, minLength=1, maxLength=255),
     *  @OA\Property(property="name", type="string", format="string", description="name", minLength=1, maxLength=255, readOnly=true),
     *  @OA\Property(property="data", type="string", format="string", description="data", nullable=true, readOnly=true, minLength=1, maxLength=3000),
     *  @OA\Property(property="id_external", type="string", format="string", description="external ID", example="1", readOnly=true, maxLength=64, minLength=30),
     * )
     * )
     */
    public array $parent;

    /** @OA\Property(property="id_external", type="string", format="string", description="The external id", example=1,maxLength=64, minLength=30 )  */
    public int $id_external;

    /** @OA\Property(property="parent_id_external", type="string", format="string", description="The parent external id", example=1,maxLength=64, minLength=30 )  */
    public int $parent_id_external;

    /** @OA\Property(property="created_by_user_id_external", type="string", format="string", description="The created by user id external id", example=1,maxLength=64, minLength=30 )  */
    public int $created_by_user_id_external;

    /** @OA\Property(property="business_sector_id_external", type="string", format="string", description="The  company external id", example=1,maxLength=64, minLength=30 )  */
    public int $business_sector_id_external;

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
     *     schema="CompaniesResponse",
     *     title="CompaniesResponse",
     *     description="CompaniesResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/CompaniesFormRequest")
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *     schema="CompaniesDeleteResponse",
     *     title="CompaniesDeleteResponse",
     *     description="CompaniesDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The company was successfully deleted ❗"
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
     * Get the validation rules that apply to the store request.
     */
    public function store(): array
    {
        $countriesList = getCountriesForSelect();
        $countriesIdsList = array_column($countriesList, 'id');

        $rules = [
            'name' => ['string', 'max:255'],
            'parent_id' => [
                'nullable',
                'exists:companies,id'
            ],
            'business_sector_id' => [
                'exists:business_sectors,id'
            ],
            'country' => [Rule::in($countriesIdsList)],
            'vat_country' => [Rule::in($countriesIdsList)],
            'vat_number' => ['string', 'max:255'],
            'size' => [Rule::in(array_map('strtolower', CompanySize::values()))],
            'founded_at' => ['date'],
            'type.*' => [
                'nullable',
                Rule::in(Relation::keys()),
            ],
        ];

        if (!$this->company) {
            $rules['name'][] = 'required';
            $rules['business_sector_id'][] = 'required';
            $rules['country'][] = 'required';
            $rules['vat_country'][] = 'required';
            $rules['vat_number'][] = 'required';
            $rules['size'][] = 'required';
            $rules['founded_at'][] = 'required';
            $rules['color'] = 'required';
        }

        return $rules;
    }

    /**
     * Get the validation rules that apply to the update request.
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
            'name.required' => __('The :attribute field is required.'),
            'business_sector_id.required' => __('The :attribute field is required.'),
            'business_sector_id.exists' => __('The selected :attribute is invalid.'),
            'country.required' => __('The :attribute field is required.'),
            'country.in' => __('The selected :attribute is invalid.'),
            'country.exists' => __('The selected :attribute is invalid.'),
            'vat_country.required' => __('The :attribute field is required.'),
            'vat_number.required' => __('The :attribute field is required.'),
            'size.required' => __('The :attribute field is required.'),
            'founded_at.required' => __('The :attribute field is required.'),
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
            'parent_id' => __('Parent'),
            'business_sector_id' => __('Business sector'),
            'country' => __('Country'),
            'vat_country' => __('VAT country'),
            'vat_number' => __('VAT number'),
            'size' => __('Size'),
            'founded_at' => __('Founded at'),
            'type' => __('Type'),
            'color' => __('Color'),
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
        if ($this->has('parent_id')) {
            $mergeArr['parent_id'] = $this->get('parent_id')
                ? decryptValue($this->get('parent_id'))
                : null;
        }

        if ($this->has('business_sector_id')) {
            $mergeArr['business_sector_id'] = $this->get('business_sector_id') ?
                decryptValue($this->get('business_sector_id')) : null;
        }
        $this->merge($mergeArr);
    }
}
