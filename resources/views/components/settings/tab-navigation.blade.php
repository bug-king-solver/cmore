@props([
    'tabs' => [],
    'activeTab' => null
])

<ul class="flex items-center gap-5 mb-10 p-2 bg-esg72 rounded-md" data-tabs-toggle="#applicationSettingTab" role="tablist" x-data="{ activeTab: '{{ $activeTab }}' }">
    @foreach ($tabs as $tab)
        <li role="presentation">
            <button class="flex items-center p-2 rounded-md gap-2"
                :class="{ 'bg-white font-semibold text-esg29': activeTab === '{{ $tab['id'] }}', 'font-normal text-esg16': activeTab !== '{{ $tab['id'] }}' }"
                id="{{ $tab['id'] }}-tab" data-tabs-target="#{{ $tab['id'] }}" type="button" role="tab"
                aria-controls="{{ $tab['id'] }}" aria-selected="{{ $activeTab === $tab['id'] ? 'true' : 'false' }}" x-on:click="activeTab = '{{ $tab['id'] }}'">
                <span class="font-normal flex gap-1 text-sm items-center">
                    @include('icons.' . $tab['icon']) {!! __($tab['label']) !!}
                </span>
            </button>
        </li>
    @endforeach
</ul>
