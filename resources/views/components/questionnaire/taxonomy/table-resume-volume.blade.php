@props([
    'title' => null,
    'bold' => false,
    'data' => [
        'volume' => [
            'value' => 0,
            'percentage' => 0
        ],
        'capex' => [
            'value' => 0,
            'percentage' => 0
        ],
        'opex' => [
            'value' => 0,
            'percentage' => 0
        ],
    ],
    'color' => 'esg5',
])

<x-tables.td class="!border-b-0 {{ $bold ? 'font-extrabold' : 'font-normal' }} text-sm  !p-2">
    {{ $title }}
</x-tables.td>

<x-tables.td class="!border-b-0 text-center {{ $bold ? 'font-extrabold' : 'font-normal' }} text-sm !p-2">
    <div class="flex">
        <p class="text-esg8">
            <x-currency :value="$data['volume']['value']" currency='€' />
        </p>
        <p class="text-{{ $color }} font-extrabold ml-1"> (
            {{ $data['volume']['percentage'] }}% )
        </p>
    </div>
</x-tables.td>
<x-tables.td class="!border-b-0 text-center {{ $bold ? 'font-extrabold' : 'font-normal' }} text-sm !p-2">
    <div class="flex">
        <p class="text-esg8">
            <x-currency :value="$data['capex']['value']" currency='€' />
        </p>
        <p class="text-{{ $color }} font-extrabold ml-1"> (
            {{ $data['capex']['percentage'] }}% )
        </p>
    </div>
</x-tables.td>
<x-tables.td class="!border-b-0 text-center {{ $bold ? 'font-extrabold' : 'font-normal' }} text-sm !p-2">
    <div class="flex">
        <p class="text-esg8">
            <x-currency :value="$data['opex']['value']" currency='€' />
        </p>
        <p class="text-{{ $color }} font-extrabold ml-1"> (
            {{ $data['opex']['percentage'] }}% )
        </p>
    </div>
</x-tables.td>
