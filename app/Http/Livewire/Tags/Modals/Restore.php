<?php

namespace App\Http\Livewire\Tags\Modals;

use App\Models\Tenant\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Restore extends ModalComponent
{
    use AuthorizesRequests;

    public Tag | int $tag;

    public function mount($tag)
    {
        $this->tag = Tag::withTrashed()->find($tag);
        $this->authorize("tags.restore.{$this->tag->id}");
    }

    public function render()
    {
        return view('livewire.tenant.tags.restore');
    }

    public function restore()
    {
        $this->tag->restore();

        $this->emit('tagsSaved');

        $this->closeModal();
    }
}
