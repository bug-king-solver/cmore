<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ManualActivation extends Component
{
    public $enabled;

    public $success = '';

    protected $rules = [
        'enabled' => ['required', 'bool'],
    ];

    public function mount()
    {
        $this->enabled = tenant()->hasUserManualActivationEnabled();
    }

    public function render()
    {
        return view('livewire.tenant.manual-activation');
    }

    public function update()
    {
        $this->validate();

        tenant()->update(['user_manual_activation' => (bool) $this->enabled]);

        $this->success = __('Manual activation was successfully updated.');
    }
}
