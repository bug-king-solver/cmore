@props([
    'header' => [],
    'resource' => [],
    'body' => [],
    'buttons' => [],
    'buttonHeader' => '',
    'links' => false,
    'defaultNoRecords' => __('No records found'),
])

<div>
    <x-tables.white.table>
        <x-slot name="thead" class="!border-0 !bg-esg4">
            @foreach ($header as $item)
                <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ $item }}</x-tables.white.th>
            @endforeach
            @if (count($buttons) > 0)
                <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">
                    {{ $buttonHeader ? __($buttonHeader) : '' }}
                </x-tables.white.th>
            @endif
        </x-slot>

        @forelse ($resource as $res)
            @php $loopEncoded = json_encode($loop); @endphp
            <x-tables.white.tr class="border-b border-b-esg8/20">
                @foreach ($body as $item)
                    <x-tables.white.td class="!px-0 text-sm" loop="{!! $loopEncoded !!}">
                        @php
                            $data = extractTextAndValueFromString($item, $res);
                            $text = $data['text'];
                            $value = $data['value'];
                            if ($value == '') {
                                $value = $res->$item;
                            }
                        @endphp
                        @if ($text != '')
                            {!! $text !!} -
                        @endif
                        {!! $value !!}
                    </x-tables.white.td>
                @endforeach
                @if ($buttons and count($buttons) > 0)
                    <x-tables.white.td class="!px-0 text-sm" loop="{!! $loopEncoded !!}">
                        <div class="flex w-full items-center md:space-x-4 pl-3 md:pl-0">
                            @foreach ($buttons as $button)
                                @can($button['permission'])
                                    @php
                                        $params = [$button['params'] => $res->id];
                                        $hasRoute = $button['route'] ?? false;
                                        $hasModal = $button['modal'] ?? false;
                                        if ($hasModal) {
                                            $params = json_encode($params);
                                        }
                                    @endphp

                                    @if ($hasRoute)
                                        <x-buttons.a-icon href="{{ route($button['route'], $params) }}"
                                            title="{{ __($button['title'] ?? 'Edit') }}">
                                            @include($button['icon'] ?? 'icons.edit')
                                        </x-buttons.a-icon>
                                    @elseif ($hasModal)
                                        <x-buttons.a-icon title="{{ __($button['title'] ?? 'Edit') }}"
                                            :modal="$button['modal']" :data="$params">
                                            @include($button['icon'] ?? 'icons.edit')
                                        </x-buttons.a-icon>
                                    @endif
                                @endcan
                            @endforeach
                        </div>
                    </x-tables.white.td>
                @endif
            </x-tables.white.tr>
        @empty
            <x-tables.white.tr class="border-b border-b-esg8/20">
                <x-tables.td colspan="{{ count($header) }}" class="text-center">
                    <span class="text-gray-500">{{ $defaultNoRecords }}</span>
                </x-tables.td>
            </x-tables.white.tr>
        @endforelse
    </x-tables.white.table>

    @if ($links)
        <div class="">
            {{ $resource->links() }}
        </div>
    @endif
</div>
