<?php

namespace App\Models\Tenant\Filters\User;

use Lacodix\LaravelModelFilter\Filters\SelectFilter;

class IsEnabledFilter extends SelectFilter
{
    protected string $field = 'enabled';

    public function title(): string
    {
        return __('Is user enabled');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return [
            __('Enabled') => true,
            __('Disabled') => false,
        ];
    }
}
