<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\CategoryQuestionnaireType;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
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
        $this->refactorCategory();
        $this->updateCategoryIdInQuestionnaire();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }

    /**
     * Refactor category
     */
    public function refactorCategory()
    {
        $questionTypeCats = CategoryQuestionnaireType::orderBy('category_id', 'asc')
            ->orderBy('questionnaire_type_id', 'asc')
            ->get();

        foreach ($questionTypeCats as $questionTypeCat) {
            $category = Category::withoutGlobalScopes()->find($questionTypeCat->category_id);
            if (!$category) {
                continue;
            }

            if ($category->model_id == null) {
                $category->model_id = $questionTypeCat->questionnaire_type_id;
                $category->model_type = QuestionnaireType::class;
            }
            if ($category->extra == null || !isset($category->extra['original_id'])) {
                $category->extra = ['original_id' => $category->id];
            }

            $category->save();

            $newCategory = $category->replicate();

            $newCategory->model_id = $questionTypeCat->questionnaire_type_id;
            $newCategory->model_type = QuestionnaireType::class;

            if ($category->parent_id) {
                $newParentCategory = Category::whereJsonContains('extra', ['original_id' => $category->parent_id])
                    ->where('model_id', $questionTypeCat->questionnaire_type_id)
                    ->where('model_type', QuestionnaireType::class)
                    ->orderBy('id', 'asc')
                    ->first();
                $newCategory->parent_id = $newParentCategory->id ?? null;
            }

            $categoryAlreadyExists = Category::whereJsonContains('name', ['en' => $category->name])
                ->whereJsonContains('extra', ['original_id' => $category->extra['original_id']])
                ->where('model_id', $questionTypeCat->questionnaire_type_id)
                ->where('model_type', QuestionnaireType::class)
                ->where('enabled', $category->enabled)
                ->first();

            if (!$categoryAlreadyExists) {
                $newCategory->save();
            }
        }
    }

    /**
     * Update category_id in questionnaire
     */
    public function updateCategoryIdInQuestionnaire()
    {
        $questions = Question::whereNotNull('category_id')->orderBy('id', 'asc')->get();
        foreach ($questions as $question) {
            $newCategoryForQuestion = Category::where('model_id', $question->questionnaire_type_id)
                ->where('model_type', QuestionnaireType::class)->whereJsonContains('extra', ['original_id' => $question->category_id]);

            if ($newCategoryForQuestion->exists()) {
                $newCategoryForQuestion = $newCategoryForQuestion->first();
                $question->category_id = $newCategoryForQuestion->id;
                $question->save();
            }
        }


        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            $questionaries = Questionnaire::orderBy('id', 'asc')
                ->whereIn('questionnaire_type_id', [7, 8, 9])
                ->get();

            foreach ($questionaries as $questionnaire) {
                $categoriesArr = $questionnaire->categories;
                $questionsArr = $questionnaire->questions;

                if (!empty($categoriesArr)) {
                    foreach ($categoriesArr as $key => $categoryArr) {
                        $newCategory = Category::where('model_id', $questionnaire->questionnaire_type_id)
                            ->where('model_type', QuestionnaireType::class)->whereJsonContains('extra', ['original_id' => $categoryArr['id']])->first()->toArray();
                        $categoriesArr[$key] = array_replace($categoriesArr[$key], $newCategory);
                    }
                }

                if (!empty($questionsArr)) {
                    foreach ($questionsArr as $key => $questionArr) {
                        if ($questionArr['category_id'] != "") {
                            $newCategory = Category::where('model_id', $questionnaire->questionnaire_type_id)
                                ->where('model_type', QuestionnaireType::class)->whereJsonContains('extra', ['original_id' => $questionArr['category_id']])->first();
                            $questionsArr[$key]['category_id'] = $newCategory
                                ? $newCategory->id
                                : '';
                        }
                    }
                }

                $questionnaire->categories = $categoriesArr;
                $questionnaire->questions = $questionsArr;
                $questionnaire->save();
            }
        }
    }
};
