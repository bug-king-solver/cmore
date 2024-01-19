<?php

namespace App\Http\Livewire\Companies;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\Company;
use Illuminate\View\View;
use Livewire\Component;

class Welcome extends Component
{
    public function render(): View
    {
        return tenantView('tenant.companies.welcome');
    }
}
