<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\CreatedTaskEvent;
use App\Notifications\Tasks\SendCreatedTaskUsersNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedTaskListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreatedTaskEvent $event)
    {
        if ($event instanceof CreatedTaskEvent) {
            foreach ($event->task->users as $user) {
                $user->notify(
                    new SendCreatedTaskUsersNotification(
                        $event->task,
                        $event->userFiredEvent
                    )
                );
            }
        }
    }
}
