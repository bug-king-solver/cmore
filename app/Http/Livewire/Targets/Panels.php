<?php

namespace App\Http\Livewire\Targets;

use App\Http\Livewire\Traits\CustomPagination;
use App\Http\Livewire\Traits\TasksTrait;
use App\Models\Tenant\Target;
use Illuminate\View\View;
use Livewire\Component;

class Panels extends Component
{
    use CustomPagination;
    use TasksTrait;

    protected $listeners = [
        'targetsChanged' => '$refresh',
    ];

    public function render(): View
    {
        $targets = Target::list(auth()->user())->paginate($this->selectedItemsPerPage);

        return view(
            'livewire.tenant.targets.panels',
            [
                'targets' => $targets,
            ]
        );
    }
}
