<div>
    <x-slot name="header">
        <x-header title="{{ __('Audit Log') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>
    <div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal">
        <div class="text-indigo-100 py-3 rounded-b-sm">

            <div class="">
                <x-tables.table>
                    <x-slot name="thead">
                        <x-tables.th>{{ __('MEMBER') }}</x-tables.th>
                        <x-tables.th>{{ __('ACTION') }}</x-tables.th>
                        <x-tables.th>{{ __('IP') }}</x-tables.th>
                        <x-tables.th>{{ __('TIME') }}</x-tables.th>
                    </x-slot>

                    @foreach($activities as $activity)
                        <x-tables.tr class=" hover:bg-esg44">
                            <x-tables.td class="pl-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3">
                                        @if ($activity->causer)<img class="rounded-full" src="{{ $activity->causer->avatar }}" width="40" height="40">@endif
                                    </div>
                                    <div class="font-medium text-gray-800">
                                        @if ($activity->causer){{ $activity->causer->name }}<br>@endif
                                        @if(isset($activity->changes['attributes']) || isset($activity->changes['old']))
                                            @php
                                                $records = isset($activity->changes['attributes']) ? $activity->changes['attributes'] : $activity->changes['old'];
                                            @endphp
                                            @if($activity->event == 'updated')
                                                <strong style="text-transform: capitalize;">
                                                    {{ $activity->description }}
                                                </strong> :: {{ showUpdatedActivityString($activity->changes['attributes'], $activity->changes['old']) }}
                                            @elseif($activity->event == 'created' || $activity->event == 'deleted')
                                                <strong style="text-transform: capitalize;">
                                                    {{ $activity->description }}
                                                </strong> :: {{ showActivityString($records) }}
                                            @endif
                                        @else
                                            <strong style="text-transform: capitalize;">
                                                {{ $activity->description }}
                                            </strong>
                                        @endif
                                    </div>
                                </div>

                            </x-tables.td>
                            <x-tables.td>
                                {{$activity->subject_type}}
                            </x-tables.td>
                            <x-tables.td>
                                {{ $activity->properties['ip'] ?? '' }}
                            </x-tables.td>
                            <x-tables.td>
                                {{ $activity->created_at }}
                            </x-tables.td>

                        </x-tables.tr>
                    @endforeach
                </x-tables.table>
                <div class="font-medium text-gray-800">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
