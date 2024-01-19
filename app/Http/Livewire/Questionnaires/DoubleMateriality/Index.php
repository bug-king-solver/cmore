<?php

namespace App\Http\Livewire\Questionnaires\DoubleMateriality;

use App\Models\Tenant\Category;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Double Materiality Index Livewire Component.
 * @description: Its a new questionnaire that has a structure of 3 levels of categories.
 * The levels are: 1. Category, 2. Subcategory, 3. Subcategory of the subcategory.
 * The level 1 dont have parent or questions
 * The level 2 have parent and questions
 * The level 3 have parent and questions , but the questions only are showed when all questions of the level 2 are answered.
 * @property Questionnaire $questionnaire
 * @return View
 */
class Index extends Component
{
    public Questionnaire $questionnaire;

    public $originalQuestions;
    public $questions;
    public $categories;
    public $categoryActiveChildrens;
    public $questionHighlighted = 0;

    public $categoryFirstLevel;
    public $categorySecondLevel;
    public $categoryThirtyLevel;

    protected $listeners = [
        'doublemateriality::refresh' => '$refresh',
        'updateCategoryChildrenShow' => 'updateCategoryChildrenShow',
        'nextFirstLevel' => 'nextFirstLevel',
        'prevFirstLevel' => 'prevFirstLevel',
        'goToSubCategory' => 'goToSubCategory',
        'goToThirtyCategory' => 'goToThirtyCategory',
    ];

    /**
     * Mount the component.
     */
    public function mount(Questionnaire $questionnaire, $questionHighlighted = 0)
    {
        $this->questionnaire = $questionnaire;

        if (! $this->questionnaire->is_ready) {
            return false;
        } elseif (! $this->questionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        $this->categories = $this->questionnaire->getCategoriesRecursive();

        $this->categories[0]['active'] = true;
        $this->categories[0]['childrens'][0]['active'] = true;


        if ($questionHighlighted) {
            $this->questionHighlighted = (int) $questionHighlighted;
        }

        $this->categoryFirstLevel = collect($this->categories)
            ->where('active', true)
            ->first();

        $this->categoryFirstLevel['childrens'][0]['active'] = true;

        $this->categorySecondLevel = $this->categories[0]['childrens'][0];
        $this->categorySecondLevel['childrens'][0]['active'] = true;
        $this->categoryThirtyLevel = null;
    }


    /**
     * Render the component.
     */
    public function render(): View
    {
        if (!$this->questionnaire->is_ready) {
            return view('livewire.tenant.questionnaires.not-ready');
        }

        $this->questionnaire->current_category = $this->categorySecondLevel['id'];
        $this->questions = $this->questionnaire->questions();

        $allQuestionsAnswered  = $this->categoryHasAllQuestionsAnswered($this->questions, $this->categorySecondLevel);
        $this->categoryThirtyLevel = null;

        if ($allQuestionsAnswered) {
            $this->categoryThirtyLevel = collect($this->categorySecondLevel['childrens'])
                ->where('active', true)->first() ?? $this->categorySecondLevel['childrens'][0];

            $this->questionnaire->current_category = $this->categoryThirtyLevel['id'];
            $this->questions = $this->questionnaire->questions();
        }

        return view('livewire.tenant.questionnaires.doubleMateriality.index');
    }

    public function categoryHasAllQuestionsAnswered($questions, $category)
    {
        $questionsAnswered = $questions->filter(function ($question) {
            return $question['answer']['value'] != null;
        });

        if ($questionsAnswered->count() === $questions->where('category_id', $category['id'])->count()) {
            $allQuestionsNo = $questionsAnswered->every(function ($question) {
                return $question['answer']['value'] === 'no';
            });

            if ($allQuestionsNo) {
                return false;
            }
        }

        return $questionsAnswered->count() === $questions->where('category_id', $category['id'])->count();
    }

    /**
     * Category first level navigation:: next.
     * @param int $categoryId
     */
    public function nextFirstLevel(int $categoryId): void
    {
        $this->firstLevelNavigation($categoryId, '>');
    }

    /**
     * Category first level navigation:: previous.
     * @param int $categoryId
     */
    public function prevFirstLevel(int $categoryId): void
    {
        $this->firstLevelNavigation($categoryId, '<');
    }

    /**
     * Update the first level
     */
    public function goToCategory(int $category)
    {
        $this->firstLevelNavigation($category, '=');
    }

    /**
     * Rule with the logic to navigate between categories.
     * @param int $categoryId
     * @param string $condition
     */
    public function firstLevelNavigation(int $categoryId, string $condition): void
    {
        foreach ($this->categories as &$category) {
            $category['active'] = false;
        }

        $searchCategory  = collect($this->categories)
            ->where('id', $condition, $categoryId)
            ->keys();

        if ($condition == '<') {
            $searchCategory = $searchCategory->last();
        } else {
            $searchCategory = $searchCategory->first();
        }

        if (isset($this->categories[$searchCategory])) {
            $this->categories[$searchCategory]['active'] = true;
            $this->categories[$searchCategory]['childrens'][0]['active'] = true;
            $this->categoryFirstLevel = $this->categories[$searchCategory];
            $this->categorySecondLevel = $this->categoryFirstLevel['childrens'][0];
        }
    }

    /**
     * Update the second level
     */
    public function goToSubCategory(int $categoryId)
    {
        foreach ($this->categoryFirstLevel['childrens'] as &$category) {
            $category['active'] = false;
        }

        $searchCategory = collect($this->categoryFirstLevel['childrens'])
            ->where('id', $categoryId)
            ->first();

        foreach ($this->categoryFirstLevel['childrens'] as &$category) {
            if ($category['id'] == $searchCategory['id']) {
                $category['active'] = true;
                $this->categorySecondLevel = $category;
            }
        }

        $this->categorySecondLevel['childrens'][0]['active'] = true;
    }

    public function goToThirtyCategory(int $categoryId)
    {
        foreach ($this->categorySecondLevel['childrens'] as &$category) {
            $category['active'] = false;
        }

        $searchCategory = collect($this->categorySecondLevel['childrens'])
            ->where('id', $categoryId)
            ->first();

        foreach ($this->categorySecondLevel['childrens'] as &$category) {
            if ($category['id'] == $searchCategory['id']) {
                $category['active'] = true;
                $this->categoryThirtyLevel = $category;
            }
        }
    }
}
