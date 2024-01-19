<?php

namespace App\Http\Livewire\Traits;

use App\Models\Enums\ResourcesForGroups;
use App\Models\Tenant\Groups;
use Illuminate\View\View;

trait GroupsManagerTrait
{
    public $parentGroupId;

    public $resource;

    public $searchName;

    public $resourceGroups;

    public function bootGroupsManagerTrait()
    {
        $this->listeners += [
            'openGroupsModal' => 'openGroupsModal',
            'openTargetModal' => 'openTargetModal',
            'saveNewGroup' => 'saveNewGroup',
            'groupSaved' => '$refresh',
        ];
    }

    public function mountGroupsManagerTrait()
    {
        if (request()->route('groupId')) {
            $this->parentGroupId = request()->route('groupId');
        }
    }

    /**
     * Get the resource to which the effect is applied
     * Get if has, the parent group id from the route
     * Get the list of groups to which the resource is assigned
     * @param string $resourceName The resource to which the effect is applied
     * @param null|string $searchName The search name to filter the resource
     * @return array
     */
    public function prepareDataToReturnToView(string $resourceName, null|string $searchName)
    {
        $this->resource = $resourceName;

        $modelResources = collect([]);
        $collectionResources = new \Illuminate\Database\Eloquent\Collection();
        $user = auth()->user();

        if (request()->route('groupId')) {
            $this->parentGroupId = request()->route('groupId');
        }

        if ($this->parentGroupId) {
            $groupList = Groups::where('id', $this->parentGroupId)->first();
            $modelResources = $groupList->targets()->OnlyOwnData($user);
            $groupList = $groupList->subgroups()->with('groupable')->get()->map(function ($group) {
                return $group->groupable;
            });
        } else {
            $modelClass = ResourcesForGroups::fromName($resourceName);
            $model = new $modelClass();
            $modelResources = $model::notInGroupable();

            $groupList = $model::groups();

            if ($searchName) {
                $groupList = $groupList->where('name', 'like', '%' . $searchName . '%');
            }
            $groupList = $groupList->get();
        }

        $modelResources = $modelResources->with('users', 'tasks', 'indicator');

        if ($searchName) {
            $modelResources = $modelResources->where('title', 'like', '%' . $searchName . '%');
        }

        $modelResources = $modelResources->onlyOwnData()->get();

        // /** combine $resources with resourcesGroup */
        $collectionResources = $collectionResources->concat($modelResources);
        $collectionResources = $collectionResources->concat($groupList);

        $resourceGroups['data'] = $collectionResources->all();
        $resourceGroups['resources'] = $collectionResources->filter(fn ($item) => ! isGroup($item));
        $resourceGroups['groups'] = $collectionResources->filter(fn ($item) => isGroup($item));

        $resourceGroups['groups']->map(function ($group) {
            $group['allTargets'] = Groups::allTargetsFromSubGroups($group->id);
            $group['chartData'] = $this->getTargetDataChart($group['allTargets']);
        })->values()->all();

        $this->resourceGroups = $resourceGroups;
    }

    /**
     * Merge the default data with the data passed to the view and return the view
     *
     * @param string $view - The view to be returned
     * @param array $data - The data to be merged with the default data
     * @return View
     */
    public function mergeDataToBuildGroupsView(string $view, array $data = []): View
    {
        $groupLevel = 0;
        $parentGroup = null;
        $groupsData = null;
        $breadcrumbs = [];

        if (isset($this->parentGroupId)) {
            $parentGroup = Groups::find($this->parentGroupId);
            $groupsData = Groups::firstGroupAndCurrentLevel($this->parentGroupId);
            if ($groupsData) {
                $groupLevel = $groupsData['level'];
            }

            $breadcrumbs = mountBreadcrumb(
                $groupsData['groupNavigation'] ?? [],
                'tenant.targets.groups',
                'groupId',
                'name'
            );
        }

        $groupsButtonDropdown = [];
        if ($groupLevel < Groups::getMaxGroupLevels($this->resource)) {
            $groupsButtonDropdown = [
                'name' => __('New group'),
                'icon' => 'icons/library/plus-folder',
                'customClickEvent' => '\'targets.index\', \'openGroupsModal\'',
                'customClickParams' => ['target', $this->parentGroupId ?? null],
            ];
        }

        $data = array_merge(
            $data,
            [
                'groupId' => $this->parentGroupId,
                'parentGroup' => $parentGroup ?? null,
                'groupsButtonDropdown' => $groupsButtonDropdown,
                'breadcrumbs' => $breadcrumbs,
                'targetGroups' => $this->resourceGroups,
                'groupLevel' => $groupLevel,
                'groupLevelDescription' => Groups::groupLevelDescription($groupLevel, $this->resource),
            ]
        );

        return view($view, $data);
    }

    /**
     * Function to save a new group . This function is called from components that isnt group
     */
    public function saveNewGroup()
    {
        $this->emitTo('groups.modals.form', 'save');
    }

    /**
     * Set the resource for the group inject into the modal from Groups component
     * @param string $resource The resource to which the effect is applied
     */
    public function openGroupsModal(string $resource, $parentGroupId = null)
    {
        $this->resource = $resource;
        $this->emit('openModal', 'groups.modals.form', [
            'resource' => $resource,
            'parentGroupId' => $parentGroupId,
        ]);
    }

    public function openTargetModal($parentGroupId = null)
    {
        $this->emit('openModal', 'targets.modals.form', [
            'parentGroupId' => $parentGroupId,
        ]);
    }

    /**
     * Emit targetsChanged to the index component of the resource
     * @param string|null $resource
     */
    public function afterChangeGroupable(?string $resource)
    {
        if ($resource) {
            $component = "{$resource}s.index";
            $event = "{$resource}sChanged";
            \sleep(1);
            $this->emitTo($component, $event);
        }
    }
}
