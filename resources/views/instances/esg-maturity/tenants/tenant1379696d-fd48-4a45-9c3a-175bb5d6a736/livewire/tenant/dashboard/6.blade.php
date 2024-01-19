<div class="dashboard">
    @php
        $categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
        $genderIconUrl = global_asset('images/icons/genders/{id}.svg');
    @endphp

    @push('body')
        <style nonce="{{ csp_nonce() }}">
            @media print {
                .pagebreak {
                    clear: both;
                    page-break-after: always;
                }
                div {
                    break-inside: avoid;
                }
            }
        </style>
        <script nonce="{{ csp_nonce() }}">
            var global_esg = null,
                main_categories_esg = null,
                category1 = null,
                category2 = null,
                category3 = null,
                esg_global2 = null,
                esg_category_total2 = null,
                category1_1 = null,
                category2_1 = null,
                category3_1 = null;

            document.addEventListener('DOMContentLoaded', () => {
                if (@this.chart != null) {
                    charts();
                }

                Livewire.hook('message.processed', (el, component) => {
                    charts();
                });
            });

            function charts() {
                // Global ESG
                if(@this.chart.global_esg != null) {
                    if (global_esg !== null) {
                        global_esg.destroy();
                        esg_global2.destroy();
                    }

                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg8
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%'
                    };

                    var data = {
                        datasets: [{
                            data: @this.chart.global_esg,
                            backgroundColor: ['#FCE300', '#E1E6EF'],
                            hoverBackgroundColor: ['#FCE300', '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('esg_global');
                    global_esg = new Chart(ctx, config);

                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg27
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%',
                        elements: {
                            arc: {
                                roundedCornersFor: 0
                            }
                        }
                    };

                    var data = {
                        datasets: [{
                            data: [1],
                            backgroundColor: [ '#E1E6EF'],
                            hoverBackgroundColor: [ '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('esg_global2');
                    esg_global2 = new Chart(ctx, config);
                }

                // Joint
                if(@this.chart.main_categories_esg != null) {
                    if (main_categories_esg !== null) {
                        main_categories_esg.destroy();
                        esg_category_total2.destroy();
                    }

                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Consolidated Maturity Level by sector') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg8
                            },
                            tooltip: {
                                enabled: false,
                            },
                            datalabels: {
                                color: twConfig.theme.colors.esg27,
                                formatter: function (value, context) {
                                    if (context.dataIndex == 0 ) {
                                        return context.dataset.data[0] + '%';
                                    } else {
                                        return '';
                                    }
                                },
                                font: {
                                    weight: 'bold',
                                    size: 15,
                                }
                            }
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '33%',
                    };

                    var data = {
                        datasets: [
                            {
                                data: @this.chart.main_categories_esg[0],
                                backgroundColor: ['#AED000', '#E1E6EF'],
                                hoverBackgroundColor: ['#AED000', '#E1E6EF'],
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                            },
                            {
                                weight: 0.2,
                            },
                            {
                                data: @this.chart.main_categories_esg[1],
                                backgroundColor: ['#2AD2C9', '#E1E6EF'],
                                hoverBackgroundColor: ['#2AD2C9', '#E1E6EF'],
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                            },
                            {
                                weight: 0.2,
                            },
                            {
                                data: @this.chart.main_categories_esg[2],
                                backgroundColor: ['#FF7500', '#E1E6EF'],
                                hoverBackgroundColor: ['#FF7500', '#E1E6EF'],
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                            },
                        ],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options: options,
                        plugins: [ChartDataLabels]
                    };

                    var ctx  = document.getElementById('esg_category_total');
                    main_categories_esg = new Chart(ctx, config);

                    var options1 =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Consolidated Maturity Level by sector') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg27
                            },
                            tooltip: {
                                enabled: false,
                            }
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '33%',
                    };

                    var data = {
                        datasets: [
                            {
                                data: [1],
                                backgroundColor: ['#E1E6EF'],
                                hoverBackgroundColor: ['#E1E6EF'],
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                            },
                            {
                                weight: 0.2,
                            },
                            {
                                data: [1],
                                backgroundColor: ['#E1E6EF'],
                                hoverBackgroundColor: ['#E1E6EF'],
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                            },
                            {
                                weight: 0.2,
                            },
                            {
                                data: [1],
                                backgroundColor: ['#E1E6EF'],
                                hoverBackgroundColor: ['#E1E6EF'],
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                            },
                        ],
                    };

                    var config2 = {
                        type: 'doughnut',
                        data: data,
                        options: options1
                    };

                    var ctx = document.getElementById('esg_category_total2');
                    esg_category_total2 = new Chart(ctx, config2);
                }

                // Category 1
                if(@this.chart.category1 != null) {
                    if (category1 !== null) {
                        category1.destroy();
                        category1_1.destroy();
                    }

                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg8
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%'
                    };

                    var data = {
                        datasets: [{
                            data: @this.chart.category1,
                            backgroundColor: ['#AED000', '#E1E6EF'],
                            hoverBackgroundColor: ['#AED000', '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('category1');
                    category1 = new Chart(ctx, config);

                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg27
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%',
                        elements: {
                            arc: {
                                roundedCornersFor: 0
                            }
                        }
                    };

                    var data = {
                        datasets: [{
                            data: [1],
                            backgroundColor: [ '#E1E6EF'],
                            hoverBackgroundColor: [ '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('category1_1');
                    category1_1 = new Chart(ctx, config);
                }

                // Category 2
                if(@this.chart.category2 != null) {
                    if (category2 !== null) {
                        category2.destroy();
                        category2_1.destroy();
                    }
                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg8
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%'
                    };

                    var data = {
                        datasets: [{
                            data: @this.chart.category2,
                            backgroundColor: ['#2AD2C9', '#E1E6EF'],
                            hoverBackgroundColor: ['#2AD2C9', '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('category2');
                    category2 = new Chart(ctx, config);


                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg27
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%',
                        elements: {
                            arc: {
                                roundedCornersFor: 0
                            }
                        }
                    };

                    var data = {
                        datasets: [{
                            data: [1],
                            backgroundColor: [ '#E1E6EF'],
                            hoverBackgroundColor: [ '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('category2_1');
                    category2_1 = new Chart(ctx, config);
                }

                // Category 3
                if(@this.chart.category3 != null) {
                    if (category3 !== null) {
                        category3.destroy();
                        category3_1.destroy();
                    }
                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg8
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%'
                    };

                    var data = {
                        datasets: [{
                            data: @this.chart.category3,
                            backgroundColor: ['#FF7500', '#E1E6EF'],
                            hoverBackgroundColor: ['#FF7500', '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('category3');
                    category3 = new Chart(ctx, config);


                    var options =  {
                        plugins: {
                            title: {
                                display: false,
                                text: '{{ __('Global ESG Maturity Level') }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                color: twConfig.theme.colors.esg27
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        rotation: 270, // start angle in degrees
                        circumference: 180, // sweep angle in degrees
                        cutout: '80%',
                        elements: {
                            arc: {
                                roundedCornersFor: 0
                            }
                        }
                    };

                    var data = {
                        datasets: [{
                            data: [1],
                            backgroundColor: [ '#E1E6EF'],
                            hoverBackgroundColor: [ '#E1E6EF'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                        }],
                    };

                    var config = {
                        type: 'doughnut',
                        data: data,
                        options
                    };

                    var ctx = document.getElementById('category3_1');
                    category3_1 = new Chart(ctx, config);
                }
            }

            document.addEventListener('alpine:init', () => {
                Alpine.bind('highlightCategory', (type) => ({
                    type: 'button',

                    '@click'() {
                        esgCategoryTotal(type);
                    },
                }))
            });

            document.addEventListener('alpine:init', () => {
                Alpine.bind('handlePrintClick', () => ({
                    type: 'button',

                    '@click'() {
                        window.print();
                    },
                }))
            });
        </script>
    @endpush

    <div class="print:hidden bg-esg6 h-44 flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center -mt-16">
        <div class="w-full mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid text-esg4 pt-14 text-2xl font-bold">
                {{ __('Dashboard') }}
            </div>
        </div>
    </div>

    <div class="px-4 lg:px-0 mt-10">
        <div class="max-w-7xl mx-auto mb-10">
            <div class="w-full text-right">
                <div class="print:hidden">
                    <div class="text-esg8 text-lg font-semibold pt-3">
                        <div class="flex gap-5 items-center">
                            <div class="w-full">
                                <x-inputs.tomselect
                                    :wire_ignore="false"
                                    wire:model.defer="search.questionnaire"
                                    :options="$questionnaireList"
                                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                                    :items="$search['questionnaire']"
                                    class="min-w-[150px]"
                                    multiple
                                />
                            </div>
                            <div>
                                <x-buttons.a-alt wire:click="filter()" text="{{ __('Mostrar') }}" class="!h-10 text-center !block !px-3 !py-3 cursor-pointer"/>
                            </div>
                            @if ($charts != null)
                                <div>
                                    <x-buttons.btn-icon-text class="bg-[#FF7500] text-esg4 print:hidden" x-bind="handlePrintClick">
                                        <x-slot name="buttonicon">
                                            @include(tenant()->views .'icons.download', ['class' => 'inline'])
                                        </x-slot>
                                        <span class="ml-2">Download</span>
                                    </x-buttons.btn-icon-text>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($charts != null)
                <div class="mt-10 pagebreak">
                    <p class="font-bold text-base text-esg8 pb-4"> {{ __('Maturidade ESG') }} </p>
                    <div class="grid grid-cols-1 lg:grid-cols-2 border border-esg8/20 pb-4 rounded-md">
                        <div class="p-3 xl:p-10 ">
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="esg_global2" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <canvas id="esg_global" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <div id="esg_global_value" class="text-esg8 absolute bottom-[68px] w-full text-center text-4xl font-bold">{!! $charts['global_esg'][0] !!}%</div>
                            </div>
                        </div>

                        <div id="esg_category" class="relative xl:p-10">
                            <div class="h-full w-full justify-center">
                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <canvas id="esg_category_total2" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                    <canvas id="esg_category_total" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-16 pagebreak print:mt-20">
                    <div class="flex gap-2  ">
                        @include('icons.categories.1', ['color' => '#AED000'])
                        <p class="font-bold text-base text-esg8 pb-4"> {{ __('Ambiente') }} </p>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 ">
                        <div class="p-3 xl:p-10 border border-esg8/20 pb-4 rounded-md">
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="category1_1" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <canvas id="category1" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <div id="category_value" class="text-esg8 absolute bottom-[68px] w-full text-center text-4xl font-bold">{!! $charts['category1'][0] !!}%</div>
                            </div>
                        </div>

                        <div class="relative xl:p-10">
                            <p class="text-2xl font-bold text-[#AED000] pb-3"> {{ __('Ambiente') }} </p>
                            <p class="text-esg8/60 text-base font-normal"> A adoção de práticas ambientais traz benefícios significativos para as empresas, a sociedade e o meio ambiente. Por isso, é fundamental que as empresas considerem a dimensão ambiental em suas estratégias e ações, buscando soluções inovadoras e efetivas para a preservação dos recursos naturais e a mitigação dos impactos ambientais. Empresas que adotam práticas ESG ambientais podem ter um impacto significativo na redução da pegada ecológica e na preservação dos recursos naturais. Para tanto, é preciso adotar medidas que visem à eficiência energética, à redução de resíduos e emissões de poluentes, à gestão sustentável da água e ao uso consciente dos recursos naturais. </p>
                        </div>
                    </div>
                </div>

                <div class="mt-16 pagebreak print:mt-20">
                    <div class="flex gap-2  ">
                        @include('icons.categories.2', ['color' => '#2AD2C9'])
                        <p class="font-bold text-base text-esg8 pb-4"> {{ __('Social') }} </p>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 ">
                        <div class="p-3 xl:p-10 border border-esg8/20 pb-4 rounded-md">
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="category2_1" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <canvas id="category2" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <div id="category_value" class="text-esg8 absolute bottom-[68px] w-full text-center text-4xl font-bold">{!! $charts['category2'][0] !!}%</div>
                            </div>
                        </div>

                        <div class="relative xl:p-10">
                            <p class="text-2xl font-bold text-[#2AD2C9] pb-3"> {{ __('Social') }} </p>
                            <p class="text-esg8/60 text-base font-normal"> Práticas ESG sociais voltadas para a diversidade e inclusão podem trazer benefícios significativos para as empresas, através da promoção da justiça social. A adoção dessas práticas sociais voltadas para a diversidade e inclusão deve ser encarada como uma mudança cultural e estratégica, e não apenas como uma ação pontual de marketing. Para isso, é fundamental que as empresas integrem a questão social em seus planos estratégicos e em suas políticas de gestão. </p>
                        </div>
                    </div>
                </div>

                <div class="mt-16 print:mt-20">
                    <div class="flex gap-2  ">
                        @include('icons.categories.3', ['color' => '#FF7500'])
                        <p class="font-bold text-base text-esg8 pb-4"> {{ __('Governança') }} </p>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 ">
                        <div class="p-3 xl:p-10 border border-esg8/20 pb-4 rounded-md">
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="category3_1" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <canvas id="category3" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                                <div id="category_value" class="text-esg8 absolute bottom-[68px] w-full text-center text-4xl font-bold">{!! $charts['category3'][0] !!}%</div>
                            </div>
                        </div>

                        <div class="relative xl:p-10">
                            <p class="text-2xl font-bold text-[#FF7500] pb-3"> {{ __('Governança') }} </p>
                            <p class="text-esg8/60 text-base font-normal"> Entre essas dimensões ESG, a governança corporativa ganha destaque por ser fundamental para a transparência, a prestação de contas e a gestão eficiente das empresas. Essas práticas têm um impacto significativo na redução de riscos e na promoção da ética e da integridade nos negócios. Além disso, a gestão de governança responsável também pode trazer benefícios financeiros para as empresas, como a redução de custos com fraudes e corrupção, a melhoria da imagem institucional e a atração de investidores conscientes. </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-10 text-center">
                    <p class="font-bold text-base text-esg8 pb-4"> {{ __('Please, select questionniare to show charts!') }} </p>
                </div>
            @endif
        </div>
    </div>
</div>