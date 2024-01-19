<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Questionnaire;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $selectedQuestionnaire;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Questionnaire $questionnaire)
    {
        $this->selectedQuestionnaire = $questionnaire->exists && $questionnaire->isSubmitted()
            ? $questionnaire
            : null;

        if (!$this->selectedQuestionnaire) {
            return redirect()->route('tenant.home');
        } elseif (!$this->selectedQuestionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        if (!$questionnaire->hasResult()) {
            $dashboard = "\App\Models\Tenant\Dashboards\DashboardNotReady";
            return (new $dashboard($this->selectedQuestionnaire))->view($this->selectedQuestionnaire['id']);
        }

        $dashboardId = $this->selectedQuestionnaire->questionnaire_type_id;

        if (in_array($this->selectedQuestionnaire->questionnaire_type_id, [8, 16, 19])) {
            $dashboard = "\App\Models\Tenant\Dashboards\Dashboard8";
        } else {
            $dashboard = "\App\Models\Tenant\Dashboards\Dashboard{$dashboardId}";
        }

        if ($this->selectedQuestionnaire->questionnaire_type_id == 2) {
            $questionnireDate = $this->selectedQuestionnaire->submitted_at->format('dmYhis');
            $date1 = Carbon::createFromFormat('dmYhis', $questionnireDate);
            $date2 = Carbon::createFromFormat('dmYhis', '10032023084300');
            if ($date1->lte($date2)) {
                $dashboard = "\App\Models\Tenant\Dashboards\Dashboard2old";
            }
        }

        try {
            return (new $dashboard($this->selectedQuestionnaire))->view($this->selectedQuestionnaire['id']);
        } catch (\Exception $e) {
            if (config('app.env') === 'local') {
                throw $e;
            }

            return (new \App\Models\Tenant\Dashboards\DashboardContruction($this->selectedQuestionnaire))
                ->view($this->selectedQuestionnaire['id']);
        }
    }
}
