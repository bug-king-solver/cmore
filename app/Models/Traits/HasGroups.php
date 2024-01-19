<?php

namespace App\Models\Traits;

use App\Models\Enums\ResourcesForGroups;
use App\Models\Tenant\Groupable;
use App\Models\Tenant\Groups;
use App\Models\Tenant\Target;
use Illuminate\Database\Eloquent\Model;

trait HasGroups
{
    public static function mountGroupActions(int $childrens, ?Groups $parentGroup): array
    {
        $data = [];
        if ($childrens > 0) {
            $data['deleteAll'] = __('Delete the Group and all children');
            $data['deleteGroup'] = __('Only delete the Group and leave the children without a parent group');
        }
        if ($parentGroup) {
            /**
             * Todo implement this function
             */
            // $data['moveToParentGroup'] = __('Move to parent group ( :parentGroup )', ['parentGroup' => $parentGroup->name]);
        }

        return $data;
    }

    /**
     * Use from resources ( targets, questionnaires, etc)
     * @example Model::groups()->get()
     */
    public static function groups()
    {
        return Groups::where('resource', static::class)
            ->with('targets.users')
            ->withCount('subgroups')
            ->withCount('targets');
    }

    /**
     * If is a subgroup (work to resources to ) this function return the parent group
     * @example Groups::find(2)->parentGroup
     */
    public function parentGroup()
    {
        return $this->morphOne(Groupable::class, 'groupable');
    }

    /**
     * This is a dynamic function to search all records that are not in a groupable table.
     * @example Model::notInGroupable()->get()
     * @return Model
     */
    public static function notInGroupable()
    {
        return (new static())::whereNotIn('id', function ($query) {
            $query->select('groupable_id')
                ->from('groupables')
                ->where('groupable_type', static::class);
        })->with('users')
            ->with('tasks');
    }

    /**
     * Save the resource in the group
     * @param Model $resource The resource to save in the group
     * @param int $parentGroup The group in which the resource will be saved
     * @return void
     */
    public function saveResourceInGroup($resource, int $parentGroup)
    {
        Groupable::updateOrCreate(
            [
                'groups_id' => $parentGroup,
                'groupable_id' => $resource->id,
                'groupable_type' => $resource->getMorphClass(),
            ],
            [
                'groups_id' => $parentGroup,
                'groupable_id' => $resource->id,
                'groupable_type' => $resource->getMorphClass(),
            ]
        );
    }

    /**
     * Drop the resource from the group
     * @param Model $resource The resource to drop from the group
     * @return void
     */
    public function dropResourceFromGroup($resource)
    {
        Groupable::where('groupable_id', $resource->id)
            ->where('groupable_type', $resource->getMorphClass())
            ->delete();
    }

    public function saveGroupInGroup(Groups $group, Groups $parentGroup)
    {
        /** Double check to not save the same group twice in groupables */
        Groupable::updateOrCreate(
            [
                'groups_id' => $parentGroup->id,
                'groupable_id' => $group->id,
                'groupable_type' => $group->getMorphClass(),
            ],
            [
                'groups_id' => $parentGroup->id,
                'groupable_id' => $group->id,
                'groupable_type' => $group->getMorphClass(),
            ]
        );
    }

    public function deleteGroupActions(Groups $group, string $action)
    {
        switch ($action) {
            case 'deleteAll':
                $this->deleteAll($group);
                break;
            case 'deleteGroup':
                $this->deleteGroup($group);
                break;
            case 'moveToParentGroup':
                $this->moveToParentGroup($group);
                break;
        }
    }

    /**
     * Search for the first group of the GroupId.
     * Find the current subgroup level of the groupId
     * @param int $groupId
     * @return array
     */
    public static function firstGroupAndCurrentLevel(int $groupId): array
    {
        $group = Groups::whereId($groupId)->first();
        $groupNavigation = [];
        $groupNavigation[] = $group->toArray();
        $currentLevel = 1;

        if ($group) {
            while ($group->parentGroup != null) {
                $group = $group->parentGroup->groupableParentGroup;
                $groupNavigation[] = $group->toArray();
                $currentLevel++;
            }
        }

        return [
            'group' => $group,
            'level' => $currentLevel,
            'groupNavigation' => array_reverse($groupNavigation),
        ];
    }

