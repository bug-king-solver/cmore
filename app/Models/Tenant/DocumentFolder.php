<?php

namespace App\Models\Tenant;

use App\Enums\Documents\FolderVisibility;
use App\Models\Tenant\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class DocumentFolder extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Translatable columns
     */
    protected $fillable = ['name', 'parent_id', 'slug', 'visibility'];

    protected $casts = [
        'visibility' => FolderVisibility::class,
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function accessibleChilds()
    {
        if (auth()->user()->type != 'internal') {
            return $this->child()->where('visibility', 'external', 1);
        }

        return $this->child();
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (! is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    /**
     * Check if a company is not a supplier
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => encodeHtmlToString($value),
        );
    }

    public function getFullSlugAttribute()
    {
        $parents = $this->parents;
        $slug = [];
        $slug[] = $this->slug;
        foreach ($parents as $parent) {
            $slug[] = $parent->slug;
        }
        $slug = array_reverse($slug);

        return implode('/', $slug);
    }

    /**
     * get files count
     */
    public function getTotalFilesAttribute()
    {
        return Media::where('collection_name', config('media-library.collection.internal'))
            ->whereJsonContains('custom_properties->folder', $this->id)->count();
    }
}
