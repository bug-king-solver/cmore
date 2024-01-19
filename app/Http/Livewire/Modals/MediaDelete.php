<?php

namespace App\Http\Livewire\Modals;

use App\Models\Tenant\Attachment;
use App\Models\Tenant\Media;
use Illuminate\Support\Facades\Storage;
use LivewireUI\Modal\ModalComponent;

class MediaDelete extends ModalComponent
{
    public Media | int $media;

    public function mount(Media $media)
    {
        $this->media = $media;
    }

    public function render()
    {
        return view('livewire.tenant.modals.media-delete');
    }

    public function delete()
    {
        $this->media->attachables()->delete();
        $this->media->delete();
        $this->emit('attachmentsChanged');
        $this->emit('documentFolderChanged');

        $this->closeModal();
    }
}
