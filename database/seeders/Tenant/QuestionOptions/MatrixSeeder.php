<?php

namespace Database\Seeders\Tenant\QuestionOptions;

use Database\Seeders\Tenant\DataSeeder;

class MatrixSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'question_options/matrix.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\QuestionOptions\Matrix::class;

    /**
     * False if table doesnt have enable column
     */
    protected $hasEnabledColumn = false;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'x' => 'x',
        'y' => 'y',
    ];
}
