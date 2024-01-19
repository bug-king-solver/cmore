<?php

namespace App\Http\Livewire\Roles;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\Role;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use BreadcrumbsTrait;
    use CustomPagination;

    protected $listeners = [
        'rolesChanged' => '$refresh',
    ];

    public function mount()
    {
        $this->addBreadcrumb(__('Teams'));
    }

    public function getRolesProperty()
    {
        return Role::with('permissions', 'users')->paginate($this->selectedItemsPerPage);
    }

    public function render(): View
    {
        return view('livewire.tenant.roles.index');
    }
}
