<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Slides\Saml2\Models\Tenant as TenantSSO;

class Saml2Tenants extends TenantSSO
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
        'is_default' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if ($model->is_default) {
                Saml2Tenants::where('is_default', '=', 1)->update(['is_default' => 0]);
            }
        });

        self::updating(function ($model) {
            if ($model->is_default) {
                Saml2Tenants::where([
                    ['uuid', '!=', $model->uuid],
                    ['is_default', '=', 1]
                ])->update(['is_default' => 0]);
            }
        });
    }
}
