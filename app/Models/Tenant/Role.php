<?php

namespace App\Models\Tenant;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['default', 'name', 'description', 'guard_name'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'default' => $this->default,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
