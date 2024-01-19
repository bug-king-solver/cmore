<?php

namespace App\Models\Tenant\Compliance\DocumentAnalysis;

use App\Enums\Compliance\DocumentAnalysis\ResultComplianceLevel;
use App\Enums\Compliance\DocumentAnalysis\ResultStatus;
use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use App\Models\Tenant\Compliance\DocumentAnalysis\Snippet;
use App\Models\Tenant\MediaType;
use App\Models\Traits\QueryBuilderScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Result extends Model implements HasMedia
{
    use HasFactory;
    use QueryBuilderScopes;
    use InteractsWithMedia;

    protected $table = 'document_analysis_results';

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected $fillable = [
        'document_analysis_type_id',
        'started_at',
        'ended_at',
        'compliance_level',
    ];

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (is_null($attributes['started_at'])) {
                    return ResultStatus::IN_QUEUE;
                } elseif (is_null($attributes['ended_at'])) {
                    return ResultStatus::PROCESSING;
                } else {
                    return ResultStatus::COMPLETE;
                }
            },
        );
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function complianceLevel(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value)) {
                    return ResultComplianceLevel::WAITING;
                } elseif ($value < 25) {
                    return ResultComplianceLevel::LOW;
                } elseif ($value >= 25 && $value <= 75) {
                    return ResultComplianceLevel::MEDIUM;
                } else {
                    return ResultComplianceLevel::HIGH;
                }
            },
        );
    }

    public function type()
    {
        return $this->belongsTo(MediaType::class, 'document_analysis_type_id');
    }

    public function domains()
    {
        return $this->hasManyThrough(
            Domain::class,
            Snippet::class,
            'document_analysis_result_id',
            'id',
            null,
            'document_analysis_domain_id'
        )->distinct();
    }

    public function domainsFromMedia()
    {
        return $this->hasMany(Domain::class, 'document_analysis_type_id', 'document_analysis_type_id');
    }

    public function snippets()
    {
        return $this->hasMany(Snippet::class, 'document_analysis_result_id');
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::OnlyOwnData()
            ->with('type')
            ->orderBy('ended_at', 'DESC');
    }
}
