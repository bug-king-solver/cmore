<?php

namespace App\Models\Tenant;

use App\Models\Enums\Companies\Relation;
use App\Models\Enums\Companies\Type;
use App\Models\Enums\CompanySize;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\CompanyAddresses;
use App\Models\Tenant\Concerns\Interfaces\Productable;
use App\Models\Tenant\Concerns\Interfaces\Userable;
use App\Models\Tenant\Data;
use App\Models\Tenant\Filters\AvailableUsersFilter;
use App\Models\Tenant\Filters\Company\BusinessSectorFilter;
use App\Models\Tenant\Filters\Company\CompanyRelationFilter;
use App\Models\Tenant\Filters\Company\CompanySizeFilter;
use App\Models\Tenant\Filters\Company\CompanyTypeFilter;
use App\Models\Tenant\Filters\Company\CountryFilter;
use App\Models\Tenant\Filters\Company\DateFilter;
use App\Models\Tenant\Filters\Company\ParentCompanyFilter;
use App\Models\Tenant\Filters\DateBetweenFilter;
use App\Models\Tenant\Filters\InternalTagsFilter;
use App\Models\Tenant\Filters\TagsFilter;
use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Tenant\GarBtar\BankSimulation;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Traits\Catalog\ProductItem;
use App\Models\Traits\Filters\IsSortable;
use App\Models\Traits\HasCustomColumns;
use App\Models\Traits\HasHiddenColumns;
use App\Models\Traits\HasInternalTags;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasTasks;
use App\Models\Traits\HasUsers;
use App\Models\Traits\QueryBuilderScopes;
use App\Models\User;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\ProductInterface;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Company extends Model implements Userable, Productable, ProductInterface
{
    use HasFactory;
    use LogsActivity;
    use HasCustomColumns;
    use HasDataColumn;
    use HasUsers;
    use HasTags;
    use HasInternalTags;
    use QueryBuilderScopes;
    use HasFilters;
    use HasTasks;
    use HasWallet;
    use IsSearchable;
    use HasHiddenColumns;
    use ProductItem;
    use IsSortable;

    protected $feature = 'companies';

    protected array $filters = [
        BusinessSectorFilter::class,
        ParentCompanyFilter::class,
        CountryFilter::class,
        DateFilter::class,
        DateBetweenFilter::class,
        TagsFilter::class,
        InternalTagsFilter::class,
        AvailableUsersFilter::class,
        CompanySizeFilter::class,
        CompanyTypeFilter::class,
        CompanyRelationFilter::class,
    ];

    protected array $searchable = [
        'name', 'commercial_name', 'vat_number',
    ];

    protected array $sortable = [
        'id' => 'Id',
        'name' => 'Name',
        'created_at' => 'Created at'
    ];

    protected $casts = [
        'founded_at' => 'date',
        'headquarters_eu' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by_user_id',
        'parent_id',
        'business_sector_id',
        'name',
        'commercial_name',
        'type',
        'relation',
        'country',
        'vat_country',
        'vat_number',
        'founded_at',
        'logo',
        'color',
        'size',
        'vat_secundary',
        'headquarters_eu'
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'created_by_user_id',
            'parent_id',
            'business_sector_id',
            'name',
            'commercial_name',
            'type',
            'relation',
            'country',
            'vat_country',
            'vat_number',
            'founded_at',
            'logo',
            'color',
            'headquarters_eu',
            'created_at',
            'updated_at',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->parseCustomColumns();
        $this->parseDispatchesEvents();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'created_at' => $this->created_at,
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // If no company type is used, then use the default value
            if (!$model->type) {
                $model->type = tenant()->companies_type_default;
            }

            // If is an external company and don't have any selected value to the relation,
            // use the default value. It can be null!
            if ($model->is_external && !$model->relation) {
                $model->relation = tenant()->companies_relation_default;
            }

            $model->headquarters_eu = in_array($model->country, getEuCountries())
                ? true
                : false;
            $model->commercial_name = empty($model->commercial_name)
                ? $model->name
                : $model->commercial_name;
            $model->founded_at = $model->founded_at ?? null;
            $model->color = $model->color ?? color(6);

            $model->flow = self::defaultFlowStructure();
        });

        static::created(function ($model) {
            if ($model->getProduct()->is_payable ?? false) {
                tenant()->forceWithdrawFloat($model->getPriceProduct(), $model->getMetaProduct());
            }
        });

        static::updating(function ($model) {

            $model->headquarters_eu = in_array($model->country, getEuCountries())
                ? true
                : false;

            $model->commercial_name = empty($model->commercial_name)
                ? $model->name
                : $model->commercial_name;

            $model->founded_at = $model->founded_at ?? null;
            $model->color = $model->color ?? color(6);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Has many relationship with locations
     */
    public function locations(): HasMany
    {
        return $this->hasMany(CompanyAddresses::class);
    }

    /**
     * Has many relationship with notes
     */
    public function notes(): HasMany
    {
        return $this->hasMany(InternalNotes::class);
    }

    /**
     * Has many relationship with addresses
     */
    public function headquarterLocation(): HasMany
    {
        return $this->hasMany(CompanyAddresses::class)->where('headquarters', true);
    }

    /**
     * Get the business sector that owns the company.
     */
    public function business_sector()
    {
        return $this->belongsTo(BusinessSector::class);
    }

    /**
     * Get all secondary business sectors
     */
    public function businessSectorSecondary()
    {
        return $this->belongsToMany(BusinessSector::class, 'companies_business_sectors');
    }

    /**
     * Get all business sectors » main + secondaries
     */
    public function businessSectorsAll()
    {
        return $this->businessSectorSecondary->push($this->business_sector)->unique('id');
    }

    /**
     * Get all business sectors » main + secondaries as an array
     */
    public function businessSectorsAllArray()
    {
        return $this->businessSectorsAll()->pluck('name', 'id')->toArray();
    }

    /**
     * Get all address as an array
     */
    public function companyAddressAllArray()
    {
        return $this->locations()->pluck('name', 'id')->toArray();
    }

    /**
     * Get all business sectors » main + secondaries as an array
     */
    public function businessSectorsAllIdsArray()
    {
        return $this->businessSectorsAll()->pluck('id', 'id')->toArray();
    }

    /**
     * Get the questionnaires for the company.
     */
    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
    }

    /**
     * Get the  for the company.
     */
    public function taxonomy()
    {
        return $this->hasMany(Taxonomy::class);
    }

    /**
     * Get the data for the company. // KPI's/monitoring
     */
    public function kpis()
    {
        return $this->hasMany(Data::class);
    }

    /**
     * Has many relationship with bank assets
     */
    public function bankAssets(): HasMany
    {
        return $this->hasMany(BankAssets::class);
    }

    /**
     * Has many relationship with bank assets simulation
     */
    public function bankSimulations(): HasMany
    {
        return $this->hasMany(BankSimulation::class)->orderBy('created_at', 'DESC');
    }

    /**
     * Get the business sector that owns the company.
     */
    public function sharingOptions()
    {
        return $this->belongsToMany(SharingOption::class, 'sharing_options_companies')
        ->select('sharing_options.*', 'sharing_options.name as sharing_option_name'); // Alias the name column
    }

    /**
     * Return list of assets of simulation or real
     */
    public function listAssets($simulation = null)
    {
        if ($simulation) {
            return $this->bankSimulations()->find($simulation)->assets;
        }
        return $this->bankAssets;
    }

    /**
     * Get the total of value of assets
     */
    public function bankAssetsTotal($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => 0,
            BankAssets::FLOW => 0
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::FLOW] += $asset['total'][BankAssets::FLOW];
            $assets[BankAssets::STOCK] += $asset['total'][BankAssets::STOCK];
        }
        return $assets;
    }

    /**
     * Get the total of eligible value of assets
     */
    public function bankAssetsEligible($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset['elegibilidade'][BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset['elegibilidade'][BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset['elegibilidade'][BankAssets::FLOW][BankAssets::VN];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset['elegibilidade'][BankAssets::STOCK][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of aligned mitigation value of assets
     */
    public function bankAssetsEligibleCCM($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->elegibilidadeCCM[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->elegibilidadeCCM[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->elegibilidadeCCM[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->elegibilidadeCCM[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of eligible adaptation value of assets
     */
    public function bankAssetsEligibleCCA($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->elegibilidadeCCA[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->elegibilidadeCCA[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->elegibilidadeCCA[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->elegibilidadeCCA[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of aligned value of assets for GAR and BTAR
     */
    public function bankAssetsAlignedByGARBTAR($simulation = null)
    {
        $assets = BankAssets::getBaseArray();
        foreach ($this->listAssets($simulation) as $asset) {
            foreach ($asset->alinhamento[BankAssets::STOCK][BankAssets::CAPEX][BankAssets::GAR] as $key => $value) {
                $assets[BankAssets::STOCK][BankAssets::CAPEX][BankAssets::GAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::STOCK][BankAssets::CAPEX][BankAssets::BTAR] as $key => $value) {
                $assets[BankAssets::STOCK][BankAssets::CAPEX][BankAssets::BTAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR] as $key => $value) {
                $assets[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::STOCK][BankAssets::VN][BankAssets::BTAR] as $key => $value) {
                $assets[BankAssets::STOCK][BankAssets::VN][BankAssets::BTAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::FLOW][BankAssets::CAPEX][BankAssets::GAR] as $key => $value) {
                $assets[BankAssets::FLOW][BankAssets::CAPEX][BankAssets::GAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::FLOW][BankAssets::CAPEX][BankAssets::BTAR] as $key => $value) {
                $assets[BankAssets::FLOW][BankAssets::CAPEX][BankAssets::BTAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::FLOW][BankAssets::VN][BankAssets::GAR] as $key => $value) {
                $assets[BankAssets::FLOW][BankAssets::VN][BankAssets::GAR][$key] += $value;
            }

            foreach ($asset->alinhamento[BankAssets::FLOW][BankAssets::VN][BankAssets::BTAR] as $key => $value) {
                $assets[BankAssets::FLOW][BankAssets::VN][BankAssets::BTAR][$key] += $value;
            }
        }
        return $assets;
    }

    /**
     * Get the total of aligned value of assets
     */
    public function bankAssetsAligned($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->alinhamentoTotal[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->alinhamentoTotal[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->alinhamentoTotal[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->alinhamentoTotal[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of aligned mitigation value of assets
     */
    public function bankAssetsAlignedCCM($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->alinhamentoCCM[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->alinhamentoCCM[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->alinhamentoCCM[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->alinhamentoCCM[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of aligned adaptation value of assets
     */
    public function bankAssetsAlignedCCA($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->alinhamentoCCA[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->alinhamentoCCA[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->alinhamentoCCA[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->alinhamentoCCA[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of transitional value of assets
     */
    public function bankAssetsTransitionalAdaptation($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset['adaptacao'][BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset['adaptacao'][BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset['adaptacao'][BankAssets::FLOW][BankAssets::VN];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset['adaptacao'][BankAssets::STOCK][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of transitional value of assets
     */
    public function bankAssetsTransitional($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->transicaoCCM[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->transicaoCCM[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->transicaoCCM[BankAssets::FLOW][BankAssets::VN];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->transicaoCCM[BankAssets::STOCK][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of adaptation value of assets
     */
    public function bankAssetsAdaptation($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->adaptacaoCCA[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->adaptacaoCCA[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->adaptacaoCCA[BankAssets::FLOW][BankAssets::VN];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->adaptacaoCCA[BankAssets::STOCK][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of enabling value of assets
     */
    public function bankAssetsEnabling($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset['capacitante'][BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset['capacitante'][BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset['capacitante'][BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset['capacitante'][BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of aligned value of assets
     */
    public function bankAssetsEnablingCCM($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->capacitanteCCM[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->capacitanteCCM[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->capacitanteCCM[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->capacitanteCCM[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Get the total of aligned value of assets
     */
    public function bankAssetsEnablingCCA($simulation = null)
    {
        $assets = [
            BankAssets::STOCK => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ],
            BankAssets::FLOW => [
                BankAssets::CAPEX => 0,
                BankAssets::VN => 0,
            ]
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            $assets[BankAssets::STOCK][BankAssets::CAPEX] += $asset->capacitanteCCA[BankAssets::STOCK][BankAssets::CAPEX];
            $assets[BankAssets::STOCK][BankAssets::VN] += $asset->capacitanteCCA[BankAssets::STOCK][BankAssets::VN];
            $assets[BankAssets::FLOW][BankAssets::CAPEX] += $asset->capacitanteCCA[BankAssets::FLOW][BankAssets::CAPEX];
            $assets[BankAssets::FLOW][BankAssets::VN] += $asset->capacitanteCCA[BankAssets::FLOW][BankAssets::VN];
        }
        return $assets;
    }

    /**
     * Ativo bancário
     */
    public function bankAssetsCalculations($simulation = null)
    {
        $result = [
            BankAssets::STOCK => 0,
            BankAssets::FLOW => 0,
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            if (in_array($asset->tipo, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16])) {
                $result[BankAssets::STOCK] += $asset[BankAssets::STOCK_FIELD];
                $result[BankAssets::FLOW] += $asset[BankAssets::FLOW_FIELD];
            }
        }
        return $result;
    }

    /**
     * Ativo excluído do numerador e denominador
     */
    public function assetsExcludedFromNumeratorAndDenominator($simulation = null)
    {
        $result = [
            BankAssets::STOCK => 0,
            BankAssets::FLOW => 0,
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            if (in_array($asset->tipo, [14, 15, 16])) {
                $result[BankAssets::STOCK] += $asset[BankAssets::STOCK_FIELD];
                $result[BankAssets::FLOW] += $asset[BankAssets::FLOW_FIELD];
            }
        }
        return $result;
    }

    /**
     * Ativo relevante para rácios
     */
    public function assetsRelevantForRatios($simulation = null)
    {
        $total = $this->bankAssetsCalculations($simulation);
        $excluded = $this->assetsExcludedFromNumeratorAndDenominator($simulation);
        return [
            BankAssets::STOCK => $total[BankAssets::STOCK] - $excluded[BankAssets::STOCK],
            BankAssets::FLOW => $total[BankAssets::FLOW] - $excluded[BankAssets::FLOW],
        ];
    }

    /**
     * Ativo excluído do numerador (GAR e BTAR)
     */
    public function assetsExcludedFromNumeratorGARBTAR($simulation = null)
    {
        $result = [
            BankAssets::STOCK => 0,
            BankAssets::FLOW => 0,
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            if (in_array($asset->tipo, [10, 11, 12, 13])) {
                $result[BankAssets::STOCK] += $asset[BankAssets::STOCK_FIELD];
                $result[BankAssets::FLOW] += $asset[BankAssets::FLOW_FIELD];
            }
        }
        return $result;
    }

    /**
     * Ativo Abrangido BTAR (relevância de numerador)
     */
    public function assetsCoverByBTAR($simulation = null)
    {

        $relevant = $this->assetsRelevantForRatios($simulation);
        $excludedNumerator = $this->assetsExcludedFromNumeratorGARBTAR($simulation);
        return [
            BankAssets::STOCK => $relevant[BankAssets::STOCK] - $excludedNumerator[BankAssets::STOCK],
            BankAssets::FLOW => $relevant[BankAssets::FLOW] - $excludedNumerator[BankAssets::FLOW],
        ];
    }

    /**
     * Ativo excluído do numerador GAR - Empresas
     */
    public function assetsExcludedFromNumeratorGAR($simulation = null)
    {
        $result = [
            BankAssets::STOCK => 0,
            BankAssets::FLOW => 0,
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            if (in_array($asset->tipo, [1, 2, 3]) && $asset->entidade_empresarial === 5 && strtoupper($asset->sujeição_nfdr) === "N") {
                $result[BankAssets::STOCK] += $asset[BankAssets::STOCK_FIELD];
                $result[BankAssets::FLOW] += $asset[BankAssets::FLOW_FIELD];
            }
        }
        return $result;
    }

    /**
     * Ativo Abrangido GAR (relevância de numerador)
     */
    public function assetsCoverByGAR($simulation = null)
    {

        $coverByBTAR = $this->assetsCoverByBTAR($simulation);
        $excludedNumerator = $this->assetsExcludedFromNumeratorGAR($simulation);
        return [
            BankAssets::STOCK => $coverByBTAR[BankAssets::STOCK] - $excludedNumerator[BankAssets::STOCK],
            BankAssets::FLOW => $coverByBTAR[BankAssets::FLOW] - $excludedNumerator[BankAssets::FLOW],
        ];
    }

    /**
     * Empresas não-financeiras não sujeitas a NFRD
     */
    public function companiesNotSubjectToNFRD($simulation = null)
    {
        $result = [
            BankAssets::STOCK => 0,
            BankAssets::FLOW => 0,
        ];
        foreach ($this->listAssets($simulation) as $asset) {
            if (in_array($asset->tipo, [1, 2, 3]) && $asset->entidade_empresarial === 5 && strtoupper($asset->sujeição_nfdr) === "N") {
                $result[BankAssets::STOCK] += $asset[BankAssets::STOCK_FIELD];
                $result[BankAssets::FLOW] += $asset[BankAssets::FLOW_FIELD];
            }
        }
        return $result;
    }

    /**
     * Denominador (GAR)
     */
    public function denominatorGAR($simulation = null)
    {
        $coverByGAR = $this->assetsCoverByGAR($simulation);
        $companiesNotNFRD = $this->companiesNotSubjectToNFRD($simulation);
        $excludedGARBTAR = $this->assetsExcludedFromNumeratorGARBTAR($simulation);
        return [
            BankAssets::STOCK => $coverByGAR[BankAssets::STOCK] + $companiesNotNFRD[BankAssets::STOCK] + $excludedGARBTAR[BankAssets::STOCK],
            BankAssets::FLOW => $coverByGAR[BankAssets::FLOW] + $companiesNotNFRD[BankAssets::FLOW] + $excludedGARBTAR[BankAssets::FLOW],
        ];
    }

    /**
     * Denominador (BTAR)
     */
    public function denominatorBTAR($simulation = null)
    {
        return $this->denominatorGAR($simulation);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Interact with the company's type.
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Type::from($value) : null
        );
    }

    /**
     * Interact with the company's type.
     */
    protected function relation(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_array($value) ? implode(',', $value) : $value,
        );
    }

    /**
     * Interact with the company's type.
     */
    protected function relations(): Attribute
    {
        return Attribute::make(
            get: function () {
                $values = explode(',', $this->relation);
                $values = array_filter($values); // Remove null values (they are converted into empty strings)
                $values = collect($values);

                return $values->map(fn ($relation) => Relation::from($relation));
            },
        );
    }

    /**
     * Determine if is an internal company
     */
    public function isInternal(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type->isInternal(),
        );
    }

    /**
     * Determine if is not an internal company
     */
    public function isNotInternal(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type->isNotInternal(),
        );
    }

    /**
     * Determine if is an external company
     */
    public function isExternal(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type->isExternal(),
        );
    }

    /**
     * Determine if is not an external company
     */
    public function isNotExternal(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type->isNotExternal(),
        );
    }

    /**
     * Check if a company is a client
     */
    public function isClient(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->relation && in_array(Relation::CLIENT, $this->relation ?? [], true),
        );
    }

    /**
     * Check if a company is not a client
     */
    public function isNotClient(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !$this->is_client,
        );
    }

    /**
     * Check if a company is a supplier
     */
    public function isSupplier(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->relation && in_array(Relation::SUPPLIER, $this->relation ?? [], true),
        );
    }

    /**
     * Check if a company is not a supplier
     */
    public function isNotSupplier(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !$this->is_supplier,
        );
    }

    /**
     * Is the company an issuer of securities?
     */
    public function dynIsIssuerOfSecurities(): Attribute
    {
        // Indicator id = 6778
        return Attribute::make(
            get: fn ($value, $attributes) => ($this->kpis()->where('indicator_id', 6778)->latest()->first()->value
                ??
                false
            ) === 'yes' ? true : false,
        );
    }

    /**
     * Total number of employees (contracted and subcontracted)
     */
    public function dynTotalNumberOfEmployees(): Attribute
    {
        // Indicator id = 6778
        return Attribute::make(
            get: fn ($value, $attributes) => $this->kpis()->where('indicator_id', 513)->latest()->first()->value
                ??
                0,
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
            ->with('business_sector');
    }

    /**
     * Get a list if NIF for filter
     *
     * @return Collection
     */
    public static function nifList($type = 'internal')
    {
        return self::list()->where('type', $type)
            ->whereNotNull('vat_number')->get()->unique('vat_number');
    }

    /**
     * Filer company data
     */
    public static function dataForCompnay($search, $type = 'internal')
    {
        extract($search);

        $query = self::list(auth()->user())
            ->where('type', $type);

        if (!empty($businessSectors)) {
            $query->whereIn('business_sector_id', $businessSectors);
        }

        if (!empty($countries)) {
            $query->whereIn('country', $countries);
        }

        if (!empty($nif)) {
            $query->whereIn('vat_number', $nif);
        }

        if (!empty($text)) {
            $query->where('name', 'like', $text . '%');
        }

        return $query;
    }

    /**
     * Create the assigned user message
     */
    public function assignedUserMessage($assigner)
    {
        return [
            'userName' => $assigner->name,
            'company' => $this->name,
            'message' => __(':userName assigned you the company ":company".'),
        ];
    }

    /**
     * Create the updated user message
     */
    public function updatedUserMessage()
    {
        return [
            'company' => $this->name,
            'message' => __('The company ":company" has been updated.'),
        ];
    }

    /**
     * Get the default value for the FLow column based in the tenant config
     */
    public static function defaultFlowStructure()
    {
        $flow = tenant()->hasCreatingfeature['flow'] ?? null;
        if ($flow) {
            foreach ($flow as $key => $step) {
                if (!isset($step['type'])) {
                    return [];
                }
            }

            return [
                'steps' => $flow,
                'total' => count($flow),
                'current' => '0',
                'done' => false
            ];
        }
        return [];
    }

    /**
     * Get the total of aligned value of assets
     */
    public function sizeLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->size
                    ? CompanySize::fromName($this->size)
                    : '-';
            },
        );
    }


    /**
     * Get the  for the company.
     */
    public function reportData()
    {
        return $this->hasMany(Data::class);
    }

    public function scopeIsInternalCompany($query)
    {
        return $query->where('type', Type::INTERNAL);
    }

    public function scopeIsExternalCompany($query)
    {
        return $query->where('type', Type::EXTERNAL);
    }

    public function scopeIsClientCompany($query)
    {
        return $query->where('relation', 'like', "%" . strtolower(Relation::CLIENT->value) . "%");
    }

    public function scopeIsSupplierCompany($query)
    {
        return $query->where('relation', 'like', "%" . strtolower(Relation::SUPPLIER->value) . "%");
    }
}
