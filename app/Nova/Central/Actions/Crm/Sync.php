<?php

namespace App\Nova\Central\Actions\Crm;

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Deal;
use App\Services\HubSpot\HubSpot;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Sync extends Action
{

    private $hubSpot;

    public function __construct($model) {
        $this->hubSpot = new HubSpot($model);
        return $this;
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $className = null;
        if ($this->hubSpot->model === HubSpot::COMPANIES) {
            $className = Company::class;
        } else if ($this->hubSpot->model === HubSpot::DEALS) {
            $className = Deal::class;
        } else if ($this->hubSpot->model === HubSpot::CONTACTS) {
            $className = Contact::class;
        }

        if (isset($className)) {
            $this->hubSpot->setUpConnection();
            $this->hubSpot->associations = $className::getAssociationsHubSpot();
            foreach ($models as $item) {
                $className::CreateOrUpdateFromHubSpot($this->hubSpot->getDatabyObjectId($item->id_hubspot));
            }
        }
        return $models;
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request = null)
    {
        return [];
    }
}