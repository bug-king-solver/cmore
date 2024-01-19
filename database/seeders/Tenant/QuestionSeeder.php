<?php

namespace Database\Seeders\Tenant;

class QuestionSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'questions.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\Question::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'parent_id' => 'parent_id',
        'questionnaire_type_id' => 'questionnaire_type_id',
        'category_id' => 'category_id',
        'order' => 'order',
        'weight' => 'weight',
        'answer_type' => 'answer_type',
        'description' => 'description',
        'information' => 'information',
        'data' => [
            'business_sector_only' => 'sector_only',
            'business_sector_except' => 'sector_except',
            'size_only' => 'company_size_only',
            'size_except' => 'company_size_except',
        ],
        'deleted_at' => 'deleted_at',
        'allow_not_applicable' => 'allow_not_applicable',
        'allow_not_reportable' => 'allow_not_reportable',
    ];

    /**
     * Default values when the column is empty
     */
    protected $default = [
        'order' => 0,
        'weight' => 0,
    ];
}
