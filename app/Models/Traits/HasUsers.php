<?php

namespace App\Models\Traits;

use App\Events\AssignedUsers;
use App\Events\CreatedAssignableModel;
use App\Events\UpdatedAssignableModel;
use App\Models\User;
use Http\Client\Exception\HttpException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Validation\ValidationException;

trait HasUsers
{
    public $recentlyAssignedUsers = [];


    /**
     * Boot the trait.
     */
    protected static function bootHasUsers()
    {
        static::updating(function ($model) {
            $ignoreHasUserValidation = $model->ignore_has_user_validation ?? false;
            if ($ignoreHasUserValidation || !auth()->check()) {
                return true;
            }
            unset($model->ignore_has_user_validation);

            $modelId = $model->id;
            $user = auth()->user() ?? null;
            $resource = method_exists($model, 'scopeOnlyOwnData')
                ? $model->onlyOwnData()->find($modelId)
                : $model->find($modelId);



            if (!$user || !$resource) {
                throw ValidationException::withMessages(['id' => __('You are not allowed to update this resource')]);
            }
        });

        static::deleting(function ($model) {
            $ignoreHasUserValidation = $model->ignore_has_user_validation ?? false;
            if ($ignoreHasUserValidation || !auth()->check()) {
                return true;
            }
            unset($model->ignore_has_user_validation);


            $modelId = $model->id;
            $user = auth()->user() ?? null;
            $resource = method_exists($model, 'scopeOnlyOwnData')
                ? $model->onlyOwnData()->find($modelId)
                : $model->find($modelId);

            if (!$user || !$resource) {
                throw ValidationException::withMessages(['id' => __('You are not allowed to delete this resource')]);
            }
        });
    }



    /**
     * Add owner to the assignment list
     */
    public function parseDispatchesEvents()
    {
        $assignmentEvents = [
            // 'created' => CreatedAssignableModel::class,
            'updated' => UpdatedAssignableModel::class,
        ];

        $this->dispatchesEvents = array_merge($this->dispatchesEvents, $assignmentEvents);
    }

    /**
     * List of available users to be assigned to the model.
     *
     * For example, in answers it should be: return $this->questionnaire->users;
     */
    public function availableUsers()
    {
        return $this->users;
    }

    /**
     * List of users assigned to the model
     */
    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'userable')->withTimestamps()->withPivot('assignment_type');
    }

    /**
     * Check if the providen user has access to the resource
     */
    public function canBeAccessedBy(Authenticatable $user)
    {
        return $this->users->contains($user) || $user->isOwner();
    }

    /**
     * Get the new assigned users.
     */
    protected function newlyAssignedUsers($allUsers)
    {
        $oldUsers = $this->users->pluck('id')->toArray();

        return array_diff($allUsers, $oldUsers);
    }

    /**
     * Set the user assigned to the model
     * @param array $users
     * @param User|Target|Questionnaire|Model $assigner
     */
    public function assignUsers(array $users, $assigner, $assignment_type = null)
    {
        // Not all models have an owner. Example: answer.
        $owner = $this->created_by_user_id
            ? [$this->created_by_user_id]
            : [];

        // Merge selected users with the owner
        $users = array_unique(array_merge($users, $owner));

        // Newly assigned users - just for notifications purpose
        $newlyAssignedUsers = $this->newlyAssignedUsers($users);

        $currentUsers = $this->users->pluck('id')->toArray();

        if ($assignment_type) {
            $currentUsers = $this->users()
                ->wherePivot('assignment_type', $assignment_type)
                ->pluck('users.id')
                ->toArray();
        }

        /* Get the users to remove from userables table */
        $usersToRemove = array_diff($currentUsers, $users);

        /* Get the users to add to userables table */
        $usersToAdd = array_diff($users, $currentUsers);

        /** detach user from userables */
        foreach ($usersToRemove as $userToRemove) {
            if ($assignment_type) {
                $this->users()->wherePivot('assignment_type', $assignment_type)->detach($userToRemove);
            } else {
                $this->users()->detach($userToRemove);
            }
        }

        /* attach with pivot */
        foreach ($usersToAdd as $user) {
            $this->users()->attach($user, [
                'assigner_id' => $assigner->getKey(),
                'assigner_type' => $assigner->getMorphClass(),
                'assignment_type' => $assignment_type,
            ]);
        }

        event(new AssignedUsers($this, $newlyAssignedUsers, $assigner));
    }
}
