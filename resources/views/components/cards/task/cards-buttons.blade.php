<div class="flex flex-row gap-1 items-center">
    <x-buttons.a-icon href="{{ $href ?? '' }}" title="{!! __('View') !!}" class="cursor-pointer">
        @include('icons.eye-line-v1', ['color' => color(16)])
    </x-buttons.a-icon>

    @can($modalprefix . '.update')
        @if (isset($type) && $type == "page")
            <x-buttons.edit-href href="{{ $edithref }}"
                :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])"/>
        @else
            <x-buttons.edit modal="{{ $modalprefix }}.modals.form" :data="$data" class="cursor-pointer" :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])" />
        @endif
    @endcan

</div>
