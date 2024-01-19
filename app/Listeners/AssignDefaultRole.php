<?php

namespace App\Listeners;

use App\Models\Tenant\Role;
use Illuminate\Auth\Events\Registered;

class AssignDefaultRole
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (! isset($event->extra['roles'])) {
            if ($defaultRole = Role::where('default', true)->get()) {
                $event->user->assignRole($defaultRole);
            }
        } else {
            $event->user->syncRoles($event->extra['roles']);
        }

        if (isset($event->extra['permissions'])) {
            $event->user->syncPermissions($event->extra['permissions']);
        }
    }
}
