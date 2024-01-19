<?php

namespace App\Listeners;

use App\Events\EnabledUser;
use App\Notifications\EnabledUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnabledUserNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EnabledUser $event)
    {
        $event->user->notify(new EnabledUserNotification());
    }
}
