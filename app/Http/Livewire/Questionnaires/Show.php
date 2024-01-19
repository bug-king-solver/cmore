<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Category;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Userable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Questionnaire $questionnaire;

    public ?Collection $questions;

    public int $questionHighlighted;

    public null | array $assignment_type;

    public null | int | Category $category;

    public int $assigner;

    public array | null $allowedCategories; // If null, all available

    public Category $mainCategorySelected;

    public Category $categorySelected;

    public ?Category $categoryPrevious;

    public ?Category $categoryNext;

    public $filteredQuestionareCategory;

    public $answered_questionsByCategory;

    public $questionsByCategory;

    public $status_filter = '';

    public $assignee_filter = '';

    public $validator_filter = '';

    public $firstSubCategory;

    public $childCategoryProgress = 0;

    public $categoryFirstQuestion;

    protected $listeners = ['show::answered_count_changed' => 'changeAnswerCount'];

    public function mount(Questionnaire $questionnaire, $category = 0, $questionHighlighted = 0, $assigner = 0)
    {
        if (! $this->questionnaire->is_ready) {
            return false;
        } elseif (! $this->questionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        $categories = collect($this->questionnaire->categories)->keyBy('id');

        if ($categories->count()) {
            $selectedCategory = $categories->where('id', $category)->first();

            if (! $selectedCategory) {

                $lastAnsweredCategory = $categories->sortBy('order')->first();
                foreach ($categories->sortBy('order') as $categorie) {
                    if ($categorie['progress'] > 0) {
                        $lastAnsweredCategory = $categorie;
                    }
                }

                $selectedCategory = $categories->where('parent_id', $lastAnsweredCategory['id'])->sortBy('order')->first()
                    ?: $lastAnsweredCategory;

                return redirect(route(
                    'tenant.questionnaires.show',
                    ['questionnaire' => $this->questionnaire->id, 'category' => $selectedCategory['id']]
                ));
            }

            $selectedCategory = Category::find($selectedCategory['id']);

            if ($selectedCategory && $selectedCategory->children->count()) {
                return redirect(route(
                    'tenant.questionnaires.show',
                    [
                        'questionnaire' => $this->questionnaire->id,
                        'category' => $selectedCategory->children->first()->id,
                    ]
                ));
            }
        }

        $this->questionnaire = $questionnaire;
        $this->category = $selectedCategory ?? null;

        if ($questionHighlighted) {
            $this->questionHighlighted = (int) $questionHighlighted;
        }

        if (! $this->questionnaire->is_ready) {
            return false;
        }

        if (! $this->questionnaire->categories) {
            $this->questions = $this->questionnaire->questions();

            return false;
        }

        $this->allowedCategories = ! tenant()->onActiveSubscription
            ? [4, 13, 14]
            : null;

        if (! $this->category || ! $this->category->exists) {
            return redirect()
                ->route('tenant.questionnaires.show', [
                    'questionnaire' => $questionnaire,
                    'category' => $this->questionnaire->categories[0]['id'],
                ]);
        }

        $this->categorySelected = Category::getAllowedCategory($this->category, $this->allowedCategories);
        $this->questionnaire->current_category = $this->categorySelected;

        $this->questions = $this->questionnaire->questions();

        $menu = $this->questionnaire->menu();
        $this->categoryPrevious = $menu['previous'] ?? null;
        $this->categoryNext = $menu['next'] ?? null;

        $this->answered_questionsByCategory = Category::find($this->categorySelected->id)
            ->answersCount($this->questionnaire->id);

        $targetId = $this->categorySelected->id;

        // Use array_filter() with a callback function to filter the array
        $this->filteredQuestionareCategory = array_filter(
            $this->questionnaire->categories,
            function ($subarray) use ($targetId) {
                return $subarray['id'] == $targetId;
            }
        );

        if ($this->categorySelected->parent) {
            $this->firstSubCategory = $this->categorySelected->parent->children[0];
        }

        $this->childCategoryProgress = round(
            $menu['children_categories']->where('id', $targetId)
                ->first()->progress ?? 0
        );

        $this->filteredQuestionareCategory = array_values($this->filteredQuestionareCategory);

        $this->categoryFirstQuestion = $this->questions->first();

        $this->questionsByCategory = isset($this->filteredQuestionareCategory[0]) ? $this->filteredQuestionareCategory[0]['questions_count'] : 0;
    }

    public function render(): View
    {
        if (! $this->questionnaire->is_ready) {
            return view('livewire.tenant.questionnaires.not-ready');
        }

        return view('livewire.tenant.questionnaires.show');
    }

    /**
     * Change the count of questionnaire's answered questions in terms of category.
     * @param int $categoryId
     * @param int $questionnaireId
     */
    public function changeAnswerCount(int $categoryId, int $questionnaireId)
    {
        $this->answered_questionsByCategory = Category::withoutGlobalScopes()
        ->whereId($categoryId)
        ->first()
        ->answersCount($questionnaireId);

        $targetId = $this->categorySelected->id;

        $filteredQuestionareCategory = array_filter(
            $this->questionnaire->categories,
            function ($subarray) use ($targetId) {
                return $subarray['id'] == $targetId;
            }
        );

        $filteredQuestionareCategory = array_values($filteredQuestionareCategory);
        $this->questionsByCategory = isset($filteredQuestionareCategory[0]) ? $filteredQuestionareCategory[0]['questions_count'] : 0;

    }

    /**
     * Get the current category of questionnaire through the categories that are selected
     */
    private function regenerateCategories()
    {
        if ($this->questionnaire->categories) {
            $this->allowedCategories = ! tenant()->onActiveSubscription
                ? [4, 13, 14]
                : null;
            $this->categorySelected = Category::getAllowedCategory($this->category, $this->allowedCategories);
            $this->questionnaire->current_category = $this->categorySelected;
        }
    }
}
