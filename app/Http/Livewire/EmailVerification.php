<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EmailVerification extends Component
{
    public $enabled;

    public $success = '';

    protected $rules = [
        'enabled' => ['required', 'bool'],
    ];

    public function mount()
    {
        $this->enabled = tenant()->hasUserEmailVerificationEnabled();
    }

    public function render()
    {
        return view('livewire.tenant.email-verification');
    }

    public function update()
    {
        $this->validate();

        tenant()->update(['user_email_verification' => (bool) $this->enabled]);

        $this->success = __('E-mail verification was successfully updated.');
    }
}
