<?php

namespace App\Http\Livewire\Groups\Modals;

use App\Http\Livewire\Traits\GroupsManagerTrait;
use App\Http\Livewire\Traits\HasCustomColumns;
use App\Models\Enums\ResourcesForGroups;
use App\Models\Tenant\Groups;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;
    use HasCustomColumns;
    use GroupsManagerTrait;

    public Groups | int $group;

    public $subgroups = [];

    public $targets = [];

    public $hasChildren = false;

    public $groupDeleteOptions;

    public $hasChildrenOptions;

    public $confirmDelete;

    protected $feature = 'groups';

    public $redirect = false;

    protected function rules()
    {
        $this->subgroups = $this->group->subgroups()->get();
        $this->targets = $this->group->targets()->get();
        $this->group->childrens = $this->subgroups->merge($this->targets)->count();

        if ($this->group->childrens > 0) {
            return $this->mergeCustomRules([
                'groupDeleteOptions' => 'required',
                'confirmDelete' => 'required_with:groupDeleteOptions',
            ]);
        } else {
            return $this->mergeCustomRules([
                'groupDeleteOptions' => 'nullable',
            ]);
        }
    }

    protected $messages = [
        'confirmDelete.required_with' => 'You must confirm the action',
    ];

    public function mount(Groups $group, $redirect = false)
    {
        $this->group = $group;

        $this->subgroups = $group->subgroups()->get();
        $this->targets = $group->targets()->get();

        $this->group->childrens = $this->subgroups->merge($this->targets)->count();
        $this->redirect = $redirect;

        $parentGroup = null;
        if ($this->group->parentGroup) {
            $parentGroup = Groups::find($this->group->parentGroup->groups_id);
        }

        $this->hasChildrenOptions = Groups::mountGroupActions($this->group->childrens, $parentGroup);

        if ($this->group->childrens > 0) {
            $this->hasChildren = true;
        }

        $this->authorize("groups.delete.{$this->group->id}");
    }

    public function render()
    {
        return view('livewire.tenant.groups.delete');
    }

    public function updatedGroupDeleteOptions($value)
    {
        $this->confirmDelete = null;
    }

    public function updatedConfirmDelete($value)
    {
        if ($value == 'no') {
            $this->groupDeleteOptions = null;
            $this->confirmDelete = null;
        }
    }

    public function delete()
    {
        $data = $this->validate();
        $firstGroup = Groups::firstGroupAndCurrentLevel($this->group->id);
        $resourceFromGroup = ResourcesForGroups::fromValue($firstGroup['group']->resource);

        if ($this->redirect) {
            if ($this->group->parentGroup) {
                $parentGroup = $this->group->parentGroup->groups_id;
                $redirectRoute = route('tenant.targets.groups', ['groupId' => $parentGroup]);
            } else {
                $resourceFromGroup .= 's';
                $redirectRoute = route("tenant.$resourceFromGroup.index");
            }
        }

        if (isset($data['groupDeleteOptions'])) {
            $this->group->deleteGroupActions($this->group, $data['groupDeleteOptions']);
        } else {
            $this->group->deleteGroupActions($this->group, 'deleteGroup');
        }

        if ($this->redirect) {
            return redirect($redirectRoute);
        } else {
            $this->afterChangeGroupable($resourceFromGroup);
            $this->closeModal();
        }
    }
}
