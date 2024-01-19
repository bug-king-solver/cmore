<?php

namespace App\Rules;

use App\Models\Tenant\Groups;
use Illuminate\Contracts\Validation\InvokableRule;

class GroupsLevelRule implements InvokableRule
{
    private null | int | string $groupId;

    private string $resource;

    public function __construct(?string $resource, $groupId = null)
    {
        $this->groupId = $groupId;
        $this->resource = $resource ?? '';
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (! $this->groupId) {
            return;
        }

        $groupLevels = Groups::getMaxGroupLevels($this->resource);

        $firstLevelData = Groups::firstGroupAndCurrentLevel($this->groupId);
        if ($firstLevelData['level'] >= $groupLevels) {
            $fail('You reach the maximum level of ' . $groupLevels . ' for groups.');
        }
    }
}
