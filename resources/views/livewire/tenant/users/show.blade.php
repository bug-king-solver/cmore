<div>
    <x-slot name="header">
        <x-header title="{{ __('Users') }}" data-test="users-header" click="{{ route('tenant.users.index') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="mt-5 p-3 border border-esg16/10 rounded-md">
        <div class="flex items-center gap-10">
            <div class="">
                <img class="rounded-full" src="{{ $user->avatar }}" width="99" height="99">
            </div>
            <div class="grow">
                <div class="flex items-center gap-20">
                    <div class="">
                        <label class="text-esg16"> {{ __('Name') }} </label>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="">
                        <label class="text-esg16"> {{ __('Email') }} </label>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="">
                        <label class="text-esg16"> {{ __('Roles') }} </label>
                        <p>
                            @php $rolesArr = json_encode($user->roles->pluck('name')); @endphp
                            <x-dropdown.toggle data="{!! $rolesArr !!}" roleid="{{ $user->id }}"></x-dropdown.toggle>
                        </p>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="text-esg16"> {{ __('Tags') }} </label>
                    <p> {{ !empty($user->tags->toArray()) ? implode(", ", array_column($user->tags->toArray(), 'name')) : '-' }} </p>
                </div>
            </div>
        </div>
    </div>


    <div class="mb-4 border-b border-gray-200 mt-6">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 rounded-t-lg {{ $tab == 'assigned' ? 'active !text-esg8 border-b border-b-esg5' : '!text-esg7' }}" wire:click="tab('assigned')" value="assigned" id="assigned-tab" data-tabs-target="#assigned" type="button" role="tab" aria-controls="assigned" aria-selected="false">{{ __('Assigned') }}</button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 {{ $tab != 'assigned' ? 'active !text-esg8 border-b border-b-esg5' : ' !text-esg7' }}"  wire:click="tab('history')" value="history" id="history-tab" data-tabs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">{{ __('History') }}</button>
            </li>
        </ul>
    </div>
    <div id="myTabContent" class="mb-20">
        <div class="{{ $tab == 'assigned' ? '' : 'hidden' }}" id="assigned" role="tabpanel" aria-labelledby="assigned-tab">

            <div class="flex gap-5">

                <div class="w-52">
                    <x-inputs.select id="type" :extra="['options' => $typeList]" class="h-12 w-23"/>
                </div>

                <x-filters.filter-bar-v2 :filters="$availableFilters" />
            </div>

            <div class="mt-6 mb-3">
                <label class="text-base text-esg5 font-bold "> {{ __('Companies') }} </label>
            </div>

            @if ($companies->isEmpty())
                <div class="flex justify-center items-center p-6">
                    <h3 class="w-fit text-md">{{ __('No company available yet.') }}</h3>
                </div>
            @else
                <x-cards.company.list :companies="$companies" :countries="$countries" grid="4" />
            @endif


            <div class="mt-6 mb-3">
                <label class="text-base text-esg5 font-bold "> {{ __('Questionnaires') }} </label>
            </div>

            @if ($questionnaires->isEmpty())
                <div class="flex justify-center items-center p-6">
                    <h3 class="w-fit text-md">{{ __('No questionnaires available yet.') }}</h3>
                </div>
            @else
                <x-cards.questionnaire.list :questionnaires="$questionnaires" grid="4" />
            @endif


            <div class="mt-6 mb-3">
                <label class="text-base text-esg5 font-bold "> {{ __('Targets') }} </label>
            </div>

            @if ($targets->isEmpty())
                <div class="flex justify-center items-center p-6">
                    <h3 class="w-fit text-md">{{ __('No targets available yet.') }}</h3>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-4 gap-9">
                    @foreach ($targets as $resource)
                        <x-groups.cards-components :resource="$resource" name="title" :isGroup="false" type="{{ $type ?? 'modal' }}"/>
                    @endforeach
                </div>

                <div class="">
                    {{ $targets->links() }}
                </div>
            @endif

        </div>


        <div class="{{ $tab == 'assigned' ? 'hidden' : '' }}" id="history" role="tabpanel" aria-labelledby="history-tab">

            <div class="mt-6 mb-3">
                <label class="text-base text-esg5 font-bold "> {{ __('Recent activity') }} </label>
            </div>

            @if ($activities->isEmpty())
                <div class="flex justify-center items-center p-6">
                    <h3 class="w-fit text-md">{{ __('No activity found yet.') }}</h3>
                </div>
            @else
                <x-tables.white.table>
                    <x-slot name="thead" class="!border-0 bg-esg4">
                        <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Date time') }}</x-tables.th>
                        <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Page') }}</x-tables.th>
                        <x-tables.white.th class="bg-esg4 text-esg6 !text-sm">{{ __('Activity') }}</x-tables.th>
                    </x-slot>

                    @foreach ($activities as $activitie)
                        @php
                            $type = explode('\\', $activitie->subject_type);
                        @endphp
                        <x-tables.white.tr class="border-b border-b-esg8/20">
                            <x-tables.white.td class="!px-0 text-sm">{{ $activitie->created_at }}</x-tables.td>
                            <x-tables.white.td class="!px-0 text-sm">{{ end($type) }}</x-tables.td>
                            <x-tables.white.td class="!px-0 text-sm">{{ $activitie->description }}</x-tables.td>
                        </x-tables.white.tr>
                    @endforeach
                </x-tables.white.table>

                <div class="">
                    {{ $activities->links() }}
                </div>
            @endif

        </div>
    </div>



</div>
