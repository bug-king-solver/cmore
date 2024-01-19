<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard4 extends Dashboard3
{
    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId);
        $Ids = [
            778, 780, 782, 783, 784, 787, 790, 793, 796, 798,
            800, 801, 803, 804, 806, 807, 808, 809, 811, 813, 815, 824, 825,
        ];
        $this->parsePosition($Ids);

        return tenantView(
            'tenant.dashboards.4',
            [
                'questionnaire' => $this->questionnaire,
                'position' => $this->position,
            ]
        );
    }
}
