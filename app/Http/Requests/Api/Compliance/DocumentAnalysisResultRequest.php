<?php

namespace App\Http\Requests\Api\Compliance;

use App\Http\Requests\Api\BaseFormRequest;

/**
 *  @OA\Schema(
 *   title="Document analysis result request",
 *   description="Document analysis result request",
 *   @OA\Xml(name="DocumentAnalysisResultRequest")
 *  )
 *  @OA\Parameter(
 *   name="document_analysis",
 *   in="path",
 *   description="document_analysis id",
 *   required=true,
 *   @OA\Schema(type="integer",format="int64" ),
 *   example=1
 *  )
 */
class DocumentAnalysisResultRequest extends BaseFormRequest
{
    /** @OA\Property(property="compliance_level", format="integer", type="integer" , description="The document analysis result compliance level", example=1 )  */
    public int $compliance_level;

    /** @OA\Property(property="domain_id", format="integer", type="integer" , description="The document analysis result domain id", example=1 )  */
    public int $domain_id;

    /** @OA\Property(
     *    property="snippets",
     *    format="array",
     *    type="array",
     *    description="The document analysis result snippets",
     *    @OA\Items(
     *      type="object",
     *      @OA\Property(property="term", format="string", type="string" , description="The snippet term", example="term" ),
     *      @OA\Property(property="prefix", format="string", type="string" , description="The snippet prefix", example="prefix" ),
     *      @OA\Property(property="suffix", format="string", type="string" , description="The snippet suffix", example="suffix" ),
     *      @OA\Property(property="page", format="integer", type="integer" , description="The snippet page", example=1 )
     *    )
     *  )
     */
    public array $snippets;

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

    public function start(): array
    {
        return [];
    }

    public function finish(): array
    {
        $rules = [];
        $rules['compliance_level'] = ['integer'];
        $rules['snippets.*.domain_id'] = ['required', 'integer'];
        $rules['snippets.*.term'] = ['required', 'string'];
        $rules['snippets.*.prefix'] = ['required', 'string'];
        $rules['snippets.*.suffix'] = ['required', 'string'];
        $rules['snippets.*.page'] = ['required',  'integer'];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'document_analysis_type_id.required' => __('The document analysis type is required'),
            'document_analysis_type_id.exists' => __('The selected document analysis type is invalid'),
            'started_at.date' => __('The started at must be a date'),
            'ended_at.date' => __('The ended at must be a date'),
            'compliance_level.in' => __('The selected compliance level is invalid'),
            'snippets.required' => __('The snippets is required'),
            'snippets.array' => __('The snippets must be an array'),
            'snippets.*.domain_id.required' => __('The snippets domain id is required'),
            'snippets.*.domain_id.integer' => __('The snippets domain id is must be an integer'),
            'snippets.*.term.required' => __('The snippets term is required'),
            'snippets.*.term.string' => __('The snippets term must be a string'),
            'snippets.*.prefix.required' => __('The snippets prefix is required'),
            'snippets.*.prefix.string' => __('The snippets prefix must be a string'),
            'snippets.*.suffix.required' => __('The snippets suffix is required'),
            'snippets.*.suffix.string' => __('The snippets suffix must be a string'),
            'snippets.*.page.required' => __('The snippets page is required'),
            'snippets.*.page.integer' => __('The snippets page must be an integer'),
            'domain_id.required' => __('The domain id is required'),
            'domain_id.integer' => __('The domain id must be an integer'),
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
            'document_analysis_type_id' => __('document analysis type'),
            'started_at' => __('started at'),
            'ended_at' => __('ended at'),
            'compliance_level' => __('compliance level'),
            'snippets' => __('snippets'),
            'snippets.*.term' => __('snippets term'),
            'snippets.*.prefix' => __('snippets prefix'),
            'snippets.*.suffix' => __('snippets suffix'),
            'snippets.*.page' => __('snippets page'),
            'domain_id' => __('domain id'),
        ];
    }
}
