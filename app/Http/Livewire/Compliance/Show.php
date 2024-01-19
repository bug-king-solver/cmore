<?php

namespace App\Http\Livewire\Compliance;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    protected function getListeners()
    {
        return [
            'complianceChanged' => '$refresh',
        ];
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.show'
        );
    }
}
