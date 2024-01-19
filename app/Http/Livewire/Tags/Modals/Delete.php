<?php

namespace App\Http\Livewire\Tags\Modals;

use App\Models\Tenant\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Tag | int $tag;

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
        $this->authorize("tags.delete.{$this->tag->id}");
    }

    public function render()
    {
        return view('livewire.tenant.tags.delete');
    }

    public function delete()
    {
        $this->tag->delete();

        $this->emit('tagsSaved');

        $this->closeModal();
    }
}
