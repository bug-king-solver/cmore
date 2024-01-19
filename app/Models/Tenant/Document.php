<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Document extends Model
{
    use HasTranslations;
    use HasFactory;

    /**
     * Translatable columns
     */
    public $translatable = ['name', 'file'];

    /**
     * Check if a company is not a supplier
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => encodeHtmlToString($value),
        );
    }
}
