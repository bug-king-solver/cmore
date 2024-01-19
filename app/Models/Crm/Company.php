<?php

namespace App\Models\Crm;

use App\Services\HubSpot\HubSpot;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;
use Symfony\Component\Intl\Countries;

class Company extends Model
{
    use HasDataColumn;
    use SoftDeletes;

    protected $connection = 'central';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_companies';

    protected $casts = [
        //'data' => 'json',
    ];

    protected $fillable = [
        'id_hubspot',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'id_hubspot',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     *
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['name'] ?? '',
        );
    }

    /**
     *
     */
    public function addressLine1(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['address'],
        );
    }

    /**
     *
     */
    public function addressState(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['state'],
        );
    }

    /**
     *
     */
    public function addressZip(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['zip'],
        );
    }

    /**
     *
     */
    public function addressCity(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['city'],
        );
    }

    /**
     *
     */
    public function addressLine2(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['address2'],
        );
    }

    /**
     *
     */
    public function addressCountry(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => array_search($attributes['country'], Countries::getNames()),
        );
    }

    /**
     *
     */
    public function vatCountryAlpha2(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => array_search($attributes['tax_id_country'], Countries::getNames()),
        );
    }

    /**
     *
     */
    public function vatCountryAlpha3(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => array_search($attributes['tax_id_country'], Countries::getAlpha3Names()),
        );
    }

    /**
     *
     */
    public function vatNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['tax_id_number'] ?? '',
        );
    }

    /**
     *
     */
    public function financialContactFullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['invoicing_contact'] ?? '',
        );
    }

    /**
     *
     */
    public function financialContactFirstName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $name = explode(' ', $attributes['invoicing_contact'] ?? '');
                return $name[0] ?? '';
            },
        );
    }

    /**
     *
     */
    public function financialContactLastName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $name = explode(' ', $attributes['invoicing_contact'] ?? '');
                return $name[count($name) - 1] ?? '';
            },
        );
    }

    /**
     *
     */
    public function financialContactEmail(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['invoicing_e_mail'] ?? '',
        );
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (!$company->id_hubspot) {
                $companyInput = new \HubSpot\Client\Crm\Companies\Model\SimplePublicObjectInput();

                $companyInput->setProperties([
                    'name' => $company->name,
                    'description' => $company->description,
                    'about_us' => $company->about_us,
                    'phone' => $company->phone,
                ]);

                $hubSpot = new HubSpot(HubSpot::COMPANIES, config('app.hubspot_key'));
                $hubSpot->setUpConnection();
                $companyHubSpot = $hubSpot->createObject($companyInput);
                $company->id_hubspot = $companyHubSpot->getId();
                $company->enabled = true;
                $company->forceFill($companyHubSpot->getProperties());
            }
        });
    }

    /**
     * A company can have multiple contacts
     */
    public function contacts()
    {
        return $this->morphedByMany(Contact::class, 'relatable', 'crm_companies_relations', 'company_id', 'relatable_id');
    }

    /**
     * A company can have multiple deals
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public static function getAssociationsHubSpot(): array
    {
        return [
            'contacts',
            'deals',
        ];
    }

    public static function CreateOrUpdateFromHubSpot(array $results) {
        foreach ($results as $item) {
            $id = $item->getId();

            $company = Company::firstOrNew(['id_hubspot' => $id]);
            $company->forceFill($item->getProperties());
            $company->enabled = !$item['archived'];
            $company->save();

            $company->contacts()->detach();
            if (isset($item['associations']['contacts'])) {
                foreach ($item['associations']['contacts']->getResults() as $association) {
                    if ($contact = Contact::where('id_hubspot', '=', $association['id'])->first()) {
                        if ($company->contacts()->where('relatable_id', '=', $contact->id)->count() == 0) {
                            $company->contacts()->save($contact);
                        }
                    }
                }
            }

            if (isset($item['associations']['deals'])) {
                foreach ($item['associations']['deals']->getResults() as $association) {
                    if ($deal = Deal::where('id_hubspot', '=', $association['id'])->first()) {
                        $company->deals()->save($deal);
                    }
                }
            }
        }
    }

}
