<?php

namespace App\Http\Livewire\GarBtarAssets;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Enums\Regulamentation\EntityTypeEnum;
use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Livewire\Component;

class UploadDocument extends Component
{
    use CustomPagination;

    protected $listeners = [
        'updateRow' => '$refresh'
    ];

    /**
     * Mount the component.
     */
    public function mount()
    {
        $this->model = new BankAssets();
    }

    public function render(): View
    {
        $this->assets = $this->search($this->model->list())
            ->paginate($this->selectedItemsPerPage);
        return view('livewire.tenant.garbtarassets.index', [
            'assets' => $this->assets
        ]);
    }
}
