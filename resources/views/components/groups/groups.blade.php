<div class="flex flex-col mx-auto max-w-7xl leading-normal pb-10 mt-10">

    @if (count($groups['groups']) > 0)
        <div class="w-full pb-2 lg:gap-3 xl:gap-3 md:gap-4 sm:gap-4 xs:gap-4 text-esg6 text-2xl">
            {{ $groupLevelDescription }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-9">
            @foreach ($groups['groups'] as $group)
                <x-groups.card-group :parentGroup="$parentGroup" :group="$group" name="name" />
            @endforeach

            <x-cards.card-version-target-panel-v2 text="{{ __('Overview') }}">
                {{-- TODO finish this
                <x-groups.targets.dropdown-filter-date :title="$this->ongoingInterval['title']" event="changeOngoingInterval" />
                --}}
                <x-slot:data>
                    @foreach ($groups['data'] as $i => $progress)
                        <div class="flex justify-between mt-2">
                            <div class="text-sm font-normal text-esg8"> {{ $group['name'] }}
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm font-extrabold text-esg8">
                                    {{ $group['chartData']['progressCompleted'] ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </x-slot:data>
            </x-cards.card-version-target-panel-v2>
        </div>
    @endif

    @if (count($groups['resources']) > 0)
        <div class="mt-10"></div>

        <div class="w-full pb-2 text-esg6 text-2xl">
            {{ __('Targets') }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-9">
            @foreach ($groups['resources'] as $resource)
                <x-groups.cards-components :group="$parentGroup" :resource="$resource" name="title" :isGroup="false"
                    type="{{ $type ?? 'modal' }}" />
            @endforeach
        </div>
    @endif

    @if (count($groups['groups']) == 0 && count($groups['resources']) == 0)
        <div class="flex justify-center items-center p-6 mt-10">
            <h3 class="w-fit text-md">
                {{ __('No groups and targets available yet. Click the “Add” button to create a new one.') }}
            </h3>
        </div>
    @endif
</div>
