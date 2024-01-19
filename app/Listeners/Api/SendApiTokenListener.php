<?php

namespace App\Listeners\Api;

use App\Events\Api\ApiTokenGeneratedEvent;
use App\Notifications\Api\ApiTokenAfterCreateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApiTokenListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ApiTokenGeneratedEvent $event)
    {
        $event->apiToken->notify(
            new ApiTokenAfterCreateNotification(
                $event->apiToken,
                $event->token
            )
        );
    }
}
