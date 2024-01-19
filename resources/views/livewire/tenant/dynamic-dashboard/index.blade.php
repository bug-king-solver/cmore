<div class="dashboard">
    @push('body')
        <script nonce="{{ csp_nonce() }}">
            document.addEventListener('DOMContentLoaded', () => {
                var options = {
                    plugins: {
                        tooltip: {
                            enabled: false,
                        }
                    },
                    rotation: 270, // start angle in degrees
                    circumference: 180, // sweep angle in degrees
                    cutout: '33%',
                };

                var data = {
                    datasets: [{
                            data: [10, 90],
                            backgroundColor: [twConfig.theme.colors.esg2, twConfig.theme.colors.esg10],
                            hoverBackgroundColor: [twConfig.theme.colors.esg2, twConfig.theme.colors.esg10],
                            borderRadius: 15,
                            borderWidth: 0,
                            spacing: 0,
                        },
                        {
                            weight: 0.2,
                        },
                        {
                            data: [15, 85],
                            backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg10],
                            hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg10],
                            borderRadius: 15,
                            borderWidth: 0,
                            spacing: 0,
                        },
                        {
                            weight: 0.2,
                        },
                        {
                            data: [20, 80],
                            backgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg10],
                            hoverBackgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg10],
                            borderRadius: 15,
                            borderWidth: 0,
                            spacing: 0,
                        },
                    ],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options: options
                };

                var ctx = document.getElementById('esg_category_total');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        tooltip: {
                            enabled: false,
                        }
                    },
                    rotation: 270, // start angle in degrees
                    circumference: 180, // sweep angle in degrees
                    cutout: '33%',
                };

                var data = {
                    datasets: [{
                            data: [1],
                            backgroundColor: [twConfig.theme.colors.esg10],
                            hoverBackgroundColor: [twConfig.theme.colors.esg10],
                            borderRadius: 15,
                            borderWidth: 0,
                            spacing: 0,
                        },
                        {
                            weight: 0.2,
                        },
                        {
                            data: [1],
                            backgroundColor: [twConfig.theme.colors.esg10],
                            hoverBackgroundColor: [twConfig.theme.colors.esg10],
                            borderRadius: 15,
                            borderWidth: 0,
                            spacing: 0,
                        },
                        {
                            weight: 0.2,
                        },
                        {
                            data: [1],
                            backgroundColor: [twConfig.theme.colors.esg10],
                            hoverBackgroundColor: [twConfig.theme.colors.esg10],
                            borderRadius: 15,
                            borderWidth: 0,
                            spacing: 0,
                        },
                    ],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options: options
                };

                var ctx = document.getElementById('esg_category_total2');
                new Chart(ctx, config);


                const chart = echarts.init(document.getElementById("water_chart"));
                chart.setOption({
                    series: [{
                        type: 'liquidFill',
                        data: [0.6, 0.5, 0.4, 0.3],
                        outline: {
                            show: false
                        }
                    }]
                });
            });
        </script>
    @endpush

    <div>
        <x-slot name="header">
            <x-header title="{{ __('Dashboard') }}" data-test="companies-header">
                <x-slot name="left"></x-slot>
            </x-header>
        </x-slot>

        <div class="w-full mt-48">
            <div class="px-4 lg:px-0">
                <div class="max-w-7xl mx-auto font-encodesans">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1 flex">
                            <div class="w-48">
                                <x-inputs.tomselect wire:model="search.dashboard" wire:change="filter()"
                                    :options="$dashboardList"
                                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                                    :items="[]" placeholder="Select Dashboard" multiple />
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1 flex md:justify-end">
                            <button type="button"
                                class="text-esg16 bg-esg4 hover:bg-esg4 border border-esg16 focus:outline-none focus:ring-esg16 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                                @include('icons.edit', ['class' => 'mr-2'])
                                {{ __('Edit dashboard') }}
                            </button>

                            <x-buttons.a text="{{ __('Create Dashboard') }}"
                                href="{{ route('tenant.dynamic-dashboard.index') }}"
                                class="text-esg4 bg-esg5 hover:bg-esg5 border border-esg5 focus:outline-none focus:ring-esg5 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mb-2" />
                        </div>

                        <div class="col-span-2 w-full border-t-2 my-6"></div>

                        <div class="col-span-1 md:col-span-2 w-full mb-5">
                            <div class="flex">
                                <div class="mr-2 w-44">
                                    <x-inputs.tomselect wire:model="search.company" wire:change="filter()"
                                        :options="$companiesList"
                                        plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                                        :items="$search['companies']" multiple placeholder="Company / NIF" />
                                </div>

                                <div class="mr-2 w-44">
                                    <x-inputs.tomselect wire:model="search.businessSectors" wire:change="filter()"
                                        :options="$businessSectorsList"
                                        plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                                        :items="$search['businessSectors']" multiple placeholder="Business Sector" />
                                </div>

                                <div class="mr-2">
                                    <x-inputs.select input="select" id="year" :extra="['options' => ['0' => '2022']]" />
                                </div>

                                <div class="w-44">
                                    <x-inputs.tomselect wire:model="search.countries" wire:change="filter()"
                                        :options="$countriesList"
                                        plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                                        :items="$search['countries']" multiple placeholder="Country" />
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="bg-esg4 border border-esg7 rounded p-4 ">
                                <div class="flex justify-between">
                                    <div class="">
                                        <label class="text-esg8 font-bold text-base">
                                            {{ __('ESG Maturity Level') }}</label>
                                        <div class="flex">
                                            <div class="flex mt-2 mr-2">
                                                <div class="text-esg2 text-2xl">
                                                    <span
                                                        class="w-3 h-3 relative -top-2.5 rounded-full inline-block bg-esg2 text-esg2"></span>
                                                </div>
                                                <div class="pl-2 inline-block font-normal text-sm text-esg11">
                                                    {{ __('Environment') }}</div>
                                            </div>

                                            <div class="flex mt-2 mr-2">
                                                <div class="text-esg1 text-2xl">
                                                    <span
                                                        class="w-3 h-3 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-2 inline-block font-normal text-sm text-esg11">
                                                    {{ __('Social') }}</div>
                                            </div>

                                            <div class="flex mt-2">
                                                <div class="text-esg3 text-2xl">
                                                    <span
                                                        class="w-3 h-3 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                                </div>
                                                <div class="pl-2 inline-block font-normal text-sm text-esg11">
                                                    {{ __('Governance') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        @include('icons.action')
                                    </div>
                                </div>

                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <canvas id="esg_category_total2"
                                        class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                    <canvas id="esg_category_total"
                                        class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                </div>

                                <div class="flex justify-end">
                                    @include('icons.dashboards.fullscreen')
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="bg-esg4 border border-esg7 rounded p-4 ">
                                <div class="flex justify-between">
                                    <div class="">
                                        <label class="text-esg8 font-bold text-base">
                                            {{ __('Water Consumption') }}</label>
                                        <div class="flex">
                                            <div class="flex mt-2 mr-2">
                                                <div class="text-esg6 text-2xl">
                                                    <span
                                                        class="w-3 h-3 relative -top-2.5 rounded-full inline-block bg-esg6 text-esg6"></span>
                                                </div>
                                                <div class="pl-2 inline-block font-normal text-sm text-esg11">
                                                    {{ __('Yearly Water Used (m³, thouand of liters)') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        @include('icons.action')
                                    </div>
                                </div>

                                <div class="relative m-auto h-[270px] w-[270px] lg:h-[400px] lg:w-[400px]">
                                    <div id="water_chart" class="h-[270px] w-[270px] lg:h-[400px] lg:w-[400px]"></div>
                                </div>

                                <div class="flex justify-end">
                                    @include('icons.dashboards.fullscreen')
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="bg-esg4 border border-esg7 rounded p-4 ">
                                <div class="flex justify-between">
                                    <div class="">
                                        <label class="text-esg8 font-bold text-base">
                                            {{ __('Action Plans - Prority Matrix') }}</label>
                                    </div>

                                    <div class="">
                                        @include('icons.action')
                                    </div>
                                </div>

                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                </div>

                                <div class="flex justify-end">
                                    @include('icons.dashboards.fullscreen')
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="bg-esg4 border border-esg7 rounded p-4 ">
                                <div class="flex justify-between">
                                    <div class="">
                                        <label class="text-esg8 font-bold text-base"> {{ __('Action Plans') }}</label>
                                    </div>

                                    <div class="">
                                        @include('icons.action')
                                    </div>
                                </div>

                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <canvas id="esg_category_total2"
                                        class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                    <canvas id="esg_category_total"
                                        class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                </div>

                                <div class="flex justify-end">
                                    @include('icons.dashboards.fullscreen')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
