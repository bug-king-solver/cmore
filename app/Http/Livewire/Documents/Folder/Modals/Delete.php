<?php

namespace App\Http\Livewire\Documents\Folder\Modals;

use App\Models\Tenant\DocumentFolder;
use App\Models\Tenant\Media;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public DocumentFolder | int $documentFolder;

    public function mount(DocumentFolder $documentFolder)
    {
        $this->documentFolder = $documentFolder;
    }

    public function render()
    {
        return view('livewire.tenant.documents.folder.delete');
    }

    public function delete()
    {
        $allMediaOfFolder = Media::where('collection_name', config('media-library.collection.internal'))
        ->whereJsonContains('custom_properties->folder', $this->documentFolder->id)->get();
        foreach ($allMediaOfFolder as $media) {
            $media->delete();
        }

        $this->documentFolder->delete();

        $this->emit('documentFolderChanged');

        $this->closeModal();
    }
}