    public static function allSubGroups($groupId): array
    {
        $group = Groups::whereId($groupId)->with('subGroups.groupable.targets', 'targets')->first();

        $subGroupIds = $group->subGroups->pluck('groupable_id')->toArray();
        if ($group->subGroups->isEmpty()) {
            return $subGroupIds;
        }
        foreach ($group->subGroups as $subGroup) {
            if ($subGroup->groupable_id) {
                $subGroupIds = array_merge($subGroupIds, self::allSubGroups($subGroup->groupable_id));
            }
        }

        return $subGroupIds;
    }

    public static function allTargetsFromSubGroups($groupId): array
    {
        $group = Groups::whereId($groupId)->with('subGroups', 'targets')->first();
        $targetsId = $group->targets->pluck('id')->toArray();

        foreach ($group->subGroups as $subGroup) {
            if ($subGroup->groupable_id) {
                $targetsId = array_merge($targetsId, self::allTargetsFromSubGroups($subGroup->groupable_id));
            }
        }

        return $targetsId;
    }

    /**
     * Recursive function to fetch all subgroups and targets of a group
     * @param Groups $group
     * @return array
     */
    public function deleteGroupable(Groups $group)
    {
        $subgroups = [];
        $targets = [];

        $groupId = $group->id;

        $group->subgroups->each(function ($subgroup) use (&$subgroups) {
            $subgroups[] = $subgroup->groupable_id;
        });

        $group->targets->each(function ($target) use (&$targets) {
            $targets[] = $target->id;
        });

        /**
         * check if the group has subgroups and if it has, add them to the array. And if the subgroup has subgroups, add them to the array.
         * This is done until there are no more subgroups.
         */
        $arrSubGroups = $subgroups;
        $arrTargets = $targets;

        while (count($subgroups) > 0) {
            $newGroup = Groups::whereIn('id', $subgroups)->get();
            foreach ($newGroup as $group) {
                if ($group->subgroups->count() > 0) {
                    $subgroups = $group->subgroups->pluck('groupable_id')->toArray();
                    $arrSubGroups = array_merge($arrSubGroups, $subgroups);
                } else {
                    $subgroups = [];
                }
                if ($group->targets->count() > 0) {
                    $targets = $group->targets->pluck('id')->toArray();
                    $arrTargets = array_merge($arrTargets, $targets);
                }
            }
        }
        $arrSubGroups[] = $groupId;

        return [
            'subgroups' => $arrSubGroups,
            'targets' => $arrTargets,
        ];
    }

    public function deleteAll(Groups $group)
    {
        $dropData = $this->deleteGroupable($group);

        Groupable::whereIn('groupable_id', $dropData['subgroups'])
            ->where('groupable_type', $group->getMorphClass())
            ->delete();
        Groupable::whereIn('groups_id', $dropData['subgroups'])->delete();
        Groupable::whereIn('groupable_id', $dropData['targets'])
            ->where('groupable_type', ResourcesForGroups::fromName('target'))
            ->delete();

        Groups::whereIn('id', $dropData['subgroups'])->delete();
        Target::whereIn('id', $dropData['targets'])->delete();

        $group->delete();
    }

    public function deleteGroup(Groups $group)
    {
        Groupable::whereIn('groupable_id', [$group->id])
            ->where('groupable_type', $group->getMorphClass())
            ->orWhereIn('groups_id', [$group->id])
            ->delete();

        $group->delete();
    }

    public function moveToParentGroup()
    {
        $this->group->subgroups()->update(['groups_id' => $this->group->parentGroup->groups_id]);
        $this->group->targets()->update(['groups_id' => $this->group->parentGroup->groups_id]);
        $this->group->delete();
    }
}
