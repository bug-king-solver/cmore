<?php

namespace App\Http\Livewire\Roles;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;
    use WithPagination;

    public Role|int $role;

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->authorize("roles.view.{$this->role->id}");

        $this->addBreadcrumb(__('Teams'), route('tenant.roles.index'));
        $this->addBreadcrumb($this->role->name);
    }

    public function render(): View
    {
        $users = $this->role->users()->with('roles')->OnlyAppUsers()->paginate(4, ['*'], 'user');
        $permissions = $this->role->permissions->toArray();

        return view(
            'livewire.tenant.roles.show',
            [
                'users' => $users,
                'permissions' => $permissions
            ]
        );
    }
}
