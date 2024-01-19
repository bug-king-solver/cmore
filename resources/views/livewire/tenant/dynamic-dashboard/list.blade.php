<div>
    <x-slot name="header">
        <x-header title="{{ __('Dashboard') }}" data-test="companies-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <div class="float-right">
                    <x-buttons.a-icon href="{{ route('tenant.dynamic-dashboard.create') }}"
                        data-test="add-questionnaires-btn" class="flex place-content-end cursor-pointer">
                        <div class="flex gap-2 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['width' => 12, 'height' => 12, 'color' => color(4)])
                            <label class="uppercase font-medium text-sm cursor-pointer">{{ __('Add') }}</label>
                        </div>
                    </x-buttons.a-icon>
                </div>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="relative flex w-full font-encodesans" x-data="{ templatesShow: true }">
        <div class="px-4 lg:px-0 w-full">

            <div class="flex w-full justify-end">
                <label class="mb-10 inline-flex relative items-center cursor-pointer ">
                    <span class="mr-3 text-sm font-medium text-[#444444] ">
                        {!! __('Show Templates') !!}
                    </span>
                    <input type="checkbox" checked="true" class="sr-only peer" value="templatesShow"
                        x-on:click="templatesShow = !templatesShow">
                    <div
                        class="w-9 h-5 bg-[#757575] rounded-full peer bg-[#757575] peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[17px] after:bg-white after:border-white-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-esg5">
                    </div>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[16px]"
                x-show="templatesShow">
                @foreach ($templateList as $i => $row)
                    <x-cards.card class="flex-col !p-3 !gap-10 justify-between h-auto">
                        <div class="flex flex-col justify-between">
                            <div class="">
                                <label class="text-sm font-bold text-[#444444]"> {{ $row['name'] }} </label>
                            </div>
                            <div class="">
                                <label class="text-sm text-[#8A8A8A]"> {{ $row['description'] }} </label>
                            </div>
                        </div>
                        <div class="flex flex-row gap-[8px] items-center justify-around max-h-[30px]">
                            @php
                                $params = json_encode(['stroke' => color(8), 'width' => 14, 'height' => 18]);
                                $data = json_encode(['dashboardTemplate' => $row['id']]);
                            @endphp
                            <x-buttons.btn-outline modal="report.modals.template-create-dashboard" :data="$data"
                                :param="$params"
                                class="py-2 px-3 rounded-md border border-1 !border-[#757575] !text-[#757575] whitespace-nowrap min-w-[119.13px] text-center">
                                {{ __('Add') }}
                            </x-buttons.btn-outline>

                            <x-buttons.a
                                class="!py-2 !px-23rounded-md border border-1 !border-[#757575] !text-[#FFFFFF] whitespace-nowrap min-w-[119.13px] text-center"
                                href="{{ route('tenant.dynamic-dashboard.template.preview', [$row['id']]) }}">
                                {{ __('Preview') }}
                            </x-buttons.a>
                        </div>
                    </x-cards.card>
                @endforeach
            </div>

        </div>
    </div>

    <div class="w-full mt-8 font-encodesans">
        <div class="px-4 lg:px-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[16px]">
                @foreach ($list as $row)
                    <x-cards.card class="flex-col !p-3 !gap-10 justify-between">
                        <div class="flex flex-row gap-[8px] items-center justify-between max-h-[30px]">
                            <div class="">
                                <label class="text-base font-bold text-esg8 block"> {{ $row['name'] }} </label>
                            </div>
                            <div class="">
                                <div class="flex">
                                    <div class="mt-[6px]">
                                        <a href='{{ route('tenant.dynamic-dashboard.edit', [$row['id']]) }}'>
                                            @include('icons.edit', ['color' => color(8)])
                                        </a>
                                    </div>
                                    @php $data = json_encode(["dashboard" => $row->id]); @endphp
                                    <x-buttons.trash modal="report.modals.delete" :data="$data" :param="json_encode([
                                        'stroke' => color(8),
                                        'width' => 14,
                                        'height' => 18,
                                    ])" />
                                </div>
                            </div>
                        </div>
                        <div class=" flex flex-row gap-[8px] items-center justify-between">
                            <div class="">
                                <label class="text-sm text-[#8A8A8A]">
                                    {{ $row->totalGraphs }} {{ __('graphs') }}
                                </label>
                            </div>
                            <div class="">
                                <div class="flex">
                                    <a href='{{ route('tenant.dynamic-dashboard', [$row['id']]) }}'>
                                        @include('icons.arrow-right', ['color' => color(8)])
                                    </a>
                                </div>
                            </div>
                        </div>
                    </x-cards.card>
                @endforeach
            </div>

            <div class="grid mt-10">
                <x-cards.card-empty class="h-auto !min-h-[175px] !w-[278px] !border-dashed !justify-center text-center">
                    <a href="{{ route('tenant.dynamic-dashboard.create') }}"
                        class="flex items-center h-full w-full text-center">
                        <div class="flex w-full justify-center">
                            <div>
                                @include('icons.plus', ['color' => color(8)])
                            </div>
                            <div class="text-normal font-normal text-esg8text-sm">
                                {{ __('Create Dashboard') }}
                            </div>
                        </div>
                    </a>
                </x-cards.card-empty>
            </div>
        </div>
    </div>
</div>
