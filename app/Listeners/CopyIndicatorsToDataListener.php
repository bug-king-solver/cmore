<?php

namespace App\Listeners;

use App\Events\CopyIndicatorsToData;
use Illuminate\Contracts\Queue\ShouldQueue;

class CopyIndicatorsToDataListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CopyIndicatorsToData $event)
    {
        $event->model->copyToData();
    }
}
