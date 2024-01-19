<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProductItem extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Code', 'Code')->rules('required', 'min:2')->default('PSER001'),

            Text::make('Description', 'Description')->rules('required', 'min:2')->default('Prestação de Serviços'),

            Currency::make('Unit Price', 'UnitPrice')->rules('required'),

            Number::make('Quantity', 'Quantity')->rules('required')->min(1)->default(1),

            Text::make('Unit', 'Unit')->rules('required')->default('UN'),

            Select::make('Type', 'Type')->rules('required')->options([
                'S' => 'Service',
                'P' => 'Product',
            ])->default('P'),

            Number::make('Tax Value', 'TaxValue')->rules('required')->min(0)->default(23),

            Currency::make('Product Discount', 'ProductDiscount')->rules('required')->min(0)->default(0),
        ];
    }

}
