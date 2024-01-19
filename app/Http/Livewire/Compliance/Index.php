<?php

namespace App\Http\Livewire\Compliance;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $listeners = [
        'complianceChanged' => '$refresh',
    ];

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.index'
        );
    }
}
