<?php

namespace App\Nova\Central\Actions\Tenant\DocuSign;

use App\Services\DocuSign\RequestSignService;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class RequestSign extends Action
{

    public $name = 'Send Document to Sign';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $docusign = new RequestSignService();
        foreach ($models as $model) {
            try {
                $docusign->sendFileToSign($model->email, $model->name, $fields->document);
                return Action::message("File sended to email: $model->email");
            } catch (\Throwable $e) {
                return Action::danger("File not sended");
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
        return [
            File::make('Document')->acceptedTypes('.pdf,.docx,.doc'),
        ];
    }
}
