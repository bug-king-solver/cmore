<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\BaseFormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Questionnaire")
 * )
 */
class QuestionnaireFormRequest extends BaseFormRequest
{
    /** @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50 )  */
    public string $id;

    /** @OA\Property(property="company_id", type="string", format="string", description="The company id", example=1, maxLength=255, minLength=50 )  */
    public int $company_id;

    /** @OA\Property(
     *  property="countries", type="array" , description="The questionnaire countries - ISO 3166 Alpha-3", example={"PRT", "BRA"},
     *  @OA\Items(type="string", example={"PRT", "BRA"})
     * )
     */
    public string $countries;

    /** @OA\Property(property="from", format="date", type="string" , description="The questionnaire begin date", example="2021-01-01", maxLength=255, minLength=1, nullable=true )  */
    public string $from;

    /** @OA\Property(property="to", format="date", type="string" , description="The questionnaire end date", example="2021-01-01", maxLength=255, minLength=1, nullable=true )  */
    public string $to;


    /** @OA\Property(property="created_by_user_id", type="string", format="string", description="The questionnaire created by user id", example=1, readOnly=true,  maxLength=255, minLength=50 )  */
    public int $created_by_user_id;

    /** @OA\Property(property="is_ready", type="integer" , description="The questionnaire is ready to be answered", example=1, readOnly=true, minimum=0, maximum=1 )  */
    public bool $is_ready;

    /** @OA\Property(property="progress", type="integer" , description="The questionnaire progress", example=100, readOnly=true, minimum=0, maximum=100 )  */
    public int $progress;


    /**
     * @OA\Property(
     *    property="categories", format="array",
     *    readOnly=true,
     *    nullable=true,
     *    type="array",
     *    @OA\Items(
     *          @OA\Property(property="id", type="string" , description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50),
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
     *          @OA\Property(property="notes", format="string", type="string" , description="The category notes", example="notes", readOnly="true", maxLength=3000, minLength=0),
     *          @OA\Property(property="extra", type="object" , description="The category extra", readOnly="true",
     *              @OA\Property(property="original_id", type="string" , description="The category original_id", example="1", readOnly="true", maxLength=255, minLength=1),
     *          ),
     *          @OA\property(property="order", type="integer" , description="The category order", example="1", readOnly="true", minimum=0, maximum=99999),
     *          @OA\property(property="weight", type="integer" , description="The category weight", example="1", readOnly="true", minimum=0, maximum=99999),
     *          @OA\property(property="enabled", type="integer" , description="The category enabled", example="true", readOnly="true", minimum="0", maximum="1"),
     *          @OA\property(property="maturity", type="string" , format="string", description="The category maturity", example="1", readOnly="true", maxLength=255, minLength=1),
     *          @OA\property(property="model_id", type="string" , format="string", description="The category model_id", example="1", readOnly="true", maxLength=255),
     *          @OA\property(property="model_type", type="string" , format="string", description="The category model_type", example="1", readOnly="true", maxLength=255),
     *          @OA\property(property="progress", type="integer" , description="The category progress", example="100", readOnly="true", minimum=0, maximum=100),
     *          @OA\property(property="parent_id", type="string" , format="string", description="The category parent_id", example="1", readOnly="true", maxLength=255),
     *          @OA\property(property="created_at", type="date-time" , format="date-time", description="The category created_at", example="1997-01-01T00:00:00.000000Z", readOnly="true", maxLength=255),
     *          @OA\property(property="updated_at", type="date-time" , format="string", description="The category updated_at", example="1997-01-01T00:00:00.000000Z", readOnly="true", maxLength=255),
     *          @OA\property(property="deleted_at", type="date-time" , format="date-time", description="The category deleted_at", example="1997-01-01T00:00:00.000000Z", readOnly="true", maxLength=255),
     *          @OA\property(property="description", type="object" , description="The category description", readOnly="true",
     *              @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *         ),
     *         @OA\property(property="ponderation", type="integer" , description="The category ponderation", example="1", readOnly="true", maximum=9999),
     *         @OA\property(property="maturity_final", type="string" , format="string", description="The category maturity_final", example="1", readOnly="true", maxLength=255, minLength=1),
     *         @OA\property(property="questions_count", type="integer" , description="The category questions_count", example="1", readOnly="true", minimum=0, maximum=9999),
     *         @OA\property(property="questions_answered", type="integer" , description="The category questions_answered", example="1", readOnly="true", minimum=0, maximum=9999),
     *         @OA\property(property="questions_sum_weight", type="integer" , description="The category questions_sum_weight", example="1", readOnly="true", minimum=0, maximum=9999),
     *         @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *         @OA\Property(property="model_id_external", format="string", type="string" , description="model id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *         @OA\Property(property="parent_id_external", format="string", type="string" , description="parent id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *      ),
     *   )
     * )
     */
    public array $categories;

