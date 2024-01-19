<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('Data') }}" data-test="data-header">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="w-full flex items-center justify-between">
        <a href="{{ route('tenant.data.indicators.show', ['indicator' => $data->indicator->id]) }}"
            class="text-esg5 w-fit text-lg font-bold flex flex-row gap-2 items-center">
            @include('icons.back', [
                'color' => color(5),
                'width' => '20',
                'height' => '16',
            ])
            {{ __('Update details :') }} {{ $data->indicator->name }} - {{ $data->company->name }}
        </a>

        <div class="flex items-center gap-2">

            @if ($viewer == 'creator')
                <x-buttons.a-icon
                    href="{{ route('tenant.data.form', ['company' => $data->company_id, 'indicator' => $data->indicator_id, 'data' => $data->id]) }}"
                    data-test="add-data-btn" class="flex place-content-end !p-0">
                    <div
                        class="flex gap-2 items-center bg-esg4 py-1.5 px-3 cursor-pointer rounded-md text-esg8 border border-esg8">
                        @include('icons.edit')
                        <label class="cursor-pointer uppercase">{{ __('Edit') }}</label>
                    </div>
                </x-buttons.a-icon>
            @else
                @if ($data->validator_status === 0 || $data->auditor_status === 0)
                    @php $modaldata = json_encode(['data' => $data->id]); @endphp
                    <x-buttons.a-icon href="#"
                        x-on:click="Livewire.emit('openModal', 'data.modals.request', {{ $modaldata ?? null }})"
                        data-test="add-data-btn" class="flex place-content-end !p-0">
                        <div
                            class="flex gap-2 items-center bg-esg4 py-1.5 px-3 cursor-pointer rounded-md text-esg8 border border-esg8 {{ $data->validator_requested != null || $data->auditor_requested != null ? '!text-esg4 !bg-[#F69138] !border-[#F69138]' : '' }}">
                            @include('icons.info', [
                                'color' =>
                                    $data->validator_requested != null || $data->auditor_requested
                                        ? color(4)
                                        : color(8),
                            ])
                            <label class="cursor-pointer">{{ __('Request info') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @else
                    <x-buttons.a-icon href="#" data-test="add-data-btn" class="flex place-content-end !p-0">
                        <div
                            class="flex gap-2 items-center bg-esg4 py-1.5 px-3 cursor-pointer rounded-md text-esg7 border border-esg7">
                            @include('icons.info', ['color' => color(7)])
                            <label>{{ __('Request info') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endif

                @if ($viewer == 'validator')
                    <x-buttons.a-icon href="#" wire:click="validatorStatusUpdate({{ $data->validator_status }})"
                        data-test="add-data-btn" class="flex place-content-end !p-0">
                        <div
                            class="flex gap-2 items-center bg-esg4 py-1.5 px-3 cursor-pointer rounded-md text-esg8 border border-esg7 {{ $data->validator_status === 1 ? '!text-esg4 !bg-[#0D9401] !border-[#0D9401]' : '' }}">
                            @include('icons.checkbox', [
                                'width' => 15,
                                'height' => 15,
                                'color' => $data->validator_status === 1 ? color(4) : color(2),
                            ])
                            <label
                                class="cursor-pointer">{{ $data->validator_status === 1 ? __('Validated') : __('Validate') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @else
                    <x-buttons.a-icon href="#" wire:click="auditorStatusUpdate({{ $data->auditor_status }})"
                        data-test="add-data-btn" class="flex place-content-end !p-0">
                        <div
                            class="flex gap-2 items-center bg-esg4 py-1.5 px-3 cursor-pointer rounded-md text-esg8 border border-esg7 {{ $data->auditor_status === 1 ? '!text-esg4 !bg-[#0D9401] !border-[#0D9401]' : '' }}">
                            @include('icons.checkbox', [
                                'width' => 15,
                                'height' => 15,
                                'color' => $data->auditor_status === 1 ? color(4) : color(2),
                            ])
                            <label
                                class="cursor-pointer">{{ $data->auditor_status === 1 ? __('Audited') : __('Audit') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endif
            @endif
        </div>
    </div>

    <div class="mt-6">
        <div class="flex gap-2 items-center">
            <div class=""> @include('icons.up') </div>
            <div class="text-sm font-bold text-esg8"> {{ __('Value') }} : </div>
            <div class="text-sm text-esg8 flex gap-3 items-center">
                {{ $data->value }}

                @if ($viewer == 'validator' && $data->validator_status === 1)
                    <div class="flex items-center gap-1 bg-esg7/20 rounded w-20 p-1">
                        @include('icons.checkbox', ['width' => 14, 'height' => 14])
                        <span class="text-xs text-esg8"> {{ __('Updated') }} </span>
                    </div>
                @elseif ($viewer == 'auditor' && $data->auditor_status === 1)
                    <div class="flex items-center gap-1 bg-esg7/20 rounded w-20 p-1">
                        @include('icons.checkbox', ['width' => 14, 'height' => 14])
                        <span class="text-xs text-esg8"> {{ __('Audited') }} </span>
                    </div>
                @elseif ($viewer == 'creator' && $data->validator_status === 1 && $data->auditor_status === 1 || $data->validator_status === 1 && !$auditor)
                    <div class="flex items-center gap-1 bg-esg7/20 rounded w-20 p-1">
                        @include('icons.checkbox', ['width' => 14, 'height' => 14])
                        <span class="text-xs text-esg8"> {{ __('Updated') }} </span>
                    </div>
                @else
                <div class="flex items-center gap-1 bg-esg7/20 rounded w-32 p-1">
                    @include('icons.loading')
                    <span class="text-xs text-esg8"> {{ __('Pending validation') }} </span>
                </div>
                @endif
            </div>
        </div>

        <div class="flex gap-2 items-center mt-2">
            <div class=""> @include('icons.info', ['color' => color(5)]) </div>
            <div class="text-sm font-bold text-esg8"> {{ __('Origin of data') }} : </div>
            <div class="text-sm text-esg8"> {{ $data->origin }} </div>
        </div>

        <div class="flex gap-2 items-center mt-2">
            <div class=""> @include('icons.calander', ['color' => color(5)]) </div>
            <div class="text-sm font-bold text-esg8"> {{ __('Date of report') }} : </div>
            <div class="text-sm text-esg8">
                <div class="flex items-center gap-1 bg-esg7/20 rounded p-1">
                    <span class="flex items-center">@include('icons.calander', ['color' => color(8)])
                        {{ \Carbon\Carbon::parse($data->reported_at)->format('Y-m-d') }}</span>
                    <span class="flex items-center">@include('icons.clock', ['color' => color(8)])
                        {{ \Carbon\Carbon::parse($data->reported_at)->format('H:i A') }}</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 items-center mt-2">
            <div class=""> @include('icons.user', ['color' => color(5), 'width' => 14, 'height' => 16]) </div>
            <div class="text-sm font-bold text-esg8"> {{ __('Rapporteur') }} : </div>
            <div class="text-sm text-esg8 flex items-center gap-1">
                <img class="rounded-full" src="{{ $data->user->avatar ?? '' }}" width="20" height="20" />
                {{ $data->user->name ?? '-' }}
            </div>
        </div>

        <div class="flex gap-2 items-center mt-2">
            <div class=""> @include('icons.user', ['color' => color(5), 'width' => 14, 'height' => 16]) </div>
            <div class="text-sm font-bold text-esg8"> {{ __('Validator') }} : </div>
            <div class="text-sm text-esg8">
                <div class="flex items-center gap-3">
                    @foreach ($validator->users ?? [] as $user)
                        <div class="flex items-center gap-1">
                            <img class="rounded-full" src="{{ $user->avatar }}" width="20" height="20">
                            <span class="text-sm text-esg8">{{ $user->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-2 items-center mt-2">
            <div class=""> @include('icons.user', ['color' => color(5), 'width' => 14, 'height' => 16]) </div>
            <div class="text-sm font-bold text-esg8"> {{ __('Auditor') }} : </div>
            <div class="text-sm text-esg8">
                <div class="flex items-center gap-3">
                    @foreach ($auditor->users ?? [] as $user)
                        <div class="flex items-center gap-1">
                            <img class="rounded-full" src="{{ $user->avatar }}" width="20" height="20">
                            <span class="text-sm text-esg8">{{ $user->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-esg7 py-8">
        <div class="text-base font-bold text-esg8"> {{ __('Attached evidences') }} : </div>
        ---
    </div>

    <div class="mt-6 border-t border-esg7 py-8">
        @foreach ($activitys ?? [] as $activity)
            @php
                $username = $activity->causer->name ?? '';
            @endphp
            @foreach ($activity->properties['attributes'] as $key => $attribute)
                @if ($key == 'validator_requested')
                    <div class="mt-2">
                        <p class="text-sm text-esg16"> {{ __('Information requested by validator: ') }} <span
                                class="font-bold">
                                {{ $username }} </span> </p>
                        <p class="text-sm text-esg16"> "{{ $attribute }}" </p>
                    </div>
                @endif

                @if ($key == 'auditor_requested')
                    <div class="mt-2">
                        <p class="text-sm text-esg16"> {{ __('Information requested by auditor: ') }} <span
                                class="font-bold">
                                {{ $username }} </span> </p>
                        <p class="text-sm text-esg16"> "{{ $attribute }}" </p>
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
