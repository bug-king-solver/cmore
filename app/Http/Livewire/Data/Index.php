<?php

namespace App\Http\Livewire\Data;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\Indicator;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    public $search;

    protected $listeners = [
        'dataChanged' => '$refresh',
    ];

    /**
     * Mount the component.
     */
    public function mount()
    {
        parent::initFilters($model = Indicator::class);
        $this->model = new Indicator();
        
        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Monitoring'));
        $this->addBreadcrumb(__('Indicators'));
    }

     /**
     * Render the component.
     */
    public function render(): View
    {
        // TODO :: Add local enabled scope
        $indicators = $this->search($this->model->list()->whereEnabled(true))
            ->paginate($this->selectedItemsPerPage);

        return view(
            'livewire.tenant.data.index',
            [
                'indicators' => $indicators,
            ]
        );
    }
}
