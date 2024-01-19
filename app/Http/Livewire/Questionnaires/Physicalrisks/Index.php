<?php

namespace App\Http\Livewire\Questionnaires\Physicalrisks;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\CompanyAddresses;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use BreadcrumbsTrait;


    public Questionnaire $questionnaire;

    public $physicalRisks;

    public $audits = [];

    protected $listeners = [
        'physicalRiskRefresh' => '$refresh',
    ];

    public $reportUrl;

    public $isReport = false;

    /**
     * Mount the component.
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;

        if (!$this->questionnaire->is_ready) {
            return false;
        } elseif (!$this->questionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        $this->reportUrl = $this->questionnaire->questionnaireReport();

        // get route name
        $routeName = request()->route()->getName();
        $this->isReport = preg_match('/report/', $routeName)
            ? true
            : false;

        $this->addBreadcrumb(__('Questionnaires'), route('tenant.questionnaires.index'));
        $this->addBreadcrumb(__('Physical Risks'), null);
        $this->addBreadcrumb($this->questionnaire->id);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $this->physicalRisks = $this->questionnaire->physicalRisks;
        // Loop into physical risks -> hazards ['data'] and get the audits , order bby created_at , and create a new array
        foreach ($this->physicalRisks as &$physicalRisk) {
            $physicalRisk['address'] = CompanyAddresses::withTrashed()->find($physicalRisk->company_address_id);
            $this->audits[$physicalRisk->id] = [];
            foreach ($physicalRisk->hazards as $hazard) {
                if (isset($hazard['audits'])) {
                    $this->audits[$physicalRisk->id] = array_merge($hazard['audits'], $this->audits[$physicalRisk->id]);
                }
            }

            $this->audits[$physicalRisk->id] = collect($this->audits[$physicalRisk->id])
                ->sortByDesc('created_at')
                ->all();
            $this->audits[$physicalRisk->id] = array_values($this->audits[$physicalRisk->id]);
        }

        return view('livewire.tenant.physicalrisks.index')
            ->with('isReport', $this->isReport);
    }

    /**
     * Submit questionnaire
     */
    public function submit()
    {
        $this->questionnaire->completed_at = Carbon::now();
        $this->questionnaire->submitted_at = Carbon::now();
        $this->questionnaire->save();

        PhysicalRisks::whereIn('id', $this->questionnaire->physicalRisks->pluck('id')->toArray())
            ->update([
                'completed_at' => Carbon::now(),
                'completed' => true
            ]);

        session()->flash(
            'messageText',
            __('The physical risks questionnaire has been completed successfully')
        );

        session()->flash('messageType', 'success');
        session()->flash('messageTitle', __('Success'));

        redirect(route('tenant.physicalrisks.index.report', ['questionnaire' => $this->questionnaire->id]));

    }
}
