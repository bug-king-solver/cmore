<?php

namespace App\Nova\Central\Actions;

use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Ready extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $model->ready = $fields->ready;
            $model->ready_at = $fields->ready_at;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Ready', 'ready')
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->rules('required'),

            DependencyContainer::make([
                DateTime::make('Ready At', 'ready_at'),
            ])->dependsOn('ready', 1),
        ];
    }
}
