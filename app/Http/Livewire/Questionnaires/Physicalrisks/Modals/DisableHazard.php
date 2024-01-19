<?php

namespace App\Http\Livewire\Questionnaires\Physicalrisks\Modals;

use App\Models\Enums\Risk;
use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use App\Services\ThinkHazard\ThinkHazard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class DisableHazard extends ModalComponent
{
    use AuthorizesRequests;

    public PhysicalRisks $physicalRisks;
    public $risk;
    public $textChange;

    /**
     * Get the maximum width of the modal.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    /**
     * Validation rules.
     */
    public function rules()
    {
        return [
            'risk' => 'required',
            'textChange' => 'required',
        ];
    }

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(PhysicalRisks $physicalRisks, string $risk)
    {
        $this->physicalRisk = $physicalRisks;
        $this->hazards = collect($this->physicalRisk->hazards);
        $this->risk = $this->hazards->filter(function ($item) use ($risk) {
            return strtolower($item['name_slug']) == strtolower($risk);
        })->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render(): View
    {
        return view('livewire.tenant.physicalrisks.modals.disable-hazard');
    }



    /**
     * Save the activity.
     *
     * @return void
     */
    public function save()
    {
        $this->authorize('questionnaires.update', $this->physicalRisk->questionnaire->id);
        $data = $this->validate();

        $this->hazards = $this->hazards->map(function ($item) use ($data) {
            if ($item['name_slug'] === $data['risk']['name_slug']) {
                $item = $data['risk'];
                if (!isset($item['audits'])) {
                    $item['audits'] = [];
                }

                $item['enabled'] = !$item['enabled'];

                $item['audits'][] = [
                    'user' => auth()->user()->name,
                    'action' => $item['enabled'] ? 'enabled' : 'disabled',
                    'risk' => $data['risk']['name'],
                    'risk_slug' => $data['risk']['name_slug'],
                    'old_value' => $item['risk'],
                    'new_value' => $data['risk']['risk'],
                    'old_slug' => $item['risk_slug'],
                    'new_slug' => $data['risk']['risk_slug'],
                    'description' => $data['textChange'],
                    'created_at' => now(),
                ];
            }
            return $item;
        });

        $hazards = $this->physicalRisk->hazards;
        $hazards = $this->hazards->toArray();
        $this->physicalRisk->hazards = $hazards;

        $this->physicalRisk->save();
        $this->closeModal();
        $this->emit('physicalRiskRefresh');
    }
}
