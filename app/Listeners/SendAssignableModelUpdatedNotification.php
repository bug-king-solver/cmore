<?php

namespace App\Listeners;

use App\Events\UpdatedAssignableModel;
use App\Notifications\UpdatedUserableModelNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendAssignableModelUpdatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UpdatedAssignableModel $event)
    {
        Notification::send(
            $event->model->users,
            new UpdatedUserableModelNotification(
                $event->model->updatedUserMessage()
            )
        );
    }
}
