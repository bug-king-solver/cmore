<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Questionnaire;
use Livewire\Component;

class Progress extends Component
{
    public Questionnaire | int $questionnaire;

    public $title = true;

    public $menu;

    protected $listeners = [
        '$refresh',
    ];

    public function mount(?Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function render()
    {
        $this->menu = $this->questionnaire->menu();

        return view('livewire.tenant.questionnaires.progress');
    }
}
