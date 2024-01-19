@props([
    'class' => 'border-b-esg5 table border-b-[1px] min-w-[800px] md:w-full',
])

<div class="text-esg8 px-4 lg:px-0">
    <div class="overflow-x-auto lg:overflow-visible">
        <table {{ $attributes->merge(['class' => $class]) }}>
            @if (isset($thead))
                <thead>
                    <x-tables.tr class='border-b-1'>
                        {{ $thead }}
                    </x-tables.tr>
                </thead>
            @endif

            <tbody>
                {{ $slot }}
            </tbody>

            @if (isset($tfoot))
                <tfoot>
                    <x-tables.tr>
                        {{ $tfoot }}
                    </x-tables.tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
