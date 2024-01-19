<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EmailNotifications extends Component
{
    public $to;

    public $cc;

    public $success = '';

    protected $rules = [
        'to' => ['required', 'email'],
        'cc' => ['email'],
    ];

    public function mount()
    {
        $notifications = tenant()->notifications_config ?? null;

        $this->to = implode(',', $notifications['to'] ?? []) ?: null;
        $this->cc = implode(',', $notifications['cc'] ?? []) ?: null;
    }

    public function render()
    {
        return view('livewire.tenant.email-notifications');
    }

    /**
     * TODO :: Allow to add multiple emails (separeted by ,)
     */
    public function update()
    {
        $data = $this->validate();

        $tos = array_filter([$data['to']]);
        $ccs = array_filter([$data['cc']]);

        $notifications = [
            'notifications_config' => [
                'to' => $tos,
                'cc' => $ccs,
            ],
        ];

        if (tenant()->update($notifications)) {
            $this->success = __('Emails for receiving notifications have been successfully updated');
        }
    }
}
