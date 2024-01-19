<?php

namespace App\Listeners;

use App\Events\ReputationalAnalysisReady;
use App\Notifications\SendReputationalAnalysisReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReputationalAnalysisReadyListner implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ReputationalAnalysisReady $event)
    {
        if (! $event->analysis) {
            return;
        }
        $user = $event->analysis->user;
        $user->notify(
            new SendReputationalAnalysisReadyNotification(
                $event->analysis,
                $user
            )
        );
    }
}
