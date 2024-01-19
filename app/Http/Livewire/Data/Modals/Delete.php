<?php

namespace App\Http\Livewire\Data\Modals;

use App\Models\Tenant\Data;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Data | int $data;

    public function mount(Data $data)
    {
        $this->data = $data;
        $this->authorize("data.delete.{$this->data->id}");
    }

    public function render()
    {
        return view('livewire.tenant.data.delete');
    }

    public function delete()
    {
        $this->data->delete();

        $this->emit('dataIndicatorChanged');

        $this->closeModal();
    }
}
