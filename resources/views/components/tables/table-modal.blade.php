<div class="w-full text-esg29">
    <table {{ $attributes->merge(['class' => 'border-b-esg5 table border-b-[1px] w-full']) }}>
        @if (isset($thead))
            <thead>
                <x-tables.tr>
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
