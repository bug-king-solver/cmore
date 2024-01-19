<?php

namespace Database\Seeders\Tenant\QuestionOptions;

use Database\Seeders\Tenant\DataSeeder;

class SimpleSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'question_options/simple.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\QuestionOptions\Simple::class;

    /**
     * False if table doesnt have enable column
     */
    protected $hasEnabledColumn = false;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'label' => 'label',
        'value' => 'value',
    ];
}
