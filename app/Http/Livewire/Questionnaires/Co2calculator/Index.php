<?php

namespace App\Http\Livewire\Questionnaires\Co2calculator;

use App\Models\Tenant\Questionnaire;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public Questionnaire $questionnaire;

    public $questions;
    public $categories;
    public $questionHighlighted = 0;

    public $categoryActive = [];
    public $subCategorieActive = [];

    protected $listeners = [
        'co2Calculator::refresh' => '$refresh',
        'updateCategoryChildrenShow' => 'updateCategoryChildrenShow',
        'nextCategoryActive' => 'nextCategoryActive',
        'previousCategoryActive' => 'previousCategoryActive',
    ];

    /**
     * Mount the component.
     */
    public function mount(Questionnaire $questionnaire, $questionHighlighted = 0)
    {

        $this->questionnaire = $questionnaire;

        if (!$this->questionnaire->is_ready) {
            return false;
        } elseif (!$this->questionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        $categories = collect(array_values($this->questionnaire->categories));
        $i = 0;

        $this->categories = $categories->map(function ($category) use ($categories, &$i) {

            $category['name'] = translateJson($category['name']);
            $category['description'] = translateJson($category['description']);

            $category['active'] = $i == 0
                ? true
                : false;
            $i++;
            return $category;
        });

        $this->categories = $this->categories->map(function ($category) {
            $temp = 0;
            $category['childrens'] = $this->categories->where('parent_id', $category['id'])
                ->map(function ($child) use ($category, &$temp) {
                    $child['active'] = $temp == 0
                        ? true
                        : false;
                    $temp++;
                    return $child;
                })->toArray();

            return $category;
        })->filter(function ($category) use ($categories) {
            return !$category['parent_id'];
        })->toArray();

        if ($questionHighlighted) {
            $this->questionHighlighted = (int) $questionHighlighted;
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        if (!$this->questionnaire->is_ready) {
            return view('livewire.tenant.questionnaires.not-ready');
        }

        $this->setCategoryActiveData();

        if (isset($this->subCategorieActive['id'])) {
            $this->questionnaire->current_category = $this->subCategorieActive['id'];
        } else {
            $this->questionnaire->current_category = $this->categoryActive['id'];
        }

        $this->questions = $this->questionnaire->questions();

        //TODO:: once we change in Seeders or csv will remvoe this condition and loop.
        foreach ($this->questions as &$question) {
            if ($question->answer_type == 'checkbox-obs-decimal') {
                $question->answer_type = 'checkbox-obs-decimal-more';
            }
        }

        return view('livewire.tenant.co2calculator.index');
    }

    //update category children activ and hide others
    public function updateCategoryChildrenShow($parent, $children)
    {
        $this->categories = collect($this->categories)
            ->transform(function ($category) use ($parent, $children) {
                if ($category['id'] == $parent) {
                    $category['childrens'] = collect($category['childrens'])
                        ->transform(function ($child) use ($children) {
                            if ($child['id'] == $children) {
                                $child['active'] = true;
                            } else {
                                $child['active'] = false;
                            }
                            return $child;
                        })->toArray();
                }
                return $category;
            })->toArray();

        $this->emit('co2Calculator::refresh');
    }

    /**
     * Set category active data.
     */
    public function nextCategoryActive()
    {
        foreach ($this->categories as &$category) {

            if ($category['active']) {
                $activeChildIndex = null;
                $foundNextChild = false;

                foreach ($category['childrens'] as $index => &$child) {
                    if ($child['active']) {
                        $activeChildIndex = $index;
                        $child['active'] = false;
                    } elseif ($activeChildIndex !== null && !$foundNextChild) {
                        $child['active'] = true;
                        $foundNextChild = true;
                        break;
                    }
                }
                if (!$foundNextChild && count($category['childrens']) > 0 && $activeChildIndex !== null) {
                    $category['childrens'][0]['active'] = true;
                }
                break;
            }
        }
        $this->emit('co2Calculator::refresh');
    }

    /**
     * Set category active data.
     */
    public function previousCategoryActive()
    {
        foreach ($this->categories as &$category) {
            if ($category['active']) {
                $previousChildIndex = null;
                $activeChildIndex = null;
                foreach ($category['childrens'] as $index => &$child) {
                    if ($child['active']) {
                        $activeChildIndex = $index;
                        $child['active'] = false;
                        break;
                    }
                    $previousChildIndex = $index;
                }

                if ($activeChildIndex !== null && $previousChildIndex !== null) {
                    $indexToActivate = $activeChildIndex > 0 ? $activeChildIndex - 1 : count($category['childrens']) - 1;
                    $category['childrens'][$indexToActivate]['active'] = true;
                }
                break;
            }
        }

        $this->emit('co2Calculator::refresh');
    }

    public function setActiveCategory($categoryId)
    {
        foreach ($this->categories as &$category) {
            $category['active'] = $category['id'] == $categoryId;
        }
    }

    /**
     * Set category active data.
     */
    public function setCategoryActiveData()
    {
        $this->categoryActive = [];
        $this->subCategorieActive = [];

        collect($this->categories)->map(function ($category) {
            if ($category['active']) {
                $this->categoryActive = [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_first' => $this->categories[0]['id'] == $category['id'],
                    'is_last' => $this->categories[count($this->categories) - 1]['id'] == $category['id'],
                    'id' => $category['id'],
                ];

                $this->firstChild = array_key_first($category['childrens']);
                $this->lastChild = array_key_last($category['childrens']);

                collect($category['childrens'])->map(function ($child, $index) {

                    if ($child['active']) {
                        $this->subCategorieActive = [
                            'name' => $child['name'],
                            'description' => $child['description'],
                            'id' => $child['id'],
                            'is_first' => $index === $this->firstChild,
                            'is_last' => $index === $this->lastChild,
                        ];
                    }
                });
            }
        });
    }
}
