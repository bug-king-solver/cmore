<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DashboardTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'layout', 'filters'];

    /**
     * Get a list of the dashboard
     *
     * @return Collection
     */
    public static function list()
    {
        return self::
            orderBy('name');
    }
}
