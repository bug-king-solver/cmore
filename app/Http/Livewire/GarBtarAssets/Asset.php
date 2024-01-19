<?php

namespace App\Http\Livewire\GarBtarAssets;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Enums\Regulamentation\EntityTypeEnum;
use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;

class Asset extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    public bool $isSearchable = false;

    public $assetTypeList;

    public $entityTypeList;

    public $rowActive;

    public $model;

    private $assets = [];

    protected $listeners = [
        'updateRow' => '$refresh'
    ];

    /**
     * Mount the component.
     */
    public function mount()
    {
        parent::initFilters($model = BankAssets::class);
        $this->model = new BankAssets();
        $this->assetTypeList = AssetTypeEnum::toArray();
        $this->entityTypeList = EntityTypeEnum::toArray();
        $this->assets = [];

        $nace = request()->query('nace');
        if (isset($nace)) {
            $this->updateFiltersValues('nace_filter', explode(',', $nace));
        }

        $entity = request()->query('entity');
        if (isset($entity)) {
            $this->updateFiltersValues('entity_type_filter', explode(',', $entity));
        }

        $type = request()->query('type');
        if (isset($type)) {
            $this->updateFiltersValues('asset_type_filter', explode(',', $type));
        }

        $this->addBreadcrumb(__('Assets'), route('tenant.garbtar.asset'));
        $this->addBreadcrumb(__('All'));
    }

    public function render(): View
    {
        $this->assets = $this->search($this->model->list())
            ->paginate($this->selectedItemsPerPage);
        return view('livewire.tenant.garbtarassets.asset', [
            'assets' => $this->assets
        ]);
    }

    /**
     * Update graph section
     */
    public function showInformationRow($value = null)
    {
        if (isset($this->rowActive) && $this->rowActive === $value) {
            $this->rowActive = null;
        } else {
            $this->rowActive = $value;
        }
        $this->emit('updateRow');
    }

}
