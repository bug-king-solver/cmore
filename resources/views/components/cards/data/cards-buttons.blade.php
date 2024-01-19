<div class="flex flex-row gap-1 items-center">
    <x-buttons.a-icon href="{{ $viewhref ?? '#' }}" title="{!! __('View') !!}" class="cursor-pointer {{ $view ?? 'hidden' }}">
        @include('icons.eye-line-v1', ['color' => color(16)])
    </x-buttons.a-icon>

    @can($modalprefix . '.delete')
        <x-buttons.trash modal="{{ $modalprefix }}.modals.delete" :data="$data" class="px-2 py-1" class="cursor-pointer" :param="json_encode(['stroke' => color(16)])" />
    @endcan
</div>
