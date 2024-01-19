<?php

namespace Database\Seeders\Tenant;

class CategoryQuestionnaireTypeSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'category_questionnaire_type.json';


    /**
     * False if table doesnt have enable column
     */
    protected $hasEnabledColumn = false;

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\CategoryQuestionnaireType::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'questionnaire_type_id' => 'questionnaire_type_id',
        'category_id' => 'category_id',
    ];
}
