<?php

namespace App\Nova\Central;

use App\Nova\Central\Actions\Transactions\Confirm;
use App\Nova\Central\Tenant;
use App\Nova\CustomResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transaction extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'transaction';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'type', 'meta->description',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('UUID')->exceptOnForms(),

            MorphTo::make('Payable', 'parent')
                ->types([Tenant::class])
                ->exceptOnForms(),

            Select::make('Type')->sortable()->options([
                $this::$model::TYPE_DEPOSIT => 'Deposit',
                $this::$model::TYPE_WITHDRAW => 'Withdraw',
            ])->displayUsingLabels()
                ->readonly(),

            Currency::make('Amount', 'amountFloat')
                ->rules('required')
                ->currency('EUR')
                ->exceptOnForms()
                ->sortable(),

            Text::make('Description', 'meta->description')->onlyOnIndex(),

            Boolean::make('Confirmed?', 'confirmed')->exceptOnForms(),


            Text::make('Title', 'meta->title')
                ->resolveUsing(fn () => $this->meta['title'] ?? '')
                ->hideFromIndex(),

            Text::make('Description', 'meta->description')
                ->resolveUsing(fn () => $this->meta['description'] ?? '')
                ->hideFromIndex(),

            Text::make('Date', 'created_at')->exceptOnForms(),

            Code::make('Meta')
                ->readonly()
                ->rules('required')
                ->json(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {

        $actions = [];

        if (!$this->confirmed) {
            $actions[] = (new Confirm())->showOnTableRow();
        }

        return $actions;
    }


    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return true;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
