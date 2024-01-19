<?php

namespace App\Http\Livewire\Modals;

use App\Models\Tenant\Attachment;
use Illuminate\Support\Facades\Storage;
use LivewireUI\Modal\ModalComponent;

class AttachmentsDelete extends ModalComponent
{
    public Attachment | int $attachment;

    public function mount(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function render()
    {
        return view('livewire.tenant.modals.attachments-delete');
    }

    public function delete()
    {
        $this->attachment->attachables()->delete();
        $this->attachment->delete();

        Storage::disk('local')->delete($this->attachment->file);

        $this->emit('attachmentsChanged');

        $this->closeModal();
    }
}
