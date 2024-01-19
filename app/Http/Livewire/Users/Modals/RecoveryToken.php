<?php

namespace App\Http\Livewire\Users\Modals;

use App\Http\Livewire\Traits\HasCustomColumns;
use LivewireUI\Modal\ModalComponent;

class RecoveryToken extends ModalComponent
{
    use HasCustomColumns;

    protected $feature = 'users';

    public $tokens;

    public function render()
    {
        return view('livewire.tenant.users.recovery-token');
    }

    protected function rules()
    {
        return $this->mergeCustomRules([]);
    }

    public function mount()
    {
        $user = auth()->user();

        $tokens = $user->generateRecoveryCodes();

        $this->tokens = $tokens->toArray();

        $user->update([
            'recovery_codes' => $this->tokens,
        ]);
    }
}