    /**
     *    @OA\Property(
     *        property="physical_risks",
     *        format="array",
     *        readOnly=true,
     *        nullable=true,
     *        type="array",
     *        description="This property only has value when the questionnaire type name (pt-PT) is 'Riscos Físicos' ",
     *        @OA\Items(
     *            @OA\Property(property="id", type="string", example="1", readOnly=true, maxLength=255, minLength=50),
     *            @OA\Property(property="created_by_user_id", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="questionnaire_id", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="name", type="string", example="test", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="description", type="string", example="description", nullable=true, readOnly=true, maxLength=3000, minLength=1),
     *            @OA\Property(property="country_iso", type="string", example="AFG", readOnly=true, maxLength=255, minLength=3),
     *            @OA\Property(property="country_code", type="integer", example=1, readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="region_code", type="integer", example=272, readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="city_code", type="integer", example=3448, readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(
     *                property="hazards",
     *                type="object",
     *                readOnly=true,
     *                @OA\Property(
     *                    property="data",
     *                    type="array",
     *                    @OA\Items(
     *                        @OA\Property(property="name", type="string", example="River flood", readOnly=true, maxLength=255, minLength=1),
     *                        @OA\Property(property="risk", type="string", example="Very low", readOnly=true, maxLength=255, minLength=1),
     *                        @OA\Property(property="enabled", type="integer", example=1, maxLength=1, minLength=0, readOnly=true),
     *                        @OA\Property(property="name_slug", type="string", example="fl", readOnly=true, maxLength=255, minLength=1),
     *                        @OA\Property(property="risk_slug", type="string", example="vlo", readOnly=true, maxLength=255, minLength=1),
     *                        @OA\Property(
     *                            property="audits",
     *                            type="array",
     *                            @OA\Items(
     *                                @OA\Property(property="risk", type="string", example="Volcano", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="user", type="string", example="Test", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="action", type="string", example="change_level", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="new_slug", type="string", example="high", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="old_slug", type="string", example="low", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="new_value", type="string", example="High", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="old_value", type="string", example="low", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="risk_slug", type="string", example="va", readOnly=true, maxLength=255, minLength=1),
     *                                @OA\Property(property="created_at", type="string", example="2023-10-30T15:52:55.296308Z", readOnly=true),
     *                                @OA\Property(property="description", type="string", example="high", readOnly=true, maxLength=255, minLength=1)
     *                            )
     *                        )
     *                    )
     *                ),
     *                @OA\Property(
     *                    property="location",
     *                    type="object",
     *                    readOnly=true,
     *                    @OA\Property(property="city", type="string", example="Eshkmesh", readOnly=true, maxLength=255, minLength=1),
     *                    @OA\Property(property="region", type="string", example="Badakhshan", readOnly=true, maxLength=255, minLength=1),
     *                    @OA\Property(property="country", type="string", example="Afghanistan", readOnly=true, maxLength=255, minLength=1)
     *                )
     *            ),
     *            @OA\Property(property="note", type="string", example="testing note", readOnly=true, maxLength=500, minLength=1),
     *            @OA\Property(property="relevant", type="string", example="critical", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="completed", type="integer", example=0, maxLength=1, minLength=0, readOnly=true),
     *            @OA\Property(property="completed_at", type="string", example="2023-10-24T15:21:47.000000Z", nullable=true, readOnly=true),
     *            @OA\Property(property="created_at", type="string", example="2023-10-24T15:21:47.000000Z", readOnly=true),
     *            @OA\Property(property="updated_at", type="string", example="2023-10-30T15:52:55.000000Z", readOnly=true),
     *            @OA\Property(property="deleted_at", type="string", nullable=true, readOnly=true),
     *            @OA\Property(property="business_sector_id", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="id_external", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="created_by_user_id_external", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="questionnaire_id_external", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *            @OA\Property(property="business_sector_id_external", type="string", example="1", readOnly=true, maxLength=255, minLength=1)
     *        )
     *    )
     */
    public array $physical_risks;

