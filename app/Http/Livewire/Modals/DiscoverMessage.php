<?php

namespace App\Http\Livewire\Modals;

use App\Events\DiscoverMessage as EventsDiscoverMessage;
use App\Notifications\DiscoverMessageNotification;
use Illuminate\Support\Facades\Notification;
use LivewireUI\Modal\ModalComponent;

class DiscoverMessage extends ModalComponent
{
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render()
    {
        event(new EventsDiscoverMessage(tenant(), auth()->user()));

        return view('livewire.tenant.modals.discover-message');
    }
}
