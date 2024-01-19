<?php

namespace App\Models\Tenant\Dashboards;

class Dashboard19
{
    public function view($questionnaireId)
    {
        return tenantView(
            request()->print == 'true' ? 'tenant.dashboards.reports.19' : 'tenant.dashboards.demos.19'
        );
    }
}
