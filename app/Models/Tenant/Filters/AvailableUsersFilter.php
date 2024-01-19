<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Filters\vendor\FilterRelationTrait;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\User;

class AvailableUsersFilter extends SelectInFilter
{
    use FilterRelationTrait;

    protected string $field = 'user_id';

    protected string $relation = 'users';

    public function title(): string
    {
        return __('Users');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return User::list(auth()->user()->id)
            ->where('system', false)
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