    /**
     *    @OA\Property(
     *        property="taxonomy",
     *        format="object",
     *        readOnly=true,
     *        description="This property only has value when the questionnaire type name ( pt-PT ) is 'Taxonomia' ",
     *        nullable=true,
     *           @OA\Items(
     *               @OA\Property(property="id", type="string", example="1", readOnly=true, maxLength=255, minLength=50),
     *               @OA\Property(property="imported_file_url", type="string", example=null, nullable=true, readOnly=true),
     *               @OA\Property(property="created_at", type="string", example="2023-08-25T09:27:45.000000Z", readOnly=true),
     *               @OA\Property(property="updated_at", type="string", example="2023-09-14T08:41:48.000000Z", readOnly=true),
     *               @OA\Property(property="questionnaire_id", type="string", example="1", readOnly=true, maxLength=255, minLength=1),
     *               @OA\Property(
     *                   property="summary",
     *                   type="object",
     *                   @OA\Property(
     *                       property="total",
     *                       type="object",
     *                       @OA\Property(property="opex", type="integer" , example=18000, readOnly=true, minLength=1),
     *                       @OA\Property(property="capex", type="integer" , example=3000, readOnly=true, minLength=1),
     *                       @OA\Property(property="volume", type="integer" , example=10000, readOnly=true, minLength=1)
     *                   ),
     *                   @OA\Property(
     *                       property="eligible",
     *                       type="object",
     *                       @OA\Property(property="opex", type="integer", example=18000, readOnly=true, minLength=1),
     *                       @OA\Property(property="capex", type="integer", example=3000, readOnly=true, minLength=1),
     *                       @OA\Property(property="volume", type="integer", example=10000, readOnly=true, minLength=1)
     *                   ),
     *                   @OA\Property(
     *                       property="notEligible",
     *                       type="object",
     *                       @OA\Property(property="opex", type="integer", example=18000, readOnly=true, minLength=1),
     *                       @OA\Property(property="capex", type="integer", example=3000, readOnly=true, minLength=1),
     *                       @OA\Property(property="volume", type="integer", example=10000, readOnly=true, minLength=1)
     *                   ),
     *                   @OA\Property(
     *                       property="eligibleAligned",
     *                       type="object",
     *                       @OA\Property(property="opex", type="integer", example=18000, readOnly=true, minLength=1),
     *                       @OA\Property(property="capex", type="integer", example=3000, readOnly=true, minLength=1),
     *                       @OA\Property(property="volume", type="integer", example=10000, readOnly=true, minLength=1)
     *                   ),
     *                   @OA\Property(
     *                       property="eligibleNotAligned",
     *                       type="object",
     *                       @OA\Property(property="opex", type="integer", example=18000, readOnly=true, minLength=1),
     *                       @OA\Property(property="capex", type="integer", example=3000, readOnly=true, minLength=1),
     *                       @OA\Property(property="volume", type="integer", example=10000, readOnly=true, minLength=1)
     *                   )
     *               ),
     *               @OA\Property(
     *                   property="safeguard",
     *                   type="object",
     *                   @OA\Property(property="verified", type="integer", minLength=0, maxLength=1, example=0, readOnly=true),
     *                   @OA\Property(
     *                       property="questions",
     *                       type="array",
     *                       @OA\Items(
     *                           @OA\Property(property="id", type="string", example="1", readOnly=true, maxLength=255, minLength=50),
     *                           @OA\Property(property="text", type="string", example="Sample Question Text", maxLength=500, readOnly=true),
     *                           @OA\Property(
     *                               property="links",
     *                               type="array",
     *                               @OA\Items(
     *                                   @OA\Property(property="url", type="string", example="https://sample.url", maxLength=500, readOnly=true),
     *                                   @OA\Property(property="text", type="string", example="Sample Link Text", nullable=true, maxLength=255, readOnly=true)
     *                               ),
     *                               readOnly=true
     *                           ),
     *                           @OA\Property(
     *                               property="answer",
     *                               type="array",
     *                               @OA\Items(
     *                                   @OA\Property(property="text", type="string", example="Yes", maxLength=255, readOnly=true),
     *                                   @OA\Property(property="action", type="mixed", example="Sample Action", readOnly=true),
     *                                   @OA\Property(property="selected", type="string", example="Yes", nullable=true, maxLength=255, readOnly=true),
     *                                   @OA\Property(property="action_text", type="string", example="Sample Action Text", maxLength=500, readOnly=true)
     *                               ),
     *                               readOnly=true
     *                           ),
     *                           @OA\Property(property="enabled", type="integer", example=1, minLength=0, maxLength=1, readOnly=true),
     *                           @OA\Property(property="answered", type="integer", example=1, readOnly=true, minLength=0, maxLength=1),
     *                           @OA\Property(property="text_help", type="string", example="Sample help text", maxLength=1000, readOnly=true),
     *                           @OA\Property(property="answered_value", type="string", example="Sample answered value", maxLength=255, readOnly=true),
     *                           @OA\Property(property="id_external", type="string", example="123", maxLength=255, readOnly=true)
     *                       ),
     *                       readOnly=true
     *                   ),
     *                   @OA\Property(property="percentage", type="integer", example=0, minLength=0, maxLength=100, readOnly=true),
     *                   @OA\Property(property="arrayPosition", type="integer", minLength=0, example=0, readOnly=true)
     *               ),
     *               @OA\Property(property="completed", type="integer", minLength=0, maxLength=1, example=0, readOnly=true),
     *               @OA\Property(property="completed_at", type="string", example=null, nullable=true, readOnly=true),
     *               @OA\Property(property="started_at", type="string", example=null, nullable=true, readOnly=true),
     *               @OA\Property(property="deleted_at", type="string", example=null, nullable=true, readOnly=true),
     *               @OA\Property(
     *                   property="activities",
     *                   type="array",
     *                   @OA\Items(
     *                       @OA\Property(property="id", type="string", example="encrypted_id_value", readOnly=true),
     *                       @OA\Property(property="taxonomy_id", type="string", example="1", readOnly=true),
     *                       @OA\Property(property="business_activities_id", type="string", example="1", readOnly=true, minLength=1, maxLength=255),
     *                       @OA\Property(property="name", type="string", example="name", minLength=1, maxLength=255, readOnly=true),
     *                       @OA\Property(
     *                           property="summary",
     *                           type="object",
     *                           @OA\Property(property="opex", type="integer", minLength=1, example=4990, readOnly=true),
     *                           @OA\Property(property="capex", type="integer", minLength=1, example=876, readOnly=true),
     *                           @OA\Property(property="volume", type="integer", minLength=1, example=222, readOnly=true)
     *                       ),
     *                       @OA\Property(
     *                           property="contribute",
     *                           type="object",
     *                           @OA\Property(
     *                               property="data",
     *                               type="array",
     *                               @OA\Items(
     *                                   @OA\Property(property="verified", type="integer", example=1, readOnly=true, minLength=0, maxLength=1),
     *                                   @OA\Property(property="objective", type="string", example="Adaptação às alterações climáticas", minLength=1, maxLength=255, readOnly=true),
     *                                   @OA\Property(
     *                                       property="questions",
     *                                       type="array",
     *                                       @OA\Items(
     *                                           @OA\Property(property="sector", type="string", example="Ensino", minLength=1, maxLength=255, readOnly=true),
     *                                           @OA\Property(property="activity", type="string", example="Ensino", minLength=1, maxLength=255, readOnly=true),
     *                                           @OA\Property(property="category", type="string", example="Contribui substancialmente para um dos objetivos", readOnly=true,  minLength=1, maxLength=255),
     *                                           @OA\Property(
     *                                               property="question",
     *                                               type="object",
     *                                               @OA\Property(property="id", type="string", example="1", readOnly=true),
     *                                               @OA\Property(property="text", type="string", example="A atividade tem um impacto positivo substancial no ambiente?", readOnly=true, minLength=1, maxLength=255),
     *                                               @OA\Property(
     *                                                   property="links",
     *                                                   type="object",
     *                                                   @OA\Property(property="url", type="string", example="https://www.google.com", minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="text", type="string", example="Google", minLength=1, maxLength=255, readOnly=true),
     *                                               ),
     *                                               @OA\Property(
     *                                                   property="answer",
     *                                                   type="object",
     *                                                   @OA\Property(property="text", type="string", example="Sim", minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="action", type="integer", example=2, minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="selected", type="string", example="Sim", minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="action_text", type="string", example="A atividade tem um no ambiente?", minLength=1, maxLength=255, readOnly=true),
     *                                               ),
     *                                               @OA\Property(property="enabled", type="integer", example=1, minLength=0, maxLength=1, readOnly=true),
     *                                               @OA\Property(property="answered", type="integer", example=1, minLength=0, maxLength=1, readOnly=true),
     *                                               @OA\Property(property="text_help", type="string", example="A atividade tem um impacto positivo substancial no ambiente?", minLength=1, maxLength=5000, readOnly=true),
     *                                               @OA\Property(property="answered_value", type="string", example="Sim", minLength=1, maxLength=255, readOnly=true),
     *                                               @OA\Property(property="id_external", type="string", example="1", readOnly=true),
     *                                           ),
     *                                           @OA\Property(property="objective", type="string", example="Adaptação às alterações climáticas", readOnly=true, minLength=1, maxLength=255),
     *                                           @OA\Property(property="activity_code", type="integer", example=2, minLength=1, maxLength=255, readOnly=true),
     *                                       ),
     *                                   ),
     *                                   @OA\Property(property="percentage", type="integer", example=100, minLength=0, maxLength=100, readOnly=true),
     *                                   @OA\Property(property="arrayPosition", type="integer", minLength=0, example=0, readOnly=true)
     *                               )
     *              )
     *       )
     *       )
     *      ),
     *                   @OA\Property(property="dnsh", type="object",  @OA\Property(
     *                                property="data",
     *                                type="array",
     *                                @OA\Items(
     *                                    @OA\Property(property="verified", type="integer", minLength=0, maxLength=1, example=null, readOnly=true),
     *                                   @OA\Property(property="objective", type="string", example=null, minLength=1, maxLength=255, readOnly=true),
     *                                   @OA\Property(
     *                                       property="questions",
     *                                       type="array",
     *                                       @OA\Items(
     *                                           @OA\Property(property="sector", type="string", example="Ensino", minLength=1, maxLength=255, readOnly=true),
     *                                           @OA\Property(property="activity", type="string", example="Ensino", minLength=1, maxLength=255, readOnly=true),
     *                                           @OA\Property(property="category", type="string", example="Contribui substancialmente para um dos objetivos", minLength=1, maxLength=255, readOnly=true),
     *                                           @OA\Property(
     *                                               @OA\Property(property="id", type="string", example="1", readOnly=true),
     *                                               @OA\Property(property="text", type="string", example="A atividade tem um impacto positivo substancial no ambiente?", minLength=1, maxLength=255, readOnly=true),
     *                                               @OA\Property(
     *                                                   property="links",
     *                                                   type="object",
     *                                                   @OA\Property(property="url", type="string", example="https://www.google.com", minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="text", type="string", example="Google", minLength=1, maxLength=255, readOnly=true),
     *                                               ),
     *                                               @OA\Property(
     *                                                   property="answer",
     *                                                   type="object",
     *                                                   @OA\Property(property="text", type="string", example="Sim", minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="action", type="integer", example=2, minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="selected", type="string", example="Sim", minLength=1, maxLength=255, readOnly=true),
     *                                                   @OA\Property(property="action_text", type="string", example="A atividade tem um no ambiente?", minLength=1, maxLength=255, readOnly=true),
     *                                               ),
     *                                               @OA\Property(property="enabled", type="integer", example=1, minLength=0, maxLength=1, readOnly=true),
     *                                               @OA\Property(property="answered", type="integer", example=1, minLength=0, maxLength=1, readOnly=true),
     *                                               @OA\Property(property="text_help", type="string", example="A atividade tem um impacto positivo substancial no ambiente?", minLength=1, maxLength=5000, readOnly=true),
     *                                               @OA\Property(property="answered_value", type="string", example="Sim", minLength=1, maxLength=255, readOnly=true),
     *                                               @OA\Property(property="id_external", type="string", example="1", readOnly=true),
     *                                           ),
     *                                           @OA\Property(property="objective", type="string", example="Adaptação às alterações climáticas", readOnly=true, minLength=1, maxLength=255),
     *                                           @OA\Property(property="activity_code", type="integer", example=2, minLength=1, maxLength=255, readOnly=true),
     *                                       ),
     *                                   ),
     *                                   @OA\Property(property="percentage", type="integer", minLength=0, maxLength=100, example=null, readOnly=true),
     *                                   @OA\Property(property="arrayPosition", type="integer", minLength=0, example=null, readOnly=true),
     *                                )
     *                            )),
     *                       @OA\Property(property="id_external", type="string", example="123", minLength=1, maxLength=255, readOnly=true),
     *                      @OA\Property(property="questionnaire_id_external", type="string", example="123", minLength=1, maxLength=255, readOnly=true)
     *      )
     *      )
     */
    public array $taxonomy;
    /**
     * @OA\Property(
     *  property="questions", format="array", type="array" , description="questions",
     *  readOnly=true,
     *  @OA\Items(
     *    @OA\Property(property="id", format="string", type="string" , description="The question id", example="1", readOnly=true, maxLength=255, minLength=50),
     *    @OA\Property(property="key", format="string", type="string" , description="key", example="1", readOnly=true, maxLength=255, minLength=1),
     *    @OA\Property(property="category_id", format="string", type="string" , description="category_id", example="1", readOnly=true, maxLength=255, minLength=1),
     *    @OA\Property(property="category_id_external", format="string", type="string" , description="category_id", example="1", readOnly=true, maxLength=255, minLength=1),
     *    @OA\Property(
     *      property="text",
     *      type="object",
     *      readOnly="true",
     *      description="The question text",
     *        @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1, readOnly=true),
     *        @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1, readOnly=true),
     *        @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1, readOnly=true),
     *        @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1, readOnly=true),
     *  ),
     * @OA\Property(
     *    property="answer", format="object", type="object" , description="The question answer", readOnly=true,
     *      @OA\property(property="answerInfo", type="array" , description="object optional", readOnly="true",
     *              @OA\Items(
     *                @OA\Property(
     *                  property="key",
     *                  type="string",
     *                  description="string",
     *                  maxLength=3000, readOnly=true
     *                ),
     *                @OA\Property(
     *                  property="text",
     *                  type="string",
     *                  description="string",
     *                  maxLength=3000, readOnly=true
     *            ),
     *        ),
     *     ),
     *     @OA\property(property="text", format="string", type="string" , description="answer text", example="yes", readOnly="true", maxLength=3000, readOnly=true),
     *     ),
     *     @OA\Property(property="not_reported", format="boolean", type="boolean" , description="The question not_reported", example="true", readOnly=true),
     *     @OA\Property(property="not_applicable", format="boolean", type="boolean" , description="The question not_applicable", example="true", readOnly=true),
     *     @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *   ),
     * )
     */
    public array $questions;

