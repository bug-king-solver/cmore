<div class="flex items-center gap-{{ $gap ?? 5 }}">
    @foreach ($tablist as $tab)
        @php
            $class = '';
            $tabStatus = 0;
            if (($activetab == null && isset($tab['active'])) || $tab['slug'] == $activetab) {
                $class = 'bg-esg5/10 border border-esg5 text-esg5 !border-esg5';
                $tabStatus = 1;
            } else {
                $class = 'text-esg7 hover:border hover:border-esg7';
                $tabStatus = 0;
                if (($activetab == null && isset($tab['active'])) || $tab['slug'] == $activetab) {
                    $class = 'bg-esg5/10 border border-esg5 text-esg5 !border-esg5';
                    $tabStatus = 1;
                } else {
                    $class = ' border border-esg7 text-esg8 hover:border hover:border-esg7';
                    $tabStatus = 0;
                }
            }
        @endphp

        <button class="flex items-center p-2 rounded-md gap-2 {{ $class ?? '' }}"
            wire:click="activateTab('{{ $tab['slug'] }}')" wire:key='tab-{{ $tab['slug'] }}'>

            @if (isset($tab['icon']))
                @include("icons.{$tab['icon']}", [
                    'width' => '21.8px',
                    'height' => '20px',
                    'color' => $tabStatus ? color(5) : color(8),
                ])
            @endif
            <span class="font-weight-bold">
                {{ $tab['label'] }}
            </span>
        </button>
    @endforeach
</div>
