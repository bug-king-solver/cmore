<?php

namespace App\Http\Livewire\Targets;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Http\Livewire\Traits\GroupsManagerTrait;
use App\Http\Livewire\Traits\TargetQueryTrait;
use App\Http\Livewire\Traits\TasksTrait;
use App\Models\Tenant\Target;
use App\Models\Tenant\Task;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use BreadcrumbsTrait;
    use CustomPagination;
    use GroupsManagerTrait;
    use TasksTrait;
    use TargetQueryTrait;

    public $listeners = [
        'targetsChanged' => '$refresh',
    ];

    protected $targetTasks;

    public function mount()
    {
        $this->addBreadcrumb(__('Targets'));
    }

    public function render(): View
    {
        $this->prepareDataToReturnToView('target', $this->searchName);
        $this->targetTasks = Task::listTargets()->paginate($this->selectedItemsPerPage);

        return $this->mergeDataToBuildGroupsView('livewire.tenant.targets.index');
    }
}
