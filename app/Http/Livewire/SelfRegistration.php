<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SelfRegistration extends Component
{
    public $enabled;

    public $success = '';

    protected $rules = [
        'enabled' => ['required', 'bool'],
    ];

    public function mount()
    {
        $this->enabled = tenant()->hasSelfRegistrationEnabled();
    }

    public function render()
    {
        return view('livewire.tenant.self-registration');
    }

    public function update()
    {
        $this->validate();

        tenant()->update(['self_registration' => (bool) $this->enabled]);

        $this->success = __('Self registration was successfully updated.');
    }
}