    /**
     * @OA\Property(
     *  property="initiatives",
     *  type="array",
     *  readOnly=true,
     *   @OA\Items(
     *          @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly="true", minLength=1, maxLength=255),
     *           @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *          @OA\Property(
     *              property="name",
     *                  type="object",
     *                  readOnly="true",
     *                  description="The name object",
     *                      @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *                      @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *                      @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *                      @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *                  ),
     *     )
     * )
     */
    public array $initiatives;

    /** @OA\Property(property="data", format="object", type="object" , description="Contains virtual columns for the questionnaire", example={}, readOnly=true )  */
    public string $data;

    /**
     * @OA\Property(
     *  property="sdgs",
     *  type="array",
     *  readOnly=true,
     *   @OA\Items(
     *          @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly="true", minLength=1, maxLength=255),
     *           @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *          @OA\Property(
     *              property="name",
     *                  type="object",
     *                  readOnly="true",
     *                  description="The name object",
     *                      @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *                      @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *                      @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *                      @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *                  ),
     *     )
     * )
     */
    public array $sdgs;

    /** @OA\Property(property="maturity", format="string", type="string" , description="The questionnaire maturity", readOnly=true, maxLength=255, minLength=1 )  */
    public float $maturity;

    /** @OA\Property(property="completed_at", format="date-time", type="string" , description="The questionnaire completed at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255 )  */
    public string $completed_at;

