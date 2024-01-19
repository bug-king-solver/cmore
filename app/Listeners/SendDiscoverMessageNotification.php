<?php

namespace App\Listeners;

use App\Events\DiscoverMessage;
use App\Notifications\DiscoverMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDiscoverMessageNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DiscoverMessage $event)
    {
        $event->tenant->notify(new DiscoverMessageNotification($event->user));
    }
}
