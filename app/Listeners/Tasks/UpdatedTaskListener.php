<?php

namespace App\Listeners\Tasks;

use App\Events\Tasks\UpdatedTaskEvent;
use App\Notifications\Tasks\SendUpdatedTaskUsersNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatedTaskListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UpdatedTaskEvent $event)
    {
        if ($event instanceof UpdatedTaskEvent) {
            foreach ($event->task->users as $user) {
                $user->notify(
                    new SendUpdatedTaskUsersNotification(
                        $event->task,
                        $event->changes,
                        $event->userFiredEvent
                    )
                );
            }
        }
    }
}