    /** @OA\Property(property="submitted_at", format="date-time", type="date-time" , description="The questionnaire submitted at date ( if is not null , the questionnaire is done)", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255)  */
    public string $submitted_at;

    /** @OA\Property(property="result_at", format="date-time", type="date-time" , description="The questionnaire result at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255)  */
    public string $result_at;

    /** @OA\Property(property="time_to_complete", format="integer", type="integer" , description="The questionnaire time to complete", example=1, nullable=true, readOnly=true, minimum = 0, maximum=99999999999 )  */
    public int $time_to_complete;

    /** @OA\Property(property="created_at", format="date-time", type="date-time" , description="The data created at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255 )  */
    public string $created_at;

    /** @OA\Property(property="updated_at", format="date-time", type="date-time" , description="The data updated at date", example="1997-01-01T00:00:00.000000Z", readOnly=true, maxLength=255 )  */
    public string $updated_at;

    /** @OA\Property(property="questionnaire_type_id", format="string", type="string" ,example="1",  description="Questionnaire type id", readOnly=true, maxLength=255, minLength=50 )  */
    public string $questionnaire_type_id;

    /** @OA\Property(property="updated_by_user_id", format="string", type="string" , example="1", description="The questionnaire updated by user id", readOnly=true,  maxLength=255, minLength=50 )  */
    public int $updated_by_user_id;

