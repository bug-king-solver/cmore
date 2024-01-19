<?php

namespace App\Http\Livewire\Roles\Modals;

use App\Models\Tenant\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Role | int $role;

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->authorize("roles.delete.{$this->role->id}");
    }

    public function render()
    {
        return view('livewire.tenant.roles.delete');
    }

    public function delete()
    {
        $this->role->delete();

        $this->emit('rolesChanged');

        $this->closeModal();
    }
}
