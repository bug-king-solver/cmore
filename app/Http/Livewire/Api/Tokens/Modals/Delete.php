<?php

namespace App\Http\Livewire\Api\Tokens\Modals;

use App\Models\Tenant\Api\ApiTokens;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public ApiTokens | int $token;

    public function mount(ApiTokens $token)
    {
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.tenant.api.tokens.delete');
    }

    public function delete()
    {
        $this->token->delete();

        $this->emit('apiTokenChanged');

        $this->closeModal();
    }
}
