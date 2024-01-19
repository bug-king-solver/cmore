<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Domain;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
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
        return $admin->is_reporter != 1;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Domain $domain)
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
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Domain $domain)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Domain $domain)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin, Domain $domain)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin, Domain $domain)
    {
        return true;
    }
}
