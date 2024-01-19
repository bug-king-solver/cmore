<?php

namespace App\Listeners;

use App\Events\AssignedUsers;
use App\Notifications\AssignedUsersNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendAssignedUsersNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AssignedUsers $event)
    {
        if (! $event->newlyAssignedUsers) {
            return;
        }

        Notification::send(
            $event->model->users->whereIn('id', $event->newlyAssignedUsers),
            new AssignedUsersNotification(
                $event->model->assignedUserMessage($event->assigner),
                $event->assigner
            )
        );
    }
}
