<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Groupable;
use App\Models\Tenant\Target;
use App\Models\Traits\HasGroups;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasGroups;

    protected $fillable = [
        'name',
        'description',
        'resource',
    ];

    protected static $maxGroupLevels = 10;

    public static function getGroupFeaturesAttribute(string $resource)
    {
        $features = tenant()->initFeatures();
        if ($features->getFeature('groups_levels', $resource, 'depth')) {
            return $features->getFeature('groups_levels', $resource);
        } elseif ($features->getFeature('groups_levels', $resource . 's')) {
            return $features->getFeature('groups_levels', $resource . 's');
        }
    }

    /**
     * Get the max number of group levels allowed
     * @return int
     */
    public static function getMaxGroupLevels(string $resource): int
    {
        $feature = self::getGroupFeaturesAttribute($resource);

        return $feature->depth ?? self::$maxGroupLevels;
    }

    /**
     * Get the max number of group levels allowed
     * @return int
     */
    public static function groupLevelDescription(int $level, string $resource): string
    {
        $feature = self::getGroupFeaturesAttribute($resource);
        $groupLevels = $feature->levels ?? null;

        return $groupLevels->$level ?? __('Groups');
    }

    public function subgroups()
    {
        return $this->morphMany(Groupable::class, 'groupable', 'groupable_type', 'groups_id', 'id')
            ->orderBy('groups_id');
    }

    /**
     * ToDo make this relation universal to all resources ( targets , questionnaie, etc )
     */
    public function targets()
    {
        return $this->morphedByMany(Target::class, 'groupable');
    }
}
