<?php

namespace App\Http\Livewire\Companies;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Enums\CompanySize;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\CompanyFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use CustomPagination;
    use BreadcrumbsTrait;

    public $model;

    public $search;

    /**
     * Mount the component.
     */
    public function mount()
    {
        $this->model = new Company();
        $this->addBreadcrumb(__('Companies'), route('tenant.companies.index'));
        $this->addBreadcrumb(__('Panel'), route('tenant.companies.index'));
    }

    public function render(): View
    {
        $companiesTotal = Company::list()->count();

        $companiesWithSize = Company::list()->select('data->size as size', DB::raw('count(*) as value'))
            ->groupBy('data->size')
            ->get();

        $colors = [color(1), color(5), color(13), color(20)];
        $companiesSize = [];
        foreach ($companiesWithSize as $index => $companies) {
            $size = $companies->size
                ? CompanySize::from($companies->size)
                : null;
            $color = $colors[$index] ?? $colors[0];

            $name = isset($size->name)
                ? ucwords(strtolower($size->name))
                : 'Not Defined';
            $companiesSize[] = array_merge($companies->toArray(), [
                'label' => __($name),
                'color' => $color
            ]);
        }

        $companiesParentNaceSector = [];

        // TO DO :: Optimize
        BusinessSector::whereHas(
            'companies',
            function ($query) {
                $query->OnlyOwnData();
            }
        )->withCount(['companies' => function ($query) {
            $query->OnlyOwnData();
        }])
            ->get()
            ->map(function ($businessSector) use (&$companiesParentNaceSector) {
                $totalCompanies = $companiesParentNaceSector[$businessSector['parent_id']] ?? 0;
                $totalCompanies += $businessSector['companies_count'];

                $companiesParentNaceSector[$businessSector['parent_id']] = $totalCompanies;
            })->toArray();

        if ($companiesParentNaceSector) {
            $parentIds = array_keys($companiesParentNaceSector);

            $companiesParentNaceSector = BusinessSector::whereIn('id', $parentIds)
                ->get()
                ->map(function ($businessSector) use ($companiesParentNaceSector) {
                    return [
                        'label' => $businessSector->name,
                        'value' => $companiesParentNaceSector[$businessSector->id],
                        'label_short' => trim(explode('-', $businessSector->name)[0])
                    ];
                })->toArray();
        }

        return view(
            'livewire.tenant.companies.index',
            [
                'companies_total'       => number_format($companiesTotal, 0, ',', '.'),
                'companies_size'        => $companiesSize,
                'companies_nace_sector' => $companiesParentNaceSector,
                'colors'                => $colors,
                'search'                => $this->search,
            ]
        );
    }
}
