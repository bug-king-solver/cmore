<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\Scopes\EnabledScope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_questionnaire_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QuestionnaireType::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->softDeletes();
        });

        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            $questionnaireType = QuestionnaireType::withoutGlobalScope(EnabledScope::class)->find(1);

            if ($questionnaireType) {
                $questionnaireType->defaultCategories()->attach(range(1, 17));
                Questionnaire::where('questionnaire_type_id', 2)
                    ->update(['categories' => []]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_questionnaire_type');

        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
