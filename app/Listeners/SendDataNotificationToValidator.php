<?php

namespace App\Listeners;

use App\Events\CreatedData;
use App\Notifications\DataValidatorNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDataNotificationToValidator implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreatedData $event)
    {
        if ($event instanceof CreatedData) {
            $event->user->notify(
                new DataValidatorNotification(
                    $event->data,
                    $event->senderuser
                )
            );
        }
    }
}

