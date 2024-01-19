<?php

namespace App\Http\Livewire\SourceReport;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\SourceReport;
use Livewire\WithPagination;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use WithPagination;

    public $resultsPerPage;
    public $model;
    public $search;

    public function __construct()
    {
        $this->resultsPerPage = config('app.paginate.per_page');
    }

    public function mount()
    {
        $this->model = new SourceReport();
        $this->fetchAvailableFilters($this->model->getMorphClass());

        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Reports'));
    }


    public function render()
    {
        $reports = SourceReport::list();
        return view('livewire.tenant.source-reports.index', [
            'reports' => $reports
        ]);
    }
}
