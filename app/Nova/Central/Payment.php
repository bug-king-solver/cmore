<?php

namespace App\Nova\Central;

use App\Nova\Central\Actions\Payments\Cancel;
use App\Nova\Central\Actions\Payments\Update;
use App\Nova\Central\Crm\Deal;
use App\Nova\Central\Invoicing\Document;
use App\Nova\CustomResource;
use App\Services\Unicre\Unicre;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Symfony\Component\Intl\Countries;

class Payment extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Payment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'token';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'token'
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

            BelongsTo::make('Tenant', 'tenant', Tenant::class),

            BelongsTo::make('Deal', 'deal', Deal::class),

            BelongsTo::make('Invoicing Document', 'invoicing_document', Document::class),

            Text::make('Token'),

            Text::make('Status'),

            URL::make('Url Payment', 'url'),

            Code::make('Payment Data')->json()->rules('json', 'nullable'),

            Code::make('Response')->json()->rules('json', 'nullable'),
        ];
    }

    /**
     * Get the fields displayed by the resource on detail page.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fieldsForCreate(NovaRequest $request)
    {
        $parent = $request->viaResource && $request->viaResourceId ? $request->findParentResource() : null;

        if (is_a($parent, Document::class)) {
            $document = $parent;
            $tenant = $document->tenant ?? null;
            $deal = $document->deal ?? null;
            $company = $deal->company ?? null;
        } elseif (is_a($parent, Deal::class)) {
            $deal = $parent;
            $company = $deal->company ?? null;
            $tenant = $deal->tenant ?? null;
        }

        return [
            new Panel('Relationships', [
                BelongsTo::make('Tenant', 'tenant', Tenant::class)
                    ->default($tenant->id ?? null)
                    ->withoutTrashed()
                    ->required(false),

                BelongsTo::make('Deal', 'deal', Deal::class)
                    ->default($deal->id ?? null)
                    ->withoutTrashed()
                    ->required(false),

                BelongsTo::make('Transaction', 'transaction', Transaction::class)
                    ->default(null)
                    ->withoutTrashed()
                    ->required(false),
                
                BelongsTo::make('Invoicing Document', 'invoicing_document', Document::class)
                    ->default($document->id ?? null)
                    ->withoutTrashed(),
            ]),

            new Panel('Contact', [
                Text::make('First Name', 'payment_data->firstName')->rules('required')
                    ->default($company->financial_contact_first_name ?? ''),

                Text::make('Last Name', 'payment_data->lastName')->rules('required')
                    ->default($company->financial_contact_last_name?? ''),

                Email::make('Email', 'payment_data->email')->rules('required')
                    ->default($company->financial_contact_email ?? ''),
            ]),

            new Panel('Address', [
                Text::make('Address', 'payment_data->addressLine1')->rules('required')
                    ->default($company->address_line1 ?? ''),

                Text::make('Zip Code', 'payment_data->zipCode')->rules('required')
                    ->default($company->address_zip ?? ''),

                Text::make('City', 'payment_data->locality')->rules('required')
                    ->default($company->address_city ?? ''),

                Country::make('Country', 'payment_data->country')->rules('required')
                    ->default($company->address_country ?? null),
            ]),

            new Panel('Payment', [
                Number::make('Tax Payer Number', 'payment_data->taxpayerNumber')->rules('required')
                    ->default($company->vat_number ?? ''),

                Text::make('Description', 'payment_data->descriptive')->rules('required'),

                Text::make('Reference Number', 'payment_data->referenceNumber')->rules('required'),

                Currency::make('Value', 'payment_data->amount')->rules('required'),

                Date::make('Dead Line', 'payment_data->paymentDeadline')->rules('required')
                    ->default(now()->addDays(7)->format('Y-m-d')),

                Select::make('Language', 'payment_data->language')->rules('required')->options([
                    'pt' => 'Portuguese',
                    'en' => 'English',
                    'es' => 'Spanish',
                    'fr' => 'French',
                    'de' => 'German',
                ])
                ->default('pt'),

                Hidden::make('Send link to Email', 'payment_data->sendLinkByEmail')->default(1),
            ])
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
        return [
            (new Cancel())->showInline()->canSee(function ($request) {
                return $request instanceof ActionRequest
                    || ($this->resource->exists && $this->resource->status === Unicre::STATUS_PAYMENT[1])
                    || sizeof($request->resources ?? []) > 1;
            }),
            (new Update())->showInline()
        ];
    }

    public static function availableForNavigation(Request $request)
    {
        return auth()->user()->is_reporter != '1';
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

}
