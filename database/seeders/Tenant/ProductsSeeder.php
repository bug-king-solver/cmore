<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Product;
use App\Models\Tenant\QuestionnaireType;
use Database\Seeders\Tenant\DataSeeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'products.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\Product::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'category_id' => 'category_id',
        'productable_type' => 'productable_type',
        'productable_id' => 'productable_id',
        'icon' => 'icon',
        'title' => 'title',
        'description' => 'description',
        'price' => 'price',
        'data' => 'data',
    ];

    /**
     * Method to run as seeder callback
     */
    public function seederCallback(): void
    {
        // By default all the new questionnaire types must be disabled.
        if ($this->idsCreated) {
            Product::whereIn('id', $this->idsCreated ?? [])->update(['enabled' => false]);
        }
    }
}
