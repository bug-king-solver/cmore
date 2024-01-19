<?php

namespace App\Http\Livewire\Dashboard\Modals;

use App\Models\Tenant\QuestionnaireType;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Filter extends ModalComponent
{
    public QuestionnaireType | int $questionnaire_type;

    public $typesList;

    public $period;

    public $type;

    protected $rules = [
        'type' => 'required|exists:questionnaire_types,id',
        'period' => 'required',
    ];

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(QuestionnaireType $questionnaire_type)
    {
        $this->questionnaire_type = $questionnaire_type;
        $types = QuestionnaireType::list()->get();
        $this->typesList = array_pluck($types, 'name', 'id');
        $this->type = 5;
    }

    public function render(): View
    {
        return tenantView('livewire.tenant.dashboard.modal.filter');
    }

    public function save()
    {
        $data = $this->validate();

        Cookie::queue('period', $data['period']);

        $this->emit('dataChanged');

        $this->closeModal();
    }
}
