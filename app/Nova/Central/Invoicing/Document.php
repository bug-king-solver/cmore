<?php

namespace App\Nova\Central\Invoicing;

use App\Nova\Central\Actions\Invoicing\UpdateDocument;
use App\Nova\Central\Crm\Company;
use App\Nova\Central\Crm\Deal;
use App\Nova\Central\Payment;
use App\Nova\Central\Tenant;
use App\Nova\CustomResource;
use App\Nova\Repeater\ProductItem;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Symfony\Component\Intl\Countries;

class Document extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoicing\Document::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'document_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'document_id'
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

            Text::make('Document Id'),

            BelongsTo::make('Tenant', 'tenant', Tenant::class),

            BelongsTo::make('Deal', 'deal', Deal::class),

            HasMany::make('Payments', 'payments', Payment::class),

            Select::make('Status')->options([
                "C" => "Close",
                "D" => "Draft",
                "A" => "Canceled",
            ])->displayUsingLabels(),

            Code::make('Body Request')->json()->rules('json', 'nullable'),

            Code::make('Response')->json()->rules('json', 'nullable'),

            Code::make('Data')->json()->rules('json', 'nullable'),
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

        if (is_a($parent, Deal::class)) {
            $deal = $parent;
            $tenant = $deal->tenant;
            $company = $deal->company;
        } elseif (is_a($parent, Company::class)) {
            $company = $parent;
        } elseif (is_a($parent, Tenant::class)) {
            $tenant = $parent;
            $company = $tenant->company;
        }

        return [
            new Panel('Relationships', [
                BelongsTo::make('Tenant', 'tenant', Tenant::class)
                    ->default($tenant->id ?? null)
                    ->withoutTrashed()
                    ->nullable()
                    ->required(false),

                BelongsTo::make('Deal', 'deal', Deal::class)
                    ->default($deal->id ?? null)
                    ->withoutTrashed()
                    ->nullable()
                    ->required(false),
            ]),

            new Panel('Contact', [
                Email::make('Email', 'body_request->SendTo')->rules('required')
                ->default($company->financial_contact_email ?? ''),

                Text::make('Full Name', 'body_request->Client->Name')->rules('required')
                    ->default($company->financial_contact_full_name ?? ''),
            ]),

            new Panel('VAT', [
                Country::make('VAT Country', 'body_request->Client->CountryCode')->rules('required')
                    ->default($company->vat_country_alpha2 ?? ''),

                Text::make('VAT Number', 'body_request->Client->NIF')->rules('required')
                    ->default($company->vat_number ?? ''),
            ]),

            new Panel('Invoice', [
                Select::make('Type', 'body_request->Document->Type')->rules('required')->options([
                    'T' => 'Fatura/Recibo',
                    'I' => 'Fatura',
                    'S' => 'Fatura Simplificada',
                    'C' => 'Credit note',
                    'D' => 'Debit Note',
                ])->default('I'),

                Date::make('Date', 'body_request->Document->Date')
                    ->rules('required')
                    ->default(now()->format('Y-m-d')),

                Date::make('Expired On', 'body_request->Document->DueDate')
                    ->rules('required')
                    ->default(now()->addDays(30)->format('Y-m-d')),

                Boolean::make('Is To Close', 'body_request->IsToClose'),
            ]),

            new Panel('Invoice Lines', [
                Repeater::make('Products', 'body_request->Document->Lines', '')->repeatables([
                    ProductItem::make(),
                ])->asJson()->rules('required'),
            ]),
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
            (new UpdateDocument())->showInline(),
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
