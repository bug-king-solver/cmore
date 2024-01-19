<?php

namespace App\Http\Livewire\Roles;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\WithPagination;
use Livewire\Component;

class Form extends Component
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;
    use WithPagination;

    protected $feature = 'roles';

    public Role | int $role;

    public $permissionsList;

    public $usersList;

    public $default = false;

    public $name;

    public $permissions;

    public $users;

    protected function rules()
    {
        return [
            'default' => ['nullable', 'boolean'],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name' . ($this->role ? ",{$this->role->id}" : ''),
            ],
            'users' => ['nullable', 'exists:users,id'],
            'permissions' => ['nullable', 'exists:permissions,id'],
        ];
    }

    public function mount(?Role $role)
    {
        $this->role = $role;
        $this->authorize(! $this->role->exists ? 'roles.create' : "roles.update.{$this->role->id}");

        $this->permissionsList = getPermissionsForSelect();
        $this->usersList = User::OnlyAppUsers()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'title' => $user->name,
            ];
        });

        $this->addBreadcrumb(__('Teams'), route('tenant.roles.index'));

        if ($this->role->exists) {
        $this->addBreadcrumb($this->role->name);
            $this->default = $this->role->default;
            $this->name = $this->role->name;
            $this->permissions = $this->role->permissions->pluck('id')->toArray();
            $this->users = $this->role->users->pluck('id')->toArray();
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.roles.form');
    }

    public function save()
    {
        $this->authorize(! $this->role->exists ? 'roles.create' : "roles.update.{$this->role->id}");

        $data = $this->validate();

        if (! $this->role->exists) {
            $this->role = Role::create($data);
        } else {
            $this->role->update($data);
        }

        $this->role->syncPermissions($data['permissions']);
        $this->role->users()->sync($data['users']);

        // Only one default role
        if ($data['default']) {
            Role::where('default', true)
                ->where('id', '!=', $this->role->id)
                ->update(['default' => false]);
        }

        if ($this->authorize('roles.view')) {
            $this->emit('rolesChanged');
        } else {
            return redirect()->route('tenant.dashboard');
        }

        return redirect(route('tenant.roles.index'));
    }
}
