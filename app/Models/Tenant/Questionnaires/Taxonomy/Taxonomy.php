<?php

namespace App\Models\Tenant\Questionnaires\Taxonomy;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use App\Models\Traits\QueryBuilderScopes;
use App\Models\Traits\Taxonomy\TaxonomyTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taxonomy extends Model
{
    use HasFactory;
    use QueryBuilderScopes;
    use TaxonomyTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questionnaire_id',
        'summary',
        'safeguard',
        'completed_at',
        'completed',
        'imported_file_url',
        'started_at'
    ];

    protected $casts = [
        'questionnaire_id' => 'integer',
        'summary' => 'json',
        'safeguard' => 'json',
    ];

    /**
     * Get the default business resume.
     *
     * @return array
     */
    public static function businnesResumeDefault(): array
    {
        $defaultArray = [
            "value" => 0,
            "percentage" => 0,
        ];

        return [
            'total' => [
                'volume' => $defaultArray,
                'capex' => $defaultArray,
                'opex' => $defaultArray,
            ],
            'notEligible' => [
                'volume' => $defaultArray,
                'capex' => $defaultArray,
                'opex' => $defaultArray,
            ],
            'eligibleAligned' => [
                'volume' => $defaultArray,
                'capex' => $defaultArray,
                'opex' => $defaultArray,
            ],
            'eligible' => [
                'volume' => $defaultArray,
                'capex' => $defaultArray,
                'opex' => $defaultArray,
            ],
            'eligibleNotAligned' => [
                'volume' => $defaultArray,
                'capex' => $defaultArray,
                'opex' => $defaultArray,
            ],
            'contribute' => [
                'objectives' => [],
            ],
            'dnsh' => [
                'objectives' => [],
            ],
        ];
    }

    //booted method of the model
    protected static function booted()
    {
        static::creating(function ($taxonomy) {
            $taxonomy->completed = false;
            $taxonomy->summary = self::businnesResumeDefault();
            $taxonomy->safeguard = [];
        });

        static::created(function ($taxonomy) {
            self::calcTaxonomyValues($taxonomy);
            $taxonomy->createSafeguard();
        });

        static::updated(function ($taxonomy) {
            self::calcTaxonomyValues($taxonomy);
        });

        static::deleted(function ($taxonomy) {
            self::calcTaxonomyValues($taxonomy);
        });
    }

    /**
     * Get the user's first name.
     */
    protected function businessResume(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
        );
    }


    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::OnlyOwnData()
            ->with('company');
    }

    /**
     * Get the questionnaire that owns the taxonomy.
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the activities for the taxonomy.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(TaxonomyActivities::class);
    }

    /**
     * Create the safeguars questions
     */
    public function createSafeguard()
    {
        // Set the default question to safeguards , from our .json file
        $safeguard = self::getSafeguardsQuestion(
            $this->questionnaire->minimum_safeguards['type'] ?? 'complete'
        );

        $safeguard = array_merge([
            'percentage' => 0,
            'verified' => null,
            'imported' => 0
        ], $safeguard);

        $this->update([
            'safeguard' => $safeguard,
        ]);

        return $safeguard;
    }

    /**
     * Get the safeguard is verified attribute.
     */
    public function getSafeguardIsImportedAttribute()
    {
        return $this->safeguard['imported'] ?? false;
    }
}
