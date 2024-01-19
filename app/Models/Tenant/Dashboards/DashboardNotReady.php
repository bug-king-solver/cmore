<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class DashboardNotReady
{
    /**
     * DashboardContruction view initializer
     */
    public function view($questionnaireId)
    {
        $questionnaire = Questionnaire::find($questionnaireId);
        return tenantView('tenant.dashboards.not_ready', compact('questionnaire'));
    }
}
