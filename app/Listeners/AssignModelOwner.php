<?php

namespace App\Listeners;

use App\Events\CreatedAssignableModel;
use App\Models\User;

class AssignModelOwner
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreatedAssignableModel $event)
    {
        if ($event->model->created_by_user_id) {
            // On the creation flow, the assigner will who is creating the resource
            $assigner = User::find($event->model->created_by_user_id);

            $event->model->assignUsers([$event->model->created_by_user_id], $assigner);
        }
    }
}
