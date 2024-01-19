<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Category;
use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Menu extends Component
{
    public Questionnaire | int $questionnaire;

    public array | null $allowedCategories; // If null, all available

    public Category | null $categorySelected;

    public $categoriesAllowed;

    public $menu;

    public $data;

    public $isCompleted;

    public $isSubmitted;


    protected $listeners = [
        '$refresh',
    ];

    public function mount(Questionnaire $questionnaire, $category = null, $data = null)
    {
        $this->questionnaire = $questionnaire;
        $this->categorySelected = $category;
        $this->data = $data;

        $this->isCompleted = $questionnaire->isCompleted();
        $this->isSubmitted = $questionnaire->isSubmitted();
    }

    public function render()
    {
        $this->menu = $this->questionnaire->menu();
        //TODO make the ID dinamyc for the categories
        $this->allowedCategories = ! tenant()->onActiveSubscription ? [4, 13, 14] : null;
        $this->categoriesAllowed = $this->allowedCategories;
        $this->isCompleted = $this->questionnaire->isCompleted();
        $this->isSubmitted = $this->questionnaire->isSubmitted();
        return tenantView('livewire.tenant.questionnaires.menu');
    }
}
