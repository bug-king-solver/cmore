@props([
    'buttons' => [],
    'activated' => null,
    'reference' => '',
])

<div class="flex items-center gap-5 mb-10 p-2 bg-esg72 rounded-md ">
    @foreach ($buttons as $button)
        <x-menus.panel-buttons :button="$button" :activated="$activated" :reference="$reference" />
    @endforeach
</div>
