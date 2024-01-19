<?php

namespace App\Models\Tenant\Questionnaires\PhysicalRisks;

use App\Models\Enums\PhysicalRisksRelevanceEnum;
use App\Models\Enums\Risk;
use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\CompanyAddresses;
use App\Models\Tenant\Questionnaire;
use App\Models\Traits\QueryBuilderScopes;
use App\Services\ThinkHazard\ThinkHazard;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalRisks extends Model
{
    use HasFactory;
    use QueryBuilderScopes;

    protected $table = 'questionnaire_physicalrisks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by_user_id',
        'questionnaire_id',
        'business_sector_id',
        'name',
        'description',
        'country_iso',
        'country_code',
        'region_code',
        'city_code',
        'hazards',
        'note',
        'relevant',
        'company_address_id'
    ];

    protected $casts = [
        'created_by_user_id' => 'integer',
        'questionnaire_id' => 'integer',
        'business_sector_id' => 'integer',
        'company_address_id' => 'integer',
        'hazards' => 'array',
    ];

    /**
     * Get the default business resume.
     *
     * @return array
     */
    public static function haardDefault(): array
    {
        return [];
    }

    //booted method of the model
    protected static function booted()
    {
        static::created(function ($physical) {
        });

        static::created(function ($physical) {
        });

        static::updated(function ($physical) {
        });

        static::deleted(function ($physical) {
        });
    }
    /**
     * Get the risk level label.
     * @param string $slug - the slug of the risk level
     */
    public function getRiskLevelLabel($slug): string
    {
        return Risk::fromSlug($slug)->label() ?? '';
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::OnlyOwnData();
    }

    /**
     * Get the questionnaire that owns the taxonomy.
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Belongs to Company location
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(CompanyAddresses::class, 'company_address_id');
    }

    /**
     * Get the questionnaire that owns the taxonomy.
     * @return BelongsTo
     * TODO Refactor this code, to use the function below intead of this one.
     */
    public function businessActivities(): BelongsTo
    {
        return $this->belongsTo(BusinessSector::class, 'business_sector_id');
    }

    /**
     * Get the questionnaire that owns the taxonomy.
     */
    public function businessSector(): BelongsTo
    {
        return $this->belongsTo(BusinessSector::class, 'business_sector_id');
    }

    /**
     * Get the label ( translated ) of the relevant attribute.
     * @return Attribute
     */
    public function relevantLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => PhysicalRisksRelevanceEnum::from($this->relevant)->label() ?? $this->relevant,
        );
    }

    /**
     * Get the full location attribute.
     * @return Attribute
     */
    public function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->location->fullAddress;
            }
        );
    }
}
