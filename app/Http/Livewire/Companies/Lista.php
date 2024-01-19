<?php

namespace App\Http\Livewire\Companies;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Enums\Companies\Relation;
use App\Models\Enums\Companies\Type;
use App\Models\Tenant\Company;
use Illuminate\View\View;

class Lista extends FilterBarComponent
{
    use CustomPagination;
    use BreadcrumbsTrait;

    protected $listeners = [
        'companiesChanged' => '$refresh',
    ];

    public $model;

    public $search;

    /**
     * Mount the component.
     */
    public function mount()
    {
        parent::initFilters($model = Company::class);
        $this->model = new Company();

        $this->addBreadcrumb(__('Companies'), route('tenant.companies.list'));
        
        $current = __('All');

        if ($this->activeFilters['company_type_filter'][0] ?? false) {
            $current = match ($this->activeFilters['company_type_filter'][0]) {
                Type::INTERNAL->value => Type::INTERNAL->labelPlural(),
                Type::EXTERNAL->value => Type::EXTERNAL->labelPlural(),
                default => '',
            };
        } elseif ($this->activeFilters['company_relation_filter'][0] ?? false) {
            $current = match ($this->activeFilters['company_relation_filter'][0]) {
                Relation::CLIENT->value => Relation::CLIENT->labelPlural(),
                Relation::SUPPLIER->value => Relation::SUPPLIER->labelPlural(),
                default => '',
            };
        }

        $this->addBreadcrumb($current);
    }

    public function render(): View
    {
        $companies = $this->search($this->model->list())
            ->with('parent')
            ->paginate($this->selectedItemsPerPage);

        $countries = [];
        $companies->map(function ($company) use (&$countries) {
            if ($company->country) {
                $countries[] = $company->country;
            }

            if ($company->vat_country) {
                $countries[] = $company->vat_country;
            }
        });

        if ($countries) {
            $countries = array_unique($countries);
            $countries = getCountriesWhereIn($countries);
        }

        return view(
            'livewire.tenant.companies.list',
            [
                'companies' => $companies,
                'countries' => $countries,
                'search' => $this->search,
            ]
        );
    }
}