    /** @OA\Property(property="last_question_id", format="string", type="string" , example="1",  description="The last question id", readOnly=true,  maxLength=255, minLength=50 )  */
    public int $last_question_id;

    /**
     * @OA\Property(
     *  property="company",
     * type="object",
     * readOnly=true,
     *  @OA\Property(property="id", type="string", format="string", description="Resource internal ID", example="1", readOnly="true", minLength=1, maxLength=255),
     *  @OA\Property(property="name", type="string", example="string", format="string", description="name", minLength=1, maxLength=255, readOnly=true),
     *  @OA\Property(property="data", format="object", type="object" , example={}, readOnly=true ),
     *  @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     * )
     */
    public array $company;

    /**
     * @OA\Property(
     *  property="type",
     * type="object",
     * readOnly=true,
     *  @OA\Property(property="id", type="string" , format="string", description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50),
     *  @OA\Property(
     *   property="name",
     *   type="object",
     *   readOnly="true",
     *   description="The name object",
     *              @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *  ),
     *  @OA\Property(property="data", format="object", type="object" , description="The data", example={}, readOnly=true ),
     *  @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),

     * )
     */
    public array $type;

    /** @OA\Property(property="id_external", type="string" , format="string", description="The external id", example=1, maxLength=64, minLength=30 )  */
    public int $id_external;

