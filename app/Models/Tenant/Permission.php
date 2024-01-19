<?php

namespace App\Models\Tenant;

class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group', 'name', 'order', 'guard_name', 'description'];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'group' => $this->group,
            'order' => $this->order,
            'created_at' => $this->created_at,
        ];
    }
}
