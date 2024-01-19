<?php

namespace App\Models\Tenant\Filters\Wallet;

use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Transaction;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class TypeFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'type';

    public function title(): string
    {
        return __('Type');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Transaction::query()
            ->orderBy('created_at')
            ->pluck('type', 'type')
            ->toArray();
    }
}
