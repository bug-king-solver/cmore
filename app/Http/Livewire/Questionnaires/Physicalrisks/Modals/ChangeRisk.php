<?php

namespace App\Http\Livewire\Questionnaires\Physicalrisks\Modals;

use App\Models\Enums\Risk;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class ChangeRisk extends ModalComponent
{
    use AuthorizesRequests;

    public PhysicalRisks $physicalRisks;
    public $hazard;
    public $risk;

    public $riskList;

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

        $this->riskList = parseValueByKeyToSimpleSelect(Risk::formList());

        $this->textChange = isset($this->risk['audits']) ? end($this->risk['audits'])['description'] : '';
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render(): View
    {
        return view('livewire.tenant.physicalrisks.modals.change-risk');
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

        $data['risk']['risk'] = ucfirst(strtolower(Risk::fromSlug($data['risk']['risk_slug'])->label()));

        $this->hazards = $this->hazards->map(function ($item) use ($data) {
            if ($item['name_slug'] === $data['risk']['name_slug']) {
                $currentRisk = $item;

                $item = $data['risk'];
                if ($currentRisk['risk_slug'] !== $data['risk']['risk_slug']) {
                    if (!isset($item['audits'])) {
                        $item['audits'] = [];
                    }

                    $item['audits'][] = [
                        'user' => auth()->user()->name,
                        'action' => 'change_level',
                        'risk' => $data['risk']['name'],
                        'risk_slug' => $data['risk']['name_slug'],
                        'old_value' => $currentRisk['risk'],
                        'new_value' => $data['risk']['risk'],
                        'old_slug' => $currentRisk['risk_slug'],
                        'new_slug' => $data['risk']['risk_slug'],
                        'description' => $data['textChange'],
                        'old_has_contingency_plan' => $currentRisk['has_contingency_plan'] ?? false,
                        'has_contingency_plan' => $data['risk']['has_contingency_plan'] ?? false,
                        'old_contingency_plan_description' => $currentRisk['contingency_description'] ?? false,
                        'contingency_plan_description' => $data['risk']['contingency_description'] ?? false,
                        'old_has_continuity_plan' => $currentRisk['has_continuity_plan'] ?? false,
                        'has_continuity_plan' => $data['risk']['has_continuity_plan'] ?? false,
                        'old_continuity_plan_description' => $currentRisk['continuity_description'] ?? false,
                        'continuity_plan_description' => $data['risk']['continuity_description'] ?? false,
                        'created_at' => now(),
                    ];
                }
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
