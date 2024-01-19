<?php

namespace App\Nova\Central\Actions\Payments;

use App\Services\Unicre\Unicre;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cancel extends Action
{

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $unicre = new Unicre();
        foreach ($models as $item) {
            if ($item->status === Unicre::STATUS_PAYMENT[1]) {
                $unicre->cancelPaymentById($item->id);
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
