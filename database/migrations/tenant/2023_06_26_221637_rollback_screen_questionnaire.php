<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\CategoryQuestionnaireType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CategoryQuestionnaireType::whereIn('id', [102, 103, 104])->forceDelete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
