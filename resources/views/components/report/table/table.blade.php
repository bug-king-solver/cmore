<div class="text-esg8 px-4 lg:px-0 print:px-0">
    <div class="">
        @if(!isset($bgnone))
            <div class="mt-5 h-4 bg-esg7/20 {{ $margin ?? '' }}"></div>
        @endif
        <table {{ $attributes->merge(['class' => 'table md:w-full border-t border-t-esg5 ']) }}>
            @if (isset($thead))
                <thead>
                    <x-tables.thead.tr>
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
