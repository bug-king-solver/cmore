<?php

namespace App\Models\Tenant\Filters\User;

use App\Models\Tenant\Filters\vendor\FilterRelationTrait;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\Role;
use App\Models\User;

class RolesFilter extends SelectInFilter
{
    use FilterRelationTrait;

    protected string $field = 'role_id';

    protected string $relation = 'roles';

    public function title(): string
    {
        return __('Teams');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Role::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
