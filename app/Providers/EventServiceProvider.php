<?php

namespace App\Providers;

use App\Events\Api\ApiTokenGeneratedEvent;
use App\Events\AssignedUsers;
use App\Events\CreatedData;
use App\Events\CopyIndicatorsToData;
use App\Events\CreatedAssignableModel;
use App\Events\CreatedCompany;
use App\Events\DeletedAssignableModel;
use App\Events\DiscoverMessage;
use App\Events\EnabledUser;
use App\Events\Registered;
use App\Events\ReputationalAnalysisReady;
use App\Events\Tasks\CreatedTaskEvent;
use App\Events\Tasks\UpdatedTaskEvent;
use App\Events\UpdatedAssignableModel;
use App\Listeners\Api\SendApiTokenListener;
use App\Listeners\AssignDefaultRole;
use App\Listeners\AssignModelOwner;
use App\Listeners\CopyIndicatorsToDataListener;
use App\Listeners\DetachAllAssignedUsers;
use App\Listeners\ReputationalAnalysisReadyListner;
use App\Listeners\RunCompanyCreationDefaultActions;
use App\Listeners\RunCustomerCreationDefaultActions;
use App\Listeners\RunSupplierCreationDefaultActions;
use App\Listeners\SendAssignableModelUpdatedNotification;
use App\Listeners\SendAssignedUsersNotification;
use App\Listeners\SendDiscoverMessageNotification;
use App\Listeners\SendEnabledUserNotification;
use App\Listeners\SendDataNotificationToValidator;
use App\Listeners\Tasks\CreatedTaskListener;
use App\Listeners\Tasks\UpdatedTaskListener;
use App\Listeners\Saml2\Saml2;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Slides\Saml2\Events\SignedIn;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            AssignDefaultRole::class,
        ],
        EnabledUser::class => [
            SendEnabledUserNotification::class,
        ],
        AssignedUsers::class => [
            SendAssignedUsersNotification::class,
        ],
        CreatedAssignableModel::class => [
            AssignModelOwner::class,
        ],
        CreatedCompany::class => [
            RunCompanyCreationDefaultActions::class,
            RunCustomerCreationDefaultActions::class,
            RunSupplierCreationDefaultActions::class,
        ],
        UpdatedAssignableModel::class => [
            SendAssignableModelUpdatedNotification::class,
        ],
        DeletedAssignableModel::class => [
            DetachAllAssignedUsers::class,
        ],
        DiscoverMessage::class => [
            SendDiscoverMessageNotification::class,
        ],
        ApiTokenGeneratedEvent::class => [
            SendApiTokenListener::class,
        ],
        CreatedTaskEvent::class => [
            CreatedTaskListener::class,
        ],
        UpdatedTaskEvent::class => [
            UpdatedTaskListener::class,
        ],
        ReputationalAnalysisReady::class => [
            ReputationalAnalysisReadyListner::class,
        ],
        CopyIndicatorsToData::class => [
            CopyIndicatorsToDataListener::class,
        ],
        SignedIn::class => [
            Saml2::class
        ],
        CreatedData::class => [
            SendDataNotificationToValidator::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
