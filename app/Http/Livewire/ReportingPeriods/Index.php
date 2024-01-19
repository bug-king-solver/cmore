<?php

namespace App\Http\Livewire\ReportingPeriods;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\ReportingPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    protected $listeners = [
        '$refresh',
    ];

    /**
     * Mount the component.
     * @return void
     */
    public function mount(): void
    {
        parent::initFilters($model = ReportingPeriod::class);

        $this->addBreadcrumb(__('Reporting Periods'));
    }

    /**
     * Get the resource property.
     * @return LengthAwarePaginator
     */
    public function getResourceProperty(): LengthAwarePaginator
    {
        return $this->search(new ReportingPeriod())
            ->paginate($this->selectedItemsPerPage)
            ->withQueryString();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.tenant.reporting-periods.index');
    }
}
