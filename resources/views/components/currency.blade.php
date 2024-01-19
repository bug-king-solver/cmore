@props([
    'value' => null,
    'withoutSymbol' => false,
    'currency' => null,
])
{{ formatToCurrency($value, $withoutSymbol, $currency) }}
