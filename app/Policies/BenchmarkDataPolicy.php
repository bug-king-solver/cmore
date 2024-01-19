<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\BenchmarkData;
use Illuminate\Auth\Access\HandlesAuthorization;

class BenchmarkDataPolicy
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
     * @param  \App\Models\BenchmarkData  $benchmarkData
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, BenchmarkData $benchmarkData)
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
        if (request()->is('*/benchmark-reports/*') || request()->query('viaResource') == 'benchmark-reports' || request()->has('editMode')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\BenchmarkData  $benchmarkData
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, BenchmarkData $benchmarkData)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\BenchmarkData  $benchmarkData
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, BenchmarkData $benchmarkData)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\BenchmarkData  $benchmarkData
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin, BenchmarkData $benchmarkData)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\BenchmarkData  $benchmarkData
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin, BenchmarkData $benchmarkData)
    {
        return true;
    }
}
