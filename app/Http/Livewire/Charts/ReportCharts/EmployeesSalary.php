<?php

namespace App\Http\Livewire\Charts\ReportCharts;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Data;
use App\Models\Traits\DashboardData;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EmployeesSalary extends Component
{
    use AuthorizesRequests;
    use DashboardData;

    public function mount()
    {
    }

    public function render()
    {
        return view(
            'livewire.tenant.charts.report-charts.employees-salary',
            [
                'employees_salary' => $this->getData(),
            ]
        )
        ->layoutData(
            [
                'mainBgColor' => 'bg-esg4',
            ]
        );
    }

    private function getData()
    {
        $data = [
            'salary1' => Data::where('indicator_id', 214)->sum('value'),
            'salary2' => Data::where('indicator_id', 54)->sum('value'),
        ];

        return $this->parseDataForChartEmployeeSalaryAvg($data);
    }
}
