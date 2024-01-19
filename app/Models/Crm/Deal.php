<?php

namespace App\Models\Crm;

use App\Models\Payment;
use App\Models\Tenant;
use App\Services\HubSpot\HubSpot;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Deal extends Model
{
    use HasDataColumn;
    use SoftDeletes;

    protected $connection = 'central';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_deals';

    protected $fillable = [
        'tenant_id',
        'company_id',
        'id_hubspot',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'tenant_id',
            'company_id',
            'id_hubspot',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($deal) {
            if (!$deal->id_hubspot) {
                $dealInput = new \HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInputForCreate();

                $dealInput->setProperties([
                    'description' => $deal->description,
                    'amount' => $deal->amount,
                    'dealname' => $deal->dealname,
                ]);

                $dealInput->setAssociations([
                    new \HubSpot\Client\Crm\Deals\Model\PublicAssociationsForObject([
                        'to' => new \HubSpot\Client\Crm\Deals\Model\PublicObjectId(['id' => $deal->company->id_hubspot]),
                        'types' => [
                            new \HubSpot\Client\Crm\Deals\Model\AssociationSpec([
                                'association_type_id' => 5,
                                'association_category' => 'HUBSPOT_DEFINED',
                            ]),
                        ]
                    ]),
                ]);

                $hubSpot = new HubSpot(HubSpot::DEALS, config('app.hubspot_key'));
                $hubSpot->setUpConnection();
                $dealHubSpot = $hubSpot->createObject($dealInput);
                $deal->id_hubspot = $dealHubSpot->getId();
                $deal->enabled = true;
                $deal->forceFill($dealHubSpot->getProperties());
            }
        });
    }

    /**
     *
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['dealname'],
        );
    }

    /**
     *
     */
    public function firstName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $name = explode(' ', $attributes['dealname'] ?? '');
                return $name[0] ?? '';
            },
        );
    }

    /**
     *
     */
    public function lastName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $name = explode(' ', $attributes['dealname'] ?? '');
                return $name[count($name) - 1] ?? '';
            },
        );
    }

    /**
     * A deal belongs to a tenant or not
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
        return $this->morphToMany(Tenant::class, 'relatable', 'tenants_relations');
    }

    /**
     * Get all the companies for the deal
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
        return $this->morphToMany(Company::class, 'relatable', 'crm_companies_relations');
    }

    /**
     * Get all the invoicing documents for the deal
     */
    public function invoicing_documents()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all the payments for the deal
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public static function getAssociationsHubSpot(): array
    {
        return [
            'companies',
            'contacts',
        ];
    }

    public static function CreateOrUpdateFromHubSpot(array $results) {
        foreach ($results as $item) {
            $id = $item->getId();

            $deal = Deal::firstOrNew(['id_hubspot' => $id]);
            $deal->forceFill($item->getProperties());
            $deal->enabled = !$item['archived'];

            if (isset($item['associations']['companies'])) {
                $association = $item['associations']['companies']->getResults()[0];
                if ($company = Company::where('id_hubspot', '=', $association['id'])->first()) {
                    $deal->company_id = $company->id;
                }
            } else {
                $deal->company_id = null;
            }
            $deal->save();
        }
    }

}
