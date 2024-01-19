<?php

namespace App\Nova\Central\Actions;

use App\Models\Enums\Users\Type;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImpersonateTenant extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var Tenant $tenant */
        $tenant = $models[0];

        tenancy()->initialize($tenant->id);

        $backOfficeUser = Auth::user();

        // Check if the back-office user already has an account on the tenant
        $user = User::where('email', $backOfficeUser->email)->first();

        // Make user a System User with Super Admin role (keep this for old users)
        if ($user) {
            if (! $user->system) {
                User::where('id', $user->id)->update(['system' => true]);
            }
            if (! $user->hasRole('Super Admin')) {
                $user->assignRole('Super Admin');
            }
        }

        // Create a new user on the tenant with the same credentials as the back-office user
        if (! $user) {
            $user = new User([
                'system' => true,
                'enabled' => true,
                'name' => $backOfficeUser->name,
                'email' => $backOfficeUser->email,
                'username' => $backOfficeUser->email,
                'password' => $backOfficeUser->password,
                'type' => Type::INTERNAL,
                'email_verified_at' => now(),
                'locale' => 'en',
            ]);
            $user->assignRole('Super Admin');
            $user->save();
        }

        tenancy()->end();

        return Action::redirect(
            $tenant->impersonationUrl($user->id)
        );
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request = null)
    {
        return [];
    }
}
