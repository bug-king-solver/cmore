<?php

namespace App\Http\Livewire\Compliance\Reputational\Modals;

use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use App\Rules\WordCount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;

    public $name;

    public $search_terms;

    public AnalysisInfo | int $analysisInfo;

    public array $keywordArray;

    protected function rules()
    {
        return [
            'name' => 'required|string',
            'search_terms' => [
                'required',
                'array',
                new WordCount(),
            ],
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function mount(?AnalysisInfo $analysisInfo)
    {
        $this->analysisInfo = $analysisInfo;
        $this->keywordArray = [];
        $this->authorize(
            ! $this->analysisInfo->exists ? 'reputation.create' : "reputation.update.{$this->analysisInfo->id}"
        );
        if ($this->analysisInfo->exists) {
            $this->name = $this->analysisInfo->name;
            $this->search_terms = $this->analysisInfo->search_terms;

            if (! empty($this->search_terms)) {
                foreach ($this->search_terms as $term) {
                    $temp = [
                        'id' => $term,
                        'title' => $term,
                    ];
                    $this->keywordArray[] = $temp;
                }
            }
        }
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.reputational.modal.form'
        );
    }

    public function save()
    {
        $this->authorize(
            ! $this->analysisInfo->exists ? 'reputation.create' : "reputation.update.{$this->analysisInfo->id}"
        );
        $data = $this->validate();
        $analysisArr = [];
        $analysisArr['name'] = $data['name'];
        $analysisArr['search_terms'] = $data['search_terms'];
        $analysisArr['created_by_user_id'] = auth()->user()->id;

        if (! $this->analysisInfo->exists) {
            $analysis = AnalysisInfo::create($analysisArr);
            $this->analysisInfo = $analysis;
        } else {
            $this->analysisInfo->update($analysisArr);
        }

        $this->emit('analysisChanged', [
            'analysisInfo' => $this->analysisInfo->id,
        ]);

        $this->closeModal();
    }
}
