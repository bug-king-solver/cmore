<?php

namespace App\Http\Livewire\Targets;

use App\Http\Livewire\Traits\TasksTrait;
use App\Models\Enums\TargetStatus;
use App\Models\Tenant\Target;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;
    use TasksTrait;

    public $statusList;

    public $userables;

    public int|Target $target;

    public $status;

    public int $attachmentsCount = 0;

    protected function getListeners()
    {
        return [
            'targetsChanged' => '$refresh',
            "attachmentsChanged{$this->target->id}" => '$refresh',
        ];
    }

    protected function statusRules()
    {
        return [
            'status' => ['required', Rule::in(TargetStatus::toValues())],
        ];
    }

    public function mount(int $target)
    {
        $this->target = Target::onlyOwnData()->where('id', $target)->with('users', 'tasks')->firstOrFail();

        $this->authorize("targets.view.{$this->target->id}");

        $this->statusList = TargetStatus::toArray();
        $this->userables = $this->target->users ? $this->target->users()->get() : [];
        $this->status = $this->target->status;
    }

    public function render(): View
    {
        $attachments = $this->target->attachments()->get();

        return view(
            'livewire.tenant.targets.show',
            [
                'target' => $this->target,
                'users' => $this->userables,
                'attachments' => $attachments,
            ]
        );
    }

    public function updatedStatus()
    {
        $this->authorize("targets.update.{$this->target->id}");

        $data = $this->validate($this->statusRules());

        $this->target->update($data);
    }

    public function destroy($id)
    {
        $this->authorize("targets.delete.{$this->target->id}");
        $this->target->detach($id);
    }
}
