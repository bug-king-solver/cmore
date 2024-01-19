<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Indicator;
use Illuminate\Auth\Access\HandlesAuthorization;

class IndicatorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Admin $admin)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Tenant\Indicator  $indicator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Indicator $indicator)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Indicator $indicator)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Indicator $indicator)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin, Indicator $indicator)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Indicator  $indicator
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin, Indicator $indicator)
    {
        return true;
    }
}
