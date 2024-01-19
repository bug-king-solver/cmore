<div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '3' }} gap-9">
    @foreach ($tags as $tag)
        <x-cards.list>
            <x-slot:header>
                <div class="flex flex-row gap-2 items-center min-h-[50px] pb-3">
                    <a href="{{ $url ?? '#' }}" class="cursor-pointer">
                        <span class="text-esg29 font-encodesans text-sm font-bold line-clamp-2">
                            {{ $tag->name }}
                        </span>
                    </a>
                </div>
            </x-slot>

            <x-slot:content>
                <div class="flex flex-row gap-2 mt-4 items-center">
                    <div class="w-4 h-4 rounded-sm" style="background-color: {{ $tag->color }}"></div>
                    <div class="text-esg16 text-xs font-medium">{{ $tag->color }}</div>
                </div>

                <div class="mt-4">
                    <span class="px-3 py-1 rounded-sm bg-esg16/20 text-esg16 text-xs">
                        {{ $tag->slug }}
                    </span>
                </div>

                <div class="flex flex-row gap-2 mt-4 items-center">
                    <div class="w-4 h-4 rounded-full {{ $tag->trashed() ? 'bg-red-600' : 'bg-esg2' }}"></div>
                    <div class="text-esg16 text-xs font-medium">{{ $tag->trashed() ? __('Deleted') : __('Active') }}
                    </div>
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div class="flex flex-row gap-2 items-end">
                    @if ($tag->trashed())
                        @php $data = json_encode(["tag" => $tag->id]); @endphp
                        <x-buttons.restore modal="tags.modals.restore" :data="$data" data-test="restore-tags-btn" />
                    @else
                        <x-cards.cards-buttons modalprefix="tags" routeShow="tenant.tags.show" :routeParams="['tag' => $tag->id]"
                            :data="json_encode(['tag' => $tag->id])" href="{{ $href ?? '' }}" type="{{ $type ?? '' }}" view="view"
                            viewhref="{{ route('tenant.tags.show', ['tag' => $tag->id]) }}" />
                    @endif
                </div>
            </x-slot:footer>
        </x-cards.list>
    @endforeach
</div>

<div class="">
    {{ $tags->links() }}
</div>

@if ($tags->isEmpty())
    <div class="flex justify-center items-center p-6">
        <h3 class="w-fit text-md">{{ __('No tags available yet. Click the “Add” button to create a new one.') }}</h3>
    </div>
@endif
