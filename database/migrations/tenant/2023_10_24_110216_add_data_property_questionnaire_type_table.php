<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\QuestionnaireType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        QuestionnaireType::where('id', 17)
            ->withoutGlobalScopes()
            ->update(['data->can_duplicate' => false]);

        QuestionnaireType::where('id', '!=' ,17)
            ->withoutGlobalScopes()
            ->update(['data->can_duplicate' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
