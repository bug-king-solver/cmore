<?php

namespace App\Listeners\Saml2;

use App\Models\Tenant\Role;
use App\Models\User;
use Slides\Saml2\Events\SignedIn;

class Saml2
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SignedIn $event)
    {
        // your own code preventing reuse of a $messageId to stop replay attacks
        $samlUser = $event->getSaml2User();

        $userData = [
            'id' => $samlUser->getUserId(),
            'attributes' => $samlUser->getAttributes(),
            'assertion' => $samlUser->getRawSamlAssertion()
        ];

        $role = Role::where('default', true)->get()->first();
        $user = User::updateOrCreate(
            [
                'email' => $userData['id'],
            ],
            [
                'name' => $userData['attributes']['name'][0] ?? "",
                'email' => $userData['id'],
                'enabled' => true
            ]
        );
        if ($user->roles()->count() == 0) {
            $user->assignRole($role);
        }

        auth()->login($user, true);
    }
}
