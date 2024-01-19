<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class DashboardContruction
{
    /**
     * DashboardContruction view initializer
     */
    public function view($questionnaireId)
    {
        return tenantView('tenant.dashboards.under_construction');
    }
}
