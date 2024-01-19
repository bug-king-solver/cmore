<?php

namespace App\Http\Livewire\Groups\Modals;

use App\Http\Livewire\Traits\GroupsManagerTrait;
use App\Http\Livewire\Traits\HasCustomColumns;
use App\Models\Enums\ResourcesForGroups;
use App\Models\Tenant\Groups;
use App\Rules\GroupsLevelRule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;
    use HasCustomColumns;
    use WithPagination;
    use GroupsManagerTrait;

    protected $feature = 'groups';

    public $group;

    public $name;

    public $description;

    protected $listeners = [
        'setResourceEvent' => 'setResourceValue',
    ];

    protected function rules()
    {
        return $this->mergeCustomRules([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:groups,name,' . $this->group->id ?? null,
                new GroupsLevelRule($this->resource, $this->parentGroupId),
            ],
            'description' => 'nullable|string',
        ]);
    }

    protected function getMessages()
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name field must be a string.'),
            'name.max' => __('The name field must not be greater than 255 characters.'),
            'name.unique' => __('The name field must be unique.'),
        ];
    }

    protected function getColumns()
    {
        return [
            'name' => __('Group'),
            'description' => __('Description'),
        ];
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(Groups $group)
    {
        $this->group = $group;
        if ($this->group->exists) {
            $this->name = $this->group->name;
            $this->description = $this->group->description;
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.groups.form');
    }

    /** function saveGroup */
    public function save()
    {
        $this->authorize(! $this->group->exists ? 'groups.create' : "groups.update.{$this->group->id}");

        $data = $this->validate(
            $this->getRules(),
            $this->getMessages(),
            $this->getColumns()
        );

        if ($this->resource && $this->parentGroupId == null) {
            $data['resource'] = ResourcesForGroups::fromName($this->resource);
        }

        $this->group->fill($data);

        if (! $this->group->exists) {
            $this->group->save();
        } else {
            $this->group->update();
        }

        if ($this->parentGroupId) {
            $parentGroup = Groups::find($this->parentGroupId);
            $this->group->saveGroupInGroup($this->group, $parentGroup);
        }

        $resourceFromGroup = null;
        if ($this->group->resource) {
            $resourceFromGroup = ResourcesForGroups::fromValue($this->group->resource);
        }
        if ($this->resource || $resourceFromGroup) {
            $this->afterChangeGroupable($this->resource ?? $resourceFromGroup);
        }

        $this->afterChangeGroupable($this->resource);

        $this->closeModal();
    }

    public function updatedDescription($value)
    {
        $this->description = $value;
    }
}
