<?php

namespace Database\Seeders\Tenant;

class QuestionOptionsSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'question_options.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\QuestionOption::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'question_option_id' => 'question_option_id',
        'question_option_type' => 'question_option_type',
        'question_id' => 'question_id',
        'initiative_id' => 'initiative_id',
        'indicator_id' => 'indicator_id',
        'sdg_id' => 'sdg_id',
        'order' => 'order',
        'weight' => 'weight',
        'comment_required' => 'comment_required',
        'attachment_required' => 'attachment_required',
        'children_action' => 'children_action',
        'deleted_at' => 'deleted_at',
    ];

    /**
     * Default values when the column is empty
     */
    protected $default = [
        'order' => 0,
        'weight' => 0,
        'comment_required' => false,
        'attachment_required' => false,
    ];
}
