<div class="flex flex-row gap-1 items-end">
    <x-buttons.a-icon href="{{ route($routeShow, $routeParams) }}" title="{!! __('View') !!}" class="cursor-pointer">
        @include('icons.eye-line-v1', ['color' => color(8)])
    </x-buttons.a-icon>

    @if (isset($type) && $type="page")
        <x-buttons.edit-href href="{{ $href }}" />
    @else
        <x-buttons.edit modal="{{ $modalprefix }}.modals.form" :data="$data" class="cursor-pointer"/>
    @endif

    <x-buttons.trash modal="{{ $modalprefix }}.modals.delete" :data="$data" class="px-2 py-1" class="cursor-pointer"/>
</div>
