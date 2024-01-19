<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\QuestionnaireType;
use Database\Seeders\Tenant\DataSeeder;
use Illuminate\Support\Facades\DB;

class QuestionnaireTypeSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'questionnaire_types.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\QuestionnaireType::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'name' => 'name',
        'data' => 'data',
        'slug' => 'slug',
        'note' => 'note',
        'has_score' => 'has_score',
    ];

    /**
     * Method to run as seeder callback
     */
    public function seederCallback(): void
    {
        // By default all the new questionnaire types must be disabled.
        if ($this->idsCreated) {
            QuestionnaireType::whereIn('id', $this->idsCreated ?? [])->update(['enabled' => false]);
        }
    }
}
