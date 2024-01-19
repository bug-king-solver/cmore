<?php

namespace App\Http\Livewire\Users\Modals;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public User | int $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->authorize("users.delete.{$this->user->id}");
    }

    public function render()
    {
        return view('livewire.tenant.users.delete');
    }

    public function delete()
    {
        $this->user->tags()->sync([]);

        $this->user->assignerAbleUpdate(auth()->user()->id);

        /* drop userables */
        $this->user->userables()->delete();

        /** assignerAbleUpdate */

        /* drop user */
        $this->user->delete();

        $this->emit('assignChanged');

        $this->closeModal();
    }
}