    /** @OA\Property(property="parent_id", type="string" , format="string", description="The parent id", example=1, maxLength=255, minLength=50 )  */
    public int $parent_id;

    /** @OA\Property(property="parent_id_external", type="string" , format="string", description="The parent external id", example=1, maxLength=64, minLength=30 )  */
    public int $parent_id_external;

    /** @OA\Property(property="questionnaire_type_id_external", type="string" , format="string", description="The questionnaire type external id", example=1, maxLength=64, minLength=30 )  */
    public int $questionnaire_type_id_external;

    /** @OA\Property(property="created_by_user_id_external", type="string" , format="string", description="The created by user id external id", example=1, maxLength=64, minLength=30 )  */
    public int $created_by_user_id_external;

    /** @OA\Property(property="updated_by_user_id_external", type="string" , format="string", description="The udpated by user id external id", example=1, maxLength=64, minLength=30 )  */
    public int $updated_by_user_id_external;

    /** @OA\Property(property="company_id_external", type="string" , format="string", description="The  company external id", example=1, maxLength=64, minLength=30 )  */
    public int $company_id_external;

    /** @OA\Property(property="last_question_id_external", type="string" , format="string", description="The last question external id", example=1, maxLength=64, minLength=30 )  */
    public int $last_question_id_external;

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

