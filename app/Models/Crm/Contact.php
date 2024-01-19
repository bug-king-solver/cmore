<?php

namespace App\Models\Crm;

use App\Services\HubSpot\HubSpot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Contact extends Model
{
    use HasDataColumn;
    use SoftDeletes;

    protected $connection = 'central';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crm_contacts';

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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($contact) {
            if (!$contact->id_hubspot) {
                $contactInput = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput();

                $contactInput->setProperties([
                    'firstname' => $contact->firstname,
                    'lastname' => $contact->lastname,
                    'email' => $contact->email,
                    'work_email' => $contact->work_email,
                    'phone' => $contact->phone,
                ]);

                $hubSpot = new HubSpot(HubSpot::CONTACTS, config('app.hubspot_key'));
                $hubSpot->setUpConnection();
                $contactHubSpot = $hubSpot->createObject($contactInput);
                $contact->id_hubspot = $contactHubSpot->getId();
                $contact->enabled = true;
                $contact->forceFill($contactHubSpot->getProperties());
            }
        });
    }

    /**
     * A contact can be attached to multiple companies
     */
    public function companies()
    {
        return $this->morphToMany(Company::class, 'relatable', 'crm_companies_relations');
    }

    public static function getAssociationsHubSpot(): array
    {
        return [
            'companies',
            'deals',
        ];
    }

    public static function CreateOrUpdateFromHubSpot(array $results)
    {
        foreach ($results as $item) {
            $id = $item->getId();

            $contact = Contact::firstOrNew(['id_hubspot' => $id]);
            $contact->forceFill($item->getProperties());
            $contact->enabled = !$item['archived'];
            $contact->save();

            $contact->companies()->detach();
            if (isset($item['associations']['companies'])) {
                foreach ($item['associations']['companies']->getResults() as $association) {
                    if ($company = Company::where('id_hubspot', '=', $association['id'])->first()) {
                        if ($contact->companies()->where('company_id', '=', $company->id)->count() == 0) {
                            $contact->companies()->save($company);
                        }
                    }
                }
            }

        }
    }

}
