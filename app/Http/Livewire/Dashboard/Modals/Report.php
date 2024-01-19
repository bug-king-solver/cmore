<?php

namespace App\Http\Livewire\Dashboard\Modals;

use App\Models\Tenant\Questionnaire;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Report extends ModalComponent
{
    public $management;

    public $questionnaireList;

    public $questionnaireIds;

    protected $rules = [
        'management' => 'required',
    ];

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function mount($questionnaireIds)
    {
        $this->questionnaireIds = $questionnaireIds;
    }

    public function render(): View
    {
        $result = array_map(function ($questionnaire) {
            return ['id' => $questionnaire['id'], 'name' => $questionnaire['company']['name']];
        }, Questionnaire::questionnaireListByQuestionId($this->questionnaireIds)->toArray());

        $this->questionnaireList = parseDataForSelect($result, 'id', 'name');

        return tenantView('livewire.tenant.dashboard.modal.report');
    }

    public function save()
    {
        $data = $this->validate();

        Cookie::queue('management', $data['management']);

        $this->emit('printView');
        $this->closeModal();
    }
}