    /**
     *  @OA\Schema(
     *     schema="QuestionnaireResponse",
     *     title="QuestionnaireResponse",
     *     description="QuestionnaireResponse",
     *     @OA\Property(
     *        property="data",
     *        type="array",
     *        @OA\Items(ref="#/components/schemas/QuestionnaireFormRequest")
     *   )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Schema(
     *    schema="QuestionnaireDataResponse",
     *    title="QuestionnaireDataResponse",
     *    description="QuestionnaireDataResponse",
     *    @OA\Property(
     *      property="data",
     *      type="array",
     *       @OA\Items(
     *         type="object",
     *          @OA\Property(property="id", type="string" , format="string", description="Resource internal ID", example="1", readOnly="true", maxLength=255, minLength=50) ,
     *          @OA\Property(property="indicator_id", type="string" , format="string", description="The indicator id", example=1 , readOnly="true", maxLength=255, minLength=50) ,
     *          @OA\Property(
     *              property="indicator_name",
     *              type="object",
     *              readOnly="true",
     *              @OA\Property(property="en", type="string", format="string", example="The text in EN", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="es", type="string", format="string", example="The text in ES", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-BR", type="string", format="string", example="The text in pt-BR", readOnly="true", maxLength=255, minLength=1),
     *              @OA\Property(property="pt-PT", type="string", format="string", example="The text in pt-PT", readOnly="true", maxLength=255, minLength=1),
     *          ),
     *          @OA\Property(property="answer", type="string" , format="string", description="The question answer", example="Lorem ipsum" , readOnly="true", maxLength=99999, minLength=0) ,
     *          @OA\Property(property="reported_at", format="date", type="string" , description="The data reported at date", example="2023-01-01T00:00:00.000000Z", readOnly="true", maxLength=255, minLength=0) ,
     *          @OA\Property(property="id_external", format="string", type="string" , description="id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *          @OA\Property(property="indicator_id_external", format="string", type="string" , description="indicator id external", example="1", readOnly=true, maxLength=64, minLength=30),
     *        )
     *     )
     * )
     */
    public function dataShow()
    {
    }

    /**
     * @OA\Schema(
     *     schema="QuestionnaireDeleteResponse",
     *     title="QuestionnaireDeleteResponse",
     *     description="QuestionnaireDeleteResponse",
     *     @OA\Property(
     *         property="error",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The Questionnaire was successfully deleted ❗"
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
     * Set the validation rules that apply to the request.
     */
    public function store(): array
    {
        $countriesList = getCountriesForSelect();
        $countriesIdsList = array_column($countriesList, 'id');

        $rules = [
            'company_id' => [
                'exists:companies,id',
            ],
            'type_id' => [
                'exists:questionnaire_types,id',
            ],
            'from' => ['date'],
            'to' => ['date'],
            'countries' => [Rule::in($countriesIdsList)],
        ];

        if (!$this->questionnaire) {
            $rules['company_id'][] = 'required';
            $rules['type_id'][] = 'required';
            $rules['countries'][] = 'required';
            $rules['from'][] = 'required';
            $rules['to'][] = 'required';
        }

        return $rules;
    }

    /**
     * Set the validation rules that apply to the update request.
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
            'type_id.required' => __('The :attribute field is required.'),
            'countries.required' => __('The :attribute field is required.'),
            'from.required' => __('The :attribute field is required.'),
            'to.required' => __('The :attribute field is required.'),
            'company_id.exists' => __('The selected :attribute is invalid.'),
            'type_id.exists' => __('The selected :attribute is invalid.'),
            'countries.in' => __('The selected :attribute is invalid.'),
            'from.date' => __('The :attribute is not a valid date.'),
            'to.date' => __('The :attribute is not a valid date.'),
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
            'type_id' => __('Type'),
            'countries' => __('Countries'),
            'from' => __('From'),
            'to' => __('To'),
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

        if ($this->has('type_id')) {
            $mergeArr['type_id'] = $this->get('type_id') ?
                decryptValue($this->get('type_id')) : null;
        }
        $this->merge($mergeArr);
    }
}
