<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Questionnaire;
use App\Models\Traits\Dashboard;

class Dashboard17
{
    /**
     *Rander the view
     */
    public function view($questionnaire)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaire);

        return tenantView(
            'tenant.dashboards.17', [
                'questionnaire' => $questionnaire,
            ]
        );
    }
}
