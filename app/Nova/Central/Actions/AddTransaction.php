<?php

namespace App\Nova\Central\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AddTransaction extends Action
{
    use InteractsWithQueue;
    use Queueable;

    public static $model = \App\Models\Transaction::class;


    /**
     * Action constructor.
     * @param string $action
     * @param string|null $seederName
     */
    public function __construct()
    {
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
        $user = auth()->user()->getModel();

        $meta = [
            'title' => $fields->title,
            'description' => $fields->description,
            'user_type' => $user->getMorphClass(),
            'user_id' => $user->getKey(),
        ];


        /** @var User $model */
        foreach ($models as $model) {
            if ($fields->type === $this::$model::TYPE_DEPOSIT) {
                $model = $model->depositFloat(
                    $fields->amount,
                    $meta,
                    $fields->confirmed || $fields->type === $this::$model::TYPE_WITHDRAW
                );

            } elseif ($fields->type === $this::$model::TYPE_WITHDRAW) {
                $model = $model->forceWithdrawFloat($fields->amount, $meta, $confirmed = true);
            }
        }

        return Action::message(__('Balance updated successfully!'));
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Type')->sortable()->options([
                $this::$model::TYPE_DEPOSIT => 'Deposit',
                $this::$model::TYPE_WITHDRAW => 'Withdraw',
            ])->displayUsingLabels(),
            Text::make(__('Title'))->rules('required', 'max:50'),
            Text::make(__('Description'))->rules('required', 'max:50'),
            Currency::make(__('Amount'))->rules('required', 'gt:0'),
            Boolean::make('Confirmed?', 'confirmed')->exceptOnForms()
                ->help("If the type is a withdrawal, the transaction will be marked as confirmed automatically."),
        ];
    }
}
