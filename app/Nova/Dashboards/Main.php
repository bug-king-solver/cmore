<?php

namespace App\Nova\Dashboards;

use App\Models\Admin;
use App\Nova\Metrics\AvgReadyTime;
use App\Nova\Metrics\AvgReadyTimePerUser;
use App\Nova\Metrics\MaxReadyTime;
use App\Nova\Metrics\MaxReadyTimePerUser;
use App\Nova\Metrics\MinReadyTime;
use App\Nova\Metrics\MinReadyTimePerUser;
use App\Nova\Metrics\NewCompany;
use App\Nova\Metrics\NewReports;
use App\Nova\Metrics\ReadyReports;
use App\Nova\Metrics\ReadyReportsPerDayPerReporter;
use App\Nova\Metrics\ReadyReportsPerReporter;
use App\Nova\Metrics\ReportsPerDay;
use App\Nova\Metrics\SubmittedQuestionnaires;
use App\Nova\Metrics\TotalCompanies;
use App\Nova\Metrics\TotalQuestionnaires;
use App\Nova\Metrics\TotalTenants;
use App\Nova\Metrics\TotalUsers;
use App\Nova\Metrics\ValidatedReports;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        $cards = [];

        if (tenancy()->initialized) {
            $cards = [];
            $tenantBackOfficeEnabledMenus = explode(',', config('app.tenant_bo_menus'));
            if (in_array('User', $tenantBackOfficeEnabledMenus)) {
                $cards[] = new TotalUsers();
            }
            if (in_array('Questionnaire', $tenantBackOfficeEnabledMenus)) {
                $cards = array_merge($cards, [
                    new TotalQuestionnaires(),
                    new SubmittedQuestionnaires(),
                ]);
            }
        } else {
            $cards = [
                new TotalTenants(),
                new TotalCompanies(),
                new TotalUsers(),
                new TotalQuestionnaires(),
                new SubmittedQuestionnaires()
            ];

            $backOfficeEnabledMenus = explode(',', config('app.central_bo_menus'));
            if (in_array('BenchmarkReport', $backOfficeEnabledMenus)) {
                $cards = array_merge($cards, [
                    new NewCompany(),
                    new NewReports(),
                    new ReadyReports(),
                    new ValidatedReports(),
                    new ReportsPerDay(),
                    new ReadyReportsPerReporter(),
                    new MinReadyTime(),
                    new MaxReadyTime(),
                    new AvgReadyTime(),
                ]);
                $admins = Admin::where('is_reporter', 1)->get();
                foreach ($admins as $admin) {
                    $cards = array_merge($cards, [
                        new ReadyReportsPerDayPerReporter($admin->id),
                        new MinReadyTimePerUser($admin->id),
                        new MaxReadyTimePerUser($admin->id),
                        new AvgReadyTimePerUser($admin->id),
                    ]);
                }
            }
        }

        return $cards;
    }
}
