@push('body')
    <style nonce="{{ csp_nonce() }}">
        @media print {
            .pagebreak {
                padding: 0px !important;
                clear: both;
                page-break-after: always;
            }
            div {
                break-inside: avoid;
            }
            .nonavoid {
                break-inside: auto;
            }
            #launcher, #footer {
                visibility: hidden;
            }
            .print {
                page-break-after: avoid;
            }
        }
        @page {
            size: A4 landscape; /* DIN A4 standard, Europe */
            margin: 0 !important;;
            padding: 0 !important;;
            /* margin: 70pt 60pt 70pt; */
        }
    </style>
@endpush

@section('content')
    <div class="px-4 lg:px-0">
        <div class="mt-10 print:hidden">
            <div class="w-full flex justify-between">
                <div class="">
                    <a href="{{ route('tenant.dashboard',  ['questionnaire' => $selectedQuestionnaire->id]) }}"
                        class="text-esg5 w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                        @include('icons.back', [
                            'color' => color(5),
                            'width' => '20',
                            'height' => '16',
                        ])
                        {{ __(' Dashboard') }}
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 print:hidden !border-esg5" @click="window.print()">
                        <x-slot name="buttonicon">
                            @includeFirst([tenant()->views . 'icons.download', 'icons.download'], ['class' => 'inline',
                            'color' => '#FFFFFF'  ])
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Imprimir') }}</span>
                    </x-buttons.btn-icon-text>

                    <x-buttons.btn-icon-text class="!bg-esg4 !text-esg16 border-esg16  print:hidden" @click="location.href='{{ route('tenant.dashboard',  ['questionnaire' => $selectedQuestionnaire->id]) }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="normal-case text-sm font-medium">{{ __('Voltar') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            {{-- Home --}}
            <x-report.pagewithimage url="/images/report/tdp/page1.png">
                <div class="flex p-14 h-full flex-col justify-between">
                    <div class="">
                        @include('icons.logos.cmore')
                    </div>

                    <div class="">
                        <p class="text-7xl text-esg5">2022</p>

                        <p class="text-5xl font-extrabold text-esg8 mt-5 w-6/12">{{ __('Sustainability Report') }}</p>

                        <p class="text-2xl text-esg8 mt-5">{{ __('ESG – Environmental, Social and Governance') }}</p>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 1 --}}
            <x-report.page footerCount="01">
                <div class="py-6">
                    <div class="text-3xl text-esg16 uppercase">{!! __('table of contents') !!}</div>

                    <div class="mt-10">
                        <div class="flex items-center gap-3">
                            <div class="text-3xl text-esg5 font-extrabold">01</div>
                            <div class="text-xl text-esg8">{!! __('Message from the management') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">02</div>
                            <div class="text-xl text-esg8">{!! __('Organisational profile') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">03</div>
                            <div class="text-xl text-esg8">{!! __('Reporting practices') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">04</div>
                            <div class="text-xl text-esg8">{!! __('Strategy') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">05</div>
                            <div class="text-xl text-esg8">{!! __('Contribution to the 2030 Agenda and the Sustainable Development Goals') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">06</div>
                            <div class="text-xl text-esg8">{!! __('Business performance') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">07</div>
                            <div class="text-xl text-esg8">{!! __('Environmental performance') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">08</div>
                            <div class="text-xl text-esg8">{!! __('Social performance') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">09</div>
                            <div class="text-xl text-esg8">{!! __('Governance performance') !!}</div>
                        </div>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="text-3xl text-esg5 font-extrabold">10</div>
                            <div class="text-xl text-esg8">{!! __('Declaration of responsibility') !!}</div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 2 --}}
            <x-report.pagewithimage url="/images/report/tdp/page2.png" footer="true" header="true" footerCount="02">
                <div class="">
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('Message') }}</p>
                    <p class="text-6xl text-esg16 mt-2 uppercase">{{ __('from the') }}</p>
                    <p class="text-6xl font-extrabold mt-2 uppercase text-esg5">{{ __('management') }}</p>
                </div>
            </x-report.page>

            {{-- Page 3 --}}
            <x-report.page footerCount="03" title="{!! __('Message from the management') !!}">
                <div class="grid grid-cols-2 gap-10 py-6">
                    <div class="">
                        <img src="/images/report/tdp/page4.png">
                    </div>
                    <div class="text-sm text-esg8">
                        {!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris. Pellentesque ut lacus nec arcu porta mattis. Vestibulum ut felis gravida, cursus nibh a, cursus diam. In finibus mauris eget lectus porttitor efficitur. Maecenas at tellus ornare, pretium ante id, tristique elit. Suspendisse non elit nec tortor luctus vestibulum. Proin dignissim, nisl vel malesuada mollis, felis dui imperdiet nisl, sed ultrices eros tortor in mi. Duis ultricies urna non orci con.') !!}
                    </div>
                </div>
            </x-report.page>

            {{-- Page 4 --}}
            <x-report.pagewithimage url="/images/report/tdp/page5.png" footer="true" header="true" footerCount="04">
                <div class="">
                    <p class="text-6xl font-extrabold mt-2 uppercase text-esg5">{{ __('Organisational') }}</p>
                    <p class="text-6xl text-esg16 mt-2 uppercase">{{ __('profile') }}</p>
                </div>
            </x-report.page>

            {{-- Page 5 --}}
            <x-report.page title="{!! __('Organisational profile') !!}" footerCount="05">
                <div class="grid grid-cols-3 gap-10 py-6">
                    <div class="">
                        <div class="text-lg font-bold">{!! __('COMPANY OVERVIEW') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Name') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('C-MORE Sustainability LDA') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Activities, brands, products and services') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. ') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold">{!! __('Head office location') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.pt', ['width' => 24, 'height' => 20]) {!! __('Portugal') !!}</div>

                        <div class="text-lg font-bold mt-4">{!! __('Location of operation(s)') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.pt', ['width' => 24, 'height' => 20]) {!! __('Portugal') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.ar', ['width' => 24, 'height' => 20]) {!! __('Andorra') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.be', ['width' => 24, 'height' => 20]) {!! __('Belgium') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.ca', ['width' => 24, 'height' => 20]) {!! __('Canada') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.sp', ['width' => 24, 'height' => 20]) {!! __('Spain') !!}</div>

                        <div class="text-lg font-bold mt-4">{!! __('Type of organisation') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum') !!}</div>

                        <div class="text-lg font-bold mt-4">{!! __('Legal nature of the organisation') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum') !!}</div>

                    </div>

                    <div class="">
                        <div class="text-lg font-bold">{!! __('Served markets') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.pt', ['width' => 24, 'height' => 20]) {!! __('Portugal') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.ar', ['width' => 24, 'height' => 20]) {!! __('Andorra') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.be', ['width' => 24, 'height' => 20]) {!! __('Belgium') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.ca', ['width' => 24, 'height' => 20]) {!! __('Canada') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.sp', ['width' => 24, 'height' => 20]) {!! __('Spain') !!}</div>

                        <div class="text-lg font-bold mt-4">{!! __('Size of the Organisation') !!}</div>

                        <x-cards.card-icon-number text="{!! __('Number of employees and other workers') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.8.workers', ['color' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Number of employees and other workers') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.building', ['color' => color(6), 'width' => 48])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Number of employees and other workers') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.calender-v1', ['color' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 6 --}}
            <x-report.page title="{!! __('Organisational profile') !!}" footerCount="06">
                <div class="grid grid-cols-3 gap-10 py-6">
                    <div class="">
                        <div class="text-lg font-bold">{!! __('Customer service') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Customer profile description') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et.') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold">{!! __('Level of satisfaction on digital platforms') !!}</div>
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !mt-4 !shadow-none !p-0"
                            contentplacement="none">
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Airbnb') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Booking') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Edreams') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Expedia') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Google') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Google My Business') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">@include('icons.checkbox-no')</div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Hotels.com') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('OpenTable') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('The Fork') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Tripadvisor') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Trivago') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/10</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Yelp') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                            <div class="flex justify-between items-center border-b border-b-esg7/40 py-2">
                                <div class="text-sm text-esg8">{!! __('Zomato') !!}</div>
                                <div class="text-base font-bold text-esg8 tracking-widest">4<span class="text-xs text-esg16 font-normal tracking-widest">/5</span></div>
                            </div>
                        </x-cards.card>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold mt-4">{!! __('Number of complaints and praises') !!}</div>
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !mt-4 !shadow-none !p-0 !bg-transparent"
                            contentplacement="none">

                            <x-charts.chartjs id="number_of_complaints_and_praises" class="" x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Complaints'), __('Praises')]) }},
                                        {{ json_encode([ '513', '800' ]) }},
                                        'number_of_complaints_and_praises',
                                        ['{{ color(6) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />

                        </x-cards.card>
                        <img src="/images/report/tdp/page6.png" class="mt-4" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 7 --}}
            <x-report.page title="{!! __('Organisational profile') !!}" footerCount="07">
                <div class="grid grid-cols-3 gap-10 py-6">
                    <div class="">
                        <div class="text-lg font-bold">{!! __('Supply chain') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Characterisation of suppliers - main brands, products and services') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. ') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold">{!! __('Geographical location of suppliers') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.pt', ['width' => 24, 'height' => 20]) {!! __('Portugal') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.ar', ['width' => 24, 'height' => 20]) {!! __('Andorra') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.be', ['width' => 24, 'height' => 20]) {!! __('Belgium') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.ca', ['width' => 24, 'height' => 20]) {!! __('Canada') !!}</div>
                        <div class="text-base text-esg8 mt-4 flex items-center gap-2">@include('icons.flag.sp', ['width' => 24, 'height' => 20]) {!! __('Spain') !!}</div>

                        <x-cards.card-icon-number text="{!! __('Total number of suppliers') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.supplier', ['color' => color(6), 'width' => 48])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('First tier or direct suppliers') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.direct_supplier', ['color' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div class="">
                        <x-cards.card-icon-number text="{!! __('suppliers of reporting units at risk of modern slavery incidents') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.slavery', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('suppliers of reporting units at risk of child labour and exploitation incidents') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">1.234</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.child_labour', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <img src="/images/report/tdp/page7.png" class="mt-4">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 8 --}}
            <x-report.pagewithimage url="/images/report/tdp/page8.png" footer="true" header="true" footerCount="08">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('Reporting') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('practices') }}</p>
                </div>
            </x-report.page>

            {{-- Page 9 --}}
            <x-report.page title="{!! __('Reporting practices') !!}" footerCount="09">
                <div class="grid grid-cols-3 gap-10 py-6">
                    <div class="">
                        <div class="text-lg font-bold">{!! __('Entities included in the report') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('This report includes the activities of the following entities/business units:') !!}</div>
                        <x-list.bullet
                            class="mt-4"
                            bgcolor="bg-esg5"
                            width="w-2"
                            height="h-2"
                            title="{!! __('Company 01') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg5"
                            width="w-2"
                            height="h-2"
                            title="{!! __('Company 02') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg5"
                            width="w-2"
                            height="h-2"
                            title="{!! __('Company 03') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg5"
                            width="w-2"
                            height="h-2"
                            title="{!! __('Company 04') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg5"
                            width="w-2"
                            height="h-2"
                            title="{!! __('Company 05') !!}" />

                        <div class="text-base text-esg8 mt-4">{!! __('For the analysis and support of the indicators, information from the Group`s companies/business units indicated in the table was taken into account, for which there is relevant activity and a majority stake is held.') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold">{!! __('Reporting practices') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('This annual report concerns the results corresponding to the period from January 1, XXXX to December 31, XXXX.') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('For questions about the report, contact XXXXX@XXXX.XXX') !!}</div>
                    </div>

                    <div class="">
                        <img src="/images/report/tdp/page9.png">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 10 --}}
            <x-report.pagewithimage url="/images/report/tdp/page10.png" footer="true" header="true" footerCount="10">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('Strategy') }}</p>
                </div>
            </x-report.page>

            {{-- Page 11 --}}
            <x-report.page title="{!! __('Strategy') !!}" footerCount="11">
                <div class="grid grid-cols-3 gap-10 py-6">
                    <div class="">
                        <div class="text-lg font-bold">{!! __('Main impacts, risks and opportunities') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. ') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold uppercase">{!! __('Stakeholders') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Stakeholder identification') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris. ') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-lg font-bold">{!! __('Stakeholder engagement approach') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris. Pellentesque ut lacus nec arcu porta mattis. Vestibulum ut felis gravida, cursus nibh a, cursus diam. ') !!}</div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 12 --}}
            <x-report.page title="{!! __('Strategy') !!}" footerCount="12">
                <div class="grid grid-cols-3 gap-10 py-6">
                    <div class="">
                        <div class="text-lg font-bold uppercase">{!! __('Materiality') !!}</div>
                        <div class="text-lg font-bold">{!! __('Material issues most relevant to the business') !!}</div>

                        <div class="mt-4 bg-esg2/10 py-1 px-2 uppercase text-sm text-esg2">
                            {!! __('Environment') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Water consumption') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Power management') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('GHG emissions') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Waste management') !!}
                        </div>

                        <div class="bg-esg3/10 py-1 px-2 uppercase text-sm text-esg3">
                            {!! __('Social') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Pay equality') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Qualification of workers') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Health and safety at work') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Reconciliation between professional, personal and family life') !!}
                        </div>
                    </div>

                    <div class="">
                        <div class="bg-esg1/10 py-1 px-2 uppercase text-sm text-esg1">
                            {!! __('Governance') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Legal compliance') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Ethic') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Transparency') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Diversity in the management body') !!}
                        </div>
                        <div class="py-2 px-2 text-sm text-esg8 border-b border-b-esg8/10">
                            {!! __('Risk management') !!}
                        </div>

                        <div class="text-lg font-bold uppercase mt-4">{!! __('Short-, medium- and long-term goals') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Short- and medium-term commitments and targets / Short-, medium- and long-term targets') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit.') !!}</div>
                    </div>

                    <div class="">
                        <div class="text-base text-esg8">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit.') !!}</div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 13 --}}
            <x-report.pagewithimage url="/images/report/tdp/page13.png" footer="true" header="true" footerCount="13">
                <div class="">
                    <p class="text-6xl text-esg16 uppercase">{{ __('Contribution to') }}</p>
                    <p class="text-6xl font-extrabold mt-3 uppercase text-esg5">{{ __('the 2030 Agenda and the SDGs') }}</p>
                </div>
            </x-report.page>

            {{-- Page 14 --}}
            <x-report.page title="{!! __('Contribution to the 2030 Agenda and the SDGs') !!}" footerCount="14">
                <div class="py-6">
                    <div class="font-encodesans text-5xl font-bold">

                        <div class="text-lg font-bold">{!! __('Sustainable Development Goals') !!}</div>

                        <div class="grid grid-cols-9 gap-3 mt-4">
                            <div class="w-full">
                                @include('icons.goals.1', ['class' => 'inline-block', 'color' => $charts['sdg'][1] ? '#EA1D2D' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.2', ['class' => 'inline-block', 'color' => $charts['sdg'][2] ? '#D19F2A' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.3', ['class' => 'inline-block', 'color' => $charts['sdg'][3] ? '#2D9A47' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.4', ['class' => 'inline-block', 'color' => $charts['sdg'][4] ? '#C22033' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.5', ['class' => 'inline-block', 'color' => $charts['sdg'][5] ? '#EF412A' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.6', ['class' => 'inline-block', 'color' => $charts['sdg'][6] ? '#00ADD8' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.7', ['class' => 'inline-block', 'color' => $charts['sdg'][7] ? '#FDB714' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.8', ['class' => 'inline-block', 'color' => $charts['sdg'][8] ? '#8F1838' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.9', ['class' => 'inline-block', 'color' => $charts['sdg'][9] ? '#F36E24' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.10', ['class' => 'inline-block', 'color' => $charts['sdg'][10] ? '#E01A83' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.11', ['class' => 'inline-block', 'color' => $charts['sdg'][11] ? '#F99D25' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.12', ['class' => 'inline-block', 'color' => $charts['sdg'][12] ? '#CD8B2A' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.13', ['class' => 'inline-block', 'color' => $charts['sdg'][13] ? '#48773C' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.14', ['class' => 'inline-block', 'color' => $charts['sdg'][14] ? '#007DBB' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.15', ['class' => 'inline-block', 'color' => $charts['sdg'][15] ? '#40AE49' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.16', ['class' => 'inline-block', 'color' => $charts['sdg'][16] ? '#00558A' : '#DCDCDC'])
                            </div>

                            <div class="w-full">
                                @include('icons.goals.17', ['class' => 'inline-block', 'color' => $charts['sdg'][17] ? '#1A3668' : '#DCDCDC'])
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 14 - 1 --}}
            <x-report.page title="{!! __('Contribution to the 2030 Agenda and the SDGs') !!}" footerCount="14.1">
                <div class="py-6">
                    <x-report.table.table class="!border-none !bg-none" bgnone="true">
                        <x-report.table.tr class="!p-1">
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20 min-w-[200px]">{!! __('Goal') !!}</x-report.table.td>
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20">{!! __('Description') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('01 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('02 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('03 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                    </x-report.table.table>
                </div>
            </x-report.page>

            {{-- Page 14 - 2 --}}
            <x-report.page title="{!! __('Contribution to the 2030 Agenda and the SDGs') !!}" footerCount="14.1">
                <div class="py-6">
                    <x-report.table.table class="!border-none !bg-none" bgnone="true">
                        <x-report.table.tr class="!p-1">
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20 min-w-[200px]">{!! __('Goal') !!}</x-report.table.td>
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20">{!! __('Description') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('04 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('05 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('06 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                    </x-report.table.table>
                </div>
            </x-report.page>

            {{-- Page 14 - 3 --}}
            <x-report.page title="{!! __('Contribution to the 2030 Agenda and the SDGs') !!}" footerCount="14.1">
                <div class="py-6">
                    <x-report.table.table class="!border-none !bg-none" bgnone="true">
                        <x-report.table.tr class="!p-1">
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20 min-w-[200px]">{!! __('Goal') !!}</x-report.table.td>
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20">{!! __('Description') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('07 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('08 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('09 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                    </x-report.table.table>
                </div>
            </x-report.page>

            {{-- Page 14 - 3 --}}
            <x-report.page title="{!! __('Contribution to the 2030 Agenda and the SDGs') !!}" footerCount="14.1">
                <div class="py-6">
                    <x-report.table.table class="!border-none !bg-none" bgnone="true">
                        <x-report.table.tr class="!p-1">
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20 min-w-[200px]">{!! __('Goal') !!}</x-report.table.td>
                            <x-report.table.td class="!p-1 text-left border-b border-b-esg5 bg-esg7/20">{!! __('Description') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('10 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('11 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td>{!! __('12 - Lorem ipsum dolor sit amet') !!}</x-report.table.td>
                            <x-report.table.td>{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris.') !!}</x-report.table.td>
                        </x-report.table.tr>
                    </x-report.table.table>
                </div>
            </x-report.page>


            {{-- Page 15 --}}
            <x-report.pagewithimage url="/images/report/tdp/page15.png" footer="true" header="true" footerCount="15">
                <div class="">
                    <p class="text-6xl font-extrabold mt-3 uppercase text-esg5">{{ __('Business') }}</p>
                    <p class="text-6xl text-esg16 uppercase">{{ __('Performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 16 --}}
            <x-report.page title="{!! __('Business Performance') !!}" footerCount="16">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div >
                        <div class="text-lg font-bold">{!! __('Direct economic value generated') !!}</div>
                        <x-cards.card-icon-number text="{!! __('total of Direct economic value generated') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <div class="text-lg font-bold mt-4">{!! __('Accumulated economic value') !!}</div>
                        <x-cards.card-icon-number text="{!! __('total of Accumulated economic value') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.salary', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Economic value distributed') !!}</div>
                        <x-cards.card-icon-number text="{!! __('total of Economic value distributed') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.salary', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-report.table.table class="!border-t-esg5">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Operational expenses') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Expenses with employee salaries and benefits') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Expenses with capital suppliers') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Expenses with the State and other public entities') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Expenditure on community investments') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        <div class="text-lg font-bold mt-4">{!! __('Investment expenditure') !!}</div>
                        <x-cards.card-icon-number text="{!! __('total of Investment expenditure') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <x-report.table.table class="!border-t-esg5">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Innovation') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Digitalisation and cybersecurity') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Research and Development') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Environmental protection') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Territorial development') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        <div class="text-lg font-bold mt-4">{!! __('Investment expenditure') !!}</div>
                        <x-cards.card-icon-number text="{!! __('total of Financial support received from the State') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-report.table.table class="!border-t-esg5">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Source/Entity') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Amount') !!}</x-report.table.td>
                                <x-report.table.td>€ 0.000,00</x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 17 --}}
            <x-report.page title="{!! __('Business Performance') !!}" footerCount="17">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('European Union Taxonomy') !!}</div>
                        <x-cards.card-icon-number text="{!! __('Eligible turnover') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.salary', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Eligible CapEx') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.capx', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Eligible OpEx') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.capx', ['fill' => color(6)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                    </div>
                    <div class="col-span-2">
                        <img src="/images/report/tdp/page17.png">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 18 --}}
            <x-report.pagewithimage url="/images/report/tdp/page18.png" footer="true" header="true" footerCount="18" footerborder="border-t-esg2">
                <div class="">
                    <p class="text-6xl font-extrabold mt-3 uppercase text-esg2">{{ __('environmental') }}</p>
                    <p class="text-6xl text-esg16 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 19 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="19" footerborder="border-t-esg2">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Framework') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris. Pellentesque ut lacus nec arcu porta mattis. Vestibulum ut felis gravida, cursus nibh a, cursus diam. In finibus mauris eget lectus porttitor efficitur. Maecenas at tellus ornare, pretium ante id, tristique elit. Suspendisse non elit nec tortor luctus vestibulum. Proin dignissim, nisl vel malesuada mollis, felis dui imperdiet nisl, sed ultrices eros tortor in mi. Duis ultricies urna non orci con.') !!}</div>
                    </div>

                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Water consumption') !!}</div>
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="water_consumption"
                                class="m-auto relative !h-full !w-full"
                                unit="{{ 'm3' }}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Acquired from third parties'), __('From water resources (sea/river)'), __('Borehole / well water')]) }},
                                        {{ json_encode([ '513', '613', '800' ]) }},
                                        'water_consumption',
                                        ['{{ color(2) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        <x-cards.card-icon-number text="{!! __('Water consumption per customer') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">m3/client</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.water', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Variation in water consumption') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.water', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Water efficiency measures') !!}</div>
                        <div class="mt-4">
                            <x-list.bullet
                                bgcolor="bg-esg2"
                                title="{!! __('Replacement of taps, shower systems, flow meters, economizers, toilet cisterns and other efficient products;') !!}" />

                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Replacement of washing equipment, such as washing machines and dishwashers, with more efficient equipment;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Maintenance interventions and prevention of leaks in the water supply and distribution network;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Implementation of partial meters per building and/or types of water use;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Rainwater harvesting systems;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Installation of infrastructure that allows new water sources to be used;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Installation of distribution infrastructures between WWTP and golf courses that allow the use of water for reuse in the aforementioned establishments.') !!}" />
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 20 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="20" footerborder="border-t-esg2">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold ">{!! __('Waste water discharges') !!}</div>

                        <x-report.table.table class="!border-t-esg2">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Reporting units with WWTP') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Disposal of wastewater in the aquatic environment (river, stream, sea)') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Disposal of wastewater in the soil') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox-no', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Disposal of wastewater in other medium') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Wastewater treatment plants (WWTPs)') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox-no', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        <div class="text-lg font-bold mt-4 uppercase">{!! __('Energy management') !!}</div>
                        <x-cards.card-icon-number text="{!! __('Energy consumption') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kWh</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Energy intensity') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">MWh / €</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <x-cards.card-icon-number text="{!! __('Carbon intensity') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base"> tCO2e / €</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.emission', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <div class="text-lg font-bold mt-4">{!! __('Proportion of energy consumption by renewable and non-renewable sources') !!}</div>
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.chartjs id="energy_consumption" class="" width="350"
                                height="150" x-init="$nextTick(() => {
                                    tenantDoughnutChart(
                                        ['{{ __('Renewable') }}', '{{ __('Non-renewable') }}'],
                                        [51.25, 153.75],
                                        'energy_consumption',
                                        ['#008131', '#66CE03'], {
                                            showTooltips: true, // toogle tolltips visibility
                                            percentagem: true, // Add the percentage value to the labels
                                            legend: {
                                                display: true,  // toogle legend visibility
                                                position: 'bottom', // show the legend on the right side
                                            },
                                            plugins: {
                                                legendOnCenter: true,  // custom plugin to show a number at the center of the chart
                                            }
                                        }
                                    );
                                });" />
                        </x-cards.card>
                    </div>

                    <div>
                        <x-cards.card-icon-number text="{!! __('Energy consumption per customer ') !!}">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">MWh/cliente</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <x-cards.card-icon-number text="{!! __('Variability in energy consumption') !!}" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <div class="text-lg font-bold mt-4">{!! __('Energy Efficiency Measures') !!}</div>
                        <div class="mt-4">
                            <x-list.bullet
                                bgcolor="bg-esg2"
                                title="{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in dignissim lore;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Nunc finibus tincidunt mollis. In pretium justo vitae magna vestibulum tristique;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Vivamus rutrum ante eget auctor ullamcorper. Sed ac tempus tortor;') !!}" />
                            <x-list.bullet
                                class="mt-2"
                                bgcolor="bg-esg2"
                                title="{!! __('Morbi sed nisi ut urna ultricies convallis non vel enim. Ut cursus interdum pellentesque.') !!}" />
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 21 --}}
            <x-report.page title="{{ __('environmental performance') }}" footerCount="21" footerborder="border-t-esg2" bgimage="/images/report/tdp/page21.png">
                <div class="grid grid-cols-3 gap-6 py-6 mb-40">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Atmospheric emissions') !!}</div>
                        <x-report.table.table class="!border-t-esg2">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('GHG emissions monitoring') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Carbon sequestration capacity') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Monitoring air pollutant emissions') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox-no', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        <x-cards.card-icon-number text="{!! __('Carbon intensity') !!}" class="items-center" class="!mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base"> kgCO2e / €</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.emission', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('GHG emissions by category') !!}</div>
                        {{-- Chart: GHG emissions by category --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Scope 1')],
                                    [ 'color' => 'bg-[#66CE03]', 'text' => __('Scope 2')],
                                    [ 'color' => 'bg-[#98BDA6]', 'text' => __('Scope 3')]
                                ]);

                            $subinfo = json_encode([
                                ['value' => '2.064,13', 'unit' => 't CO2 eq'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            subpoint="{{ $subpoint }}"
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="emissions_by_category"
                                class="m-auto relative !h-full !w-full"
                                unit="{{ 't CO2 eq' }}"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Scope 1'), __('Scope 2'), __('Scope 3')]) }},
                                        {{ json_encode([ '513.10', '751.03', '800.00' ]) }},
                                        'emissions_by_category',
                                        ['#008131', '#66CE03', '#98BDA6'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"
                                subinfo="{{ $subinfo }}" />
                        </x-cards.card>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Air pollutant emissions') !!}</div>
                        {{-- Chart: air pollutant emissions --}}
                        @php
                            $subinfo = json_encode([
                                ['value' => '2.064,13', 'unit' => 't'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                        <x-charts.bar id="air_pollutant_emissions"
                            class="m-auto relative !h-full !w-full"
                            unit="{{ 't' }}"
                            x-init="$nextTick(() => {
                                tenantBarChart(
                                    {{ json_encode([ __('SO2'), __('NOx'), __('NMVOC'), __('PM2,5'), __('PM2,5'), __('Heavy Metals'), __('O3 depleting substances')]) }},
                                    {{ json_encode([ '800.00','800.00','800.00','800.00','800.00','800.00','800.00' ]) }},
                                    'air_pollutant_emissions',
                                    ['#008131'],
                                    null,
                                    {
                                        showTitle: true,
                                        simplifiedGrid: true
                                    }
                                );
                            });"
                            subinfo="{{ $subinfo }}" />
                        </x-cards.card>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 22 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="22" footerborder="border-t-esg2">
            <div class="grid grid-cols-3 gap-6 py-6">
                <div class="col-span-2">
                    <div class="w-6/12">
                        <div class="text-lg font-bold uppercase">{!! __('Pressure on biodiversity') !!}</div>
                        <x-report.table.table class="!border-t-esg2">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Environmental impact study') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Location in an environmental protection area or an area of high biodiversity value') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>

                    <div class="flex items-center gap-2 mt-8">
                        <div class="px-20">
                            @include(tenant()->views .'icons.map')
                        </div>
                        <div class="grow">
                            <x-tables.table class="!min-w-full">
                                <x-slot name="thead">
                                    <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Location') }}</x-tables.th>
                                    <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Protected Area') }}</x-tables.th>
                                    <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Type') }}</x-tables.th>
                                </x-slot>
                                <x-tables.tr>
                                    <x-tables.td>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-2">
                                                <div class="bg-[#FF3E3A] w-2 h-2 rounded-full mt-1.5"></div>
                                            </div>
                                            <div class="text-sm text-esg8 grow">
                                                {!! __('Location Name 01') !!}
                                            </div>
                                        </div>
                                    </x-tables.td>
                                    <x-tables.td>{!! __('Serra da Estrela') !!}</x-tables.td>
                                    <x-tables.td>{!! __('Natural Park') !!}</x-tables.td>
                                </x-tables.tr>
                                <x-tables.tr>
                                    <x-tables.td>
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-2">
                                                <div class="bg-[#FBC02D] w-2 h-2 rounded-full mt-1.5"></div>
                                            </div>
                                            <div class="text-sm text-esg8 grow">
                                                {!! __('Location Name 02') !!}
                                            </div>
                                        </div>
                                    </x-tables.td>
                                    <x-tables.td>{!! __('Albufeira do Azibo') !!}</x-tables.td>
                                    <x-tables.td>{!! __('Regional Protected Landscape') !!}</x-tables.td>
                                </x-tables.tr>
                            </x-tables.table>
                        </div>
                    </div>
                </div>

                <div>
                    <img src="/images/report/tdp/page22.png">
                </div>
            </div>
            </x-report.page>

            {{-- Page 23 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="23" footerborder="border-t-esg2">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Climate risks by geolocation') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Physical risks') !!}</div>
                        <x-report.table.table class="!border-t-esg2">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Contingency plan') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Continuity plan') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>

                    <div>
                        @include(tenant()->views . 'icons.p1')
                    </div>

                    <div>
                        @include(tenant()->views . 'icons.p2')
                    </div>
                </div>
            </x-report.page>

            {{-- Page 24 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="24" footerborder="border-t-esg2">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold mt-4">{!! __('Transition risks') !!}</div>
                        {{-- Icon: Atmospheric emissions --}}
                        <x-cards.card-icon-number text="{!! __('Atmospheric emissions') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">tCO<sub>2</sub></span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.emission', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Investment in Research and Development --}}
                        <x-cards.card-icon-number text="{!! __('Investment in Research and Development') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        {{-- Chart | BAR : Energy costs --}}
                        @php
                            $subinfo = json_encode([
                                ['value' => '1.313,00', 'unit' => '€'],
                        ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Energy costs') !!}"
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !bg-transparent"
                            contentplacement="none">

                        <x-charts.bar id="energy_costs"
                            class="m-auto relative !h-full !w-full"
                            unit="{{ '€' }}"
                            x-init="$nextTick(() => {
                                tenantBarChart(
                                    {{ json_encode([ __('Electricity'), __('Fuel')]) }},
                                    {{ json_encode([ '513', '800' ]) }},
                                    'energy_costs',
                                    ['#008131'],
                                    null,
                                    {
                                        showTitle: true,
                                        simplifiedGrid: true
                                    }
                                );
                            });"
                            subinfo="{{ $subinfo }}" />
                        </x-cards.card>
                    </div>

                    <div>
                        <img src="/images/report/tdp/page24.png">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 25 --}}
            <x-report.page title="{{ __('environmental performance') }}" footerCount="25" footerborder="border-t-esg2" bgimage="/images/report/tdp/page25.png">
                <div class="grid grid-cols-3 gap-6 pb-44 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Waste management') !!}</div>
                        <x-report.table.table class="!border-t-esg2 bg-esg4">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Monitor the non-hazardous waste generated') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Monitor the non-hazardous waste generated') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        {{-- Icon: Proportion of waste sent for recycling --}}
                        <x-cards.card-icon-number text="{!! __('Proportion of waste sent for recycling') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.recicle-residue', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Quantity of hazardous waste generated  --}}
                        <x-cards.card-icon-number text="{!! __('Quantity of hazardous waste generated') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.radioactive-residue', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div class="col-span-2">
                        <div class="text-lg font-bold uppercase">{!! __('non-hazardous waste generated by type') !!}</div>
                        {{-- Chart | BAR : non-hazardous waste generated by type --}}
                        @php
                            $subinfo = json_encode([
                                ['value' => '00.000', 'unit' => 'kg'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !bg-transparent"
                            contentplacement="none">

                        <x-charts.bar id="non-hazardous_waste_generated"
                            class="m-auto relative !h-full !w-full"
                            unit="{{ 'kg' }}"
                            x-init="$nextTick(() => {
                                tenantBarChart(
                                    {{ json_encode([ __('Paper/card box'), __('Plastic/metal'), __('Glass'), __('Organic'), __(' Undifferentiated'), __('Construction and demolition'), __('Health care')]) }},
                                    {{ json_encode([ '800', '800', '800', '800', '800', '800', '800' ]) }},
                                    'non-hazardous_waste_generated',
                                    ['#008131'],
                                    null,
                                    {
                                        showTitle: true,
                                        simplifiedGrid: true
                                    }
                                );
                            });"
                            subinfo="{{ $subinfo }}" />
                        </x-cards.card>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 26 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="26" footerborder="border-t-esg2">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Circular economy') !!}</div>
                        <x-report.table.table class="!border-t-esg2 bg-esg4">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Monitoring food waste') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Circular economy measures') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        {{-- Icon: Quantity of reused materials  --}}
                        <x-cards.card-icon-number text="{!! __('Quantity of reused materials') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.recicle-residue', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: food made available and meals cooked  --}}
                        <x-cards.card-icon-number text="{!! __('food made available and meals cooked') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.iftar', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: food and meals wasted   --}}
                        <x-cards.card-icon-number text="{!! __('food and meals wasted ') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.residues', ['color' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        {{-- Icon: food and meals redistributed  --}}
                        <x-cards.card-icon-number text="{!! __('food and meals redistributed') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">kg</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.food-donation', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: cooking oil put into recycling  --}}
                        <x-cards.card-icon-number text="{!! __('cooking oil put into recycling') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base">l</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.oil', ['fill' => color(2)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        <div class="text-lg font-bold mt-4">{!! __('Circular economy measures') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. ') !!}</div>
                    </div>

                    <div>
                        <img src="/images/report/tdp/page26.png">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 27 --}}
            <x-report.page title="{!! __('environmental performance') !!}" footerCount="27" footerborder="border-t-esg2">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Environmental policies') !!}</div>
                        <x-report.table.table class="!border-t-esg2 bg-esg4">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Environmental policy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Emissions reduction policy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Biodiversity protection policy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Waste treatment and/or reduction strategy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(2)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>

                    <div class="col-span-2">
                        <img src="/images/report/tdp/page27.png">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 28 --}}
            <x-report.pagewithimage url="/images/report/tdp/page28.png" footer="true" header="true" footerCount="28" footerborder="border-t-esg1">
                <div class="">
                    <p class="text-6xl font-extrabold mt-3 uppercase text-esg1">{{ __('social') }}</p>
                    <p class="text-6xl text-esg16 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 29 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="29" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Framework') !!}</div>
                        <div class="text-lg font-bold uppercase mt-4">{!! __('Contracting Model') !!}</div>

                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. ') !!}</div>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Employees by type of working hours') !!}</div>
                        {{-- Chart | BAR : Employees by type of working hours --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employees_working_hours"
                                class="m-auto relative !h-full !w-full"
                                data-json
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Full-time'), __('Part-time'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20])}},
                                        'employees_working_hours',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card-dashboard-version1-withshadow>

                        <div class="text-lg font-bold mt-4">{!! __('Employees by type of labour contract') !!}</div>
                        {{-- Chart | BAR : Employees by type of labour contract --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employees_labour_contract"
                                class="m-auto relative !h-full !w-full"
                                data-json
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Fixed-term'), __('No term'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20]) }},
                                        'employees_labour_contract',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('New hires by type of employment contract') !!}</div>
                        {{-- Chart | BAR : New hires by type of employment contract --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employees_local_labour_contract"
                                class="m-auto relative !h-full !w-full"
                                data-json
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Fixed-term'), __('No term'), __('Other')]) }},
                                        {{ json_encode([20, 20, 20]) }},
                                        'employees_local_labour_contract',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 30 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="30" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        {{-- Icon: Net job creation --}}
                        <x-cards.card-icon-number text="{!! __('Net job creation') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.id-card', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Average turnover rate --}}
                        <x-cards.card-icon-number text="{!! __('Average turnover rate') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.community-program', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Average rate of absenteeism --}}
                        <x-cards.card-icon-number text="{!! __('Average rate of absenteeism') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.work', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Employee benefits') !!}</div>

                        <x-list.bullet
                            class="mt-4"
                            bgcolor="bg-esg1"
                            width="w-2"
                            height="h-2"
                            textcolor="text-esg8"
                            fontsize="text-sm"
                            title="{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in dignissim lore;') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg1"
                            width="w-2"
                            height="h-2"
                            textcolor="text-esg8"
                            fontsize="text-sm"
                            title="{!! __('Nunc finibus tincidunt mollis. In pretium justo vitae magna vestibulum tristique;') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg1"
                            width="w-2"
                            height="h-2"
                            textcolor="text-esg8"
                            fontsize="text-sm"
                            title="{!! __('Vivamus rutrum ante eget auctor ullamcorper. Sed ac tempus tortor;') !!}" />
                        <x-list.bullet
                            class="mt-2"
                            bgcolor="bg-esg1"
                            width="w-2"
                            height="h-2"
                            textcolor="text-esg8"
                            fontsize="text-sm"
                            title="{!! __('Morbi sed nisi ut urna ultricies convallis non vel enim. Ut cursus interdum pellentesque.') !!}" />

                    </div>

                    <div>
                        <img src="/images/report/tdp/page30.png">
                    </div>
                </div>
            </x-report.page>

            {{-- Page 31 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="31" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Equal Pay') !!}</div>

                        <div class="text-lg font-bold mt-4">{!! __('Average basic salary ratio, by job category and gender') !!}</div>
                        {{-- Chart | BAR : Average basic salary ratio, by funcional category --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="basic_salary_ratio"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'basic_salary_ratio',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        <div class="text-lg font-bold mt-4">{!! __('Average basic salary ratio, by gender') !!}</div>
                        {{-- Chart | BAR : Average basic salary ratio, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                        <x-charts.bar id="basic_salary_ratio_gender"
                            class="m-auto relative !h-full !w-full"
                            unit="€"
                            x-init="$nextTick(() => {
                                tenantBarChart(
                                    {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                    {{ json_encode([80, 60, 40]) }},
                                    'basic_salary_ratio_gender',
                                    ['{{ color(1) }}'],
                                    null,
                                    {
                                        showTitle: true,
                                        simplifiedGrid: true
                                    }
                                );
                            });"/>
                        </x-cards.card>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Gross pay ratio, by functional category') !!}</div>
                        {{-- Chart | BAR : Gross pay ratio, by functional category --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="gross_pay_ratio"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'gross_pay_ratio',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        <div class="text-lg font-bold mt-4">{!! __('Gross pay ratio, by gender') !!}</div>
                        {{-- Chart | BAR : Gross pay ratio, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="gross_pay_gender"
                                class="m-auto relative !h-full !w-full"
                                unit="€"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'gross_pay_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Proportion of employees with a basic salary above the national minimum wage, by gender') !!}</div>
                        {{-- Chart | BAR : Proportion of employees with a basic salary above the national minimum wage, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employee_basic_salary_national"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'employee_basic_salary_national',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 32 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="32" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div><img src="/images/report/tdp/page32.png" /></div>
                    <div>
                        <div class="text-lg font-bold mt-4">{!! __('Ratio between the lowest salary by gender, compared to the national minimum wage') !!}</div>
                        {{-- Chart | BAR : Ratio between the lowest salary by gender, compared to the national minimum wage --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employee_basic_salary_national_minimum"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'employee_basic_salary_national_minimum',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Ratio between the entry wage and the national minimum wage, by gender') !!}</div>
                        {{-- Chart | BAR : Ratio between the entry wage and the national minimum wage, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employee_basic_ration_between_salary_national_minimum"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'employee_basic_ration_between_salary_national_minimum',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 33 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="33" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">

                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Workforce Diversity') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Number of employees by age') !!}</div>
                        {{-- Chart | BAR : number of employees by age --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="number_employees_age"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('< 30'), __('30 - 50'), __('> 50')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'number_employees_age',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        <div class="text-lg font-bold mt-4">{!! __('Number of employees by functional category') !!}</div>
                        {{-- Chart | BAR : number of employees by functional category --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="number_employees_category"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Technicians')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'number_employees_category',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Number of employees by gender') !!}</div>
                        {{-- Chart | BAR : number of employees by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="number_employees_gender"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'number_employees_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>

                        {{-- Icon: Proportion of employees of foreign nationality --}}
                        <x-cards.card-icon-number text="{!! __('Proportion of employees of foreign nationality') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000 <span class="text-base"> %</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.planet', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div><img src="/images/report/tdp/page33.png" /></div>
                </div>
            </x-report.page>

            {{-- Page 34 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="34" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Workers Qualifications') !!}</div>
                        {{-- Icon: Training hours undertaken --}}
                        <x-cards.card-icon-number text="{!! __('Training hours undertaken') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.qualification', ['color' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Investment in training, per employee --}}
                        <x-cards.card-icon-number text="{!! __('Investment in training, per employee') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.qualification', ['color' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Partnerships with universities, vocational schools, study centres or others --}}
                        <x-cards.card-icon-number text="{!! __('Partnerships with universities, vocational schools, study centres or others') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000</div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.course', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Average number of training hours per employee, by gender') !!}</div>
                        {{-- Chart | BAR : Average number of training hours per employee, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                        <x-charts.bar id="avg_training_hours"
                            class="m-auto relative !h-full !w-full"
                            unit="{!! __('hours') !!}"
                            x-init="$nextTick(() => {
                                tenantBarChart(
                                    {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                    {{ json_encode([20, 20, 20])}},
                                    'avg_training_hours',
                                    ['#006CB7'],
                                    null,
                                    {
                                        showTitle: true,
                                        simplifiedGrid: true
                                    }
                                );
                            });" />
                        </x-cards.card>

                        <div class="text-lg font-bold mt-4">{!! __('Number of employees who have received training, by functional category') !!}</div>
                        {{-- Chart | BAR : Number of employees who have received training, by functional category --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="number_training_received"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Management'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20])}},
                                        'number_training_received',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Percentage of employees who receive regular performance and career development appraisals, by job category') !!}</div>
                        {{-- Chart | BAR : Percentage of employees who receive regular performance and career development appraisals, by job category --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="performance_and_career_development"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Administration'), __('Administration'), __('Technicians'), __('Administrative')]) }},
                                        {{ json_encode([80, 60, 40, 20]) }},
                                        'performance_and_career_development',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 35 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="35" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold">{!! __('Percentage of employees who receive regular performance and career development appraisals, by gender') !!}</div>
                        {{-- Chart | BAR : Percentage of employees who receive regular performance and career development appraisals, by gender --}}
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="performance_and_career_development_gender"
                                class="m-auto relative !h-full !w-full"
                                unit="%"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([80, 60, 40]) }},
                                        'performance_and_career_development_gender',
                                        ['{{ color(1) }}'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });"/>
                        </x-cards.card>
                    </div>

                    <div class="col-span-2">
                        <img src="/images/report/tdp/page35.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 36 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="36" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Occupational health and safety') !!}</div>
                        {{-- Icon: number of hours of occupational health and safety training --}}
                        <x-cards.card-icon-number text="{!! __('number of hours of occupational health and safety training') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('hours') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.qualification', ['color' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: percentage of positions with a risk assessment carried out --}}
                        <x-cards.card-icon-number text="{!! __('percentage of positions with a risk assessment carried out') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base"> %</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.caution', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Work-related accidents --}}
                        <x-cards.card-icon-number text="{!! __('Work-related accidents') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('accidents') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.employee', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        {{-- Icon: Number of working days lost --}}
                        <x-cards.card-icon-number text="{!! __('Number of working days lost') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('days') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.employee', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Number of days off work --}}
                        <x-cards.card-icon-number text="{!! __('Number of days off work') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">{!! __('days') !!}</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.money-bills', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>

                    <div>
                        <img src="/images/report/tdp/page36.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 37 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="37" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Reconciling professional, personal and family life') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Working time modalities') !!}</div>

                        <x-list.bullet class="mt-4"  bgcolor="bg-esg1" title="{!! __('Adaptability') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Bank hours') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Concentrated work schedule') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Exemption from working hours') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Shift work') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Night work') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Extra work') !!}" />

                        <div class="text-lg font-bold mt-4">{!! __('Measures to promote conciliation') !!}</div>
                        <x-list.bullet class="mt-4"  bgcolor="bg-esg1" title="{!! __('Flexible/adaptable working hours') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Rotating shifts') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Teleworking') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Childbirth allowance') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Child benefit') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Childcare ticket') !!}" />
                        <x-list.bullet bgcolor="bg-esg1" title="{!! __('Co-payment of tuition fees for employees children') !!}" />
                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Parental leave and return to work and retention') !!}</div>

                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees who started parental leave') !!}"
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employye_parental_leave"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'employye_parental_leave',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>
                    </div>

                    <div>
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees returning to work after parental leave') !!}"
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employye_returning_parental_leave"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'employye_returning_parental_leave',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>


                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Number of employees returning to work after parental leave and remaining with the company after 12 months') !!}"
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="employye_returning_parental_leave_12month"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'employye_returning_parental_leave_12month',
                                        ['#006CB7'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                    </div>
                </div>
            </x-report.page>

            {{-- Page 38 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="38" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        {{-- Icon: Return to work and retention rates after parental leave --}}
                        <x-cards.card-icon-number text="{!! __('Return to work and retention rates after parental leave') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.freelance', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Return to work rate (after leave) --}}
                        <x-cards.card-icon-number text="{!! __('Return to work rate (after leave)') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.employment', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Retention rate (12 months after returning to work from leave) --}}
                        <x-cards.card-icon-number text="{!! __('Retention rate (12 months after returning to work from leave)') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.absenteeism', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                    </div>

                    <div class="col-span-2">
                        <img src="/images/report/tdp/page38.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 39 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="39" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div class="col-span-2">
                        <img src="/images/report/tdp/page39.png" />
                    </div>
                    <div>
                        {{-- Icon: Investment in operations with local community involvement --}}
                        <x-cards.card-icon-number text="{!! __('Investment in operations with local community involvement') !!}" class="items-center">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include(tenant()->views . 'icons.investment', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Local development programmes --}}
                        <x-cards.card-icon-number text="{!! __('Local development programmes') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.community-program', ['color' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Proportion of local purchases --}}
                        <x-cards.card-icon-number text="{!! __('Proportion of local purchases') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">%</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include('icons.dashboard.cart', ['fill' => color(1)])
                            </x-slot:icon>
                        </x-cards.card-icon-number>

                        {{-- Icon: Amount spent on purchases of local products --}}
                        <x-cards.card-icon-number text="{!! __('Amount spent on purchases of local products') !!}" class="items-center !mt-4">
                            <x-slot:data>
                                <div class="text-4xl text-esg8">00.000<span class="text-base">€</span></div>
                            </x-slot:data>
                            <x-slot:icon>
                                @include( tenant()->views . 'icons.supplier', ['color' => color(1), 'width' => 48])
                            </x-slot:icon>
                        </x-cards.card-icon-number>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 40 --}}
            <x-report.page title="{!! __('social performance') !!}" footerCount="40" footerborder="border-t-esg1">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Social policies') !!}</div>
                        <x-report.table.table class="!border-t-esg1">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Human rights policy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(1)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Supplier policy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(1)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Remuneration policy') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(1)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>
                    <div class="col-span-2">
                        <img src="/images/report/tdp/page40.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 41 --}}
            <x-report.pagewithimage url="/images/report/tdp/page41.png" footer="true" header="true" footerCount="41" footerborder="border-t-esg3">
                <div class="">
                    <p class="text-6xl font-extrabold mt-3 uppercase text-esg3">{{ __('governance') }}</p>
                    <p class="text-6xl text-esg16 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 42 --}}
            <x-report.page title="{!! __('governance performance') !!}" footerCount="42" footerborder="border-t-esg3">
                <div class="grid grid-cols-3 gap-6 pt-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Framework') !!}</div>
                        <div class="text-base text-esg8 mt-4">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget.') !!}</div>
                    </div>

                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Legal compliance') !!}</div>
                        <x-report.table.table class="!border-t-esg3">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Formal internal mechanisms used to ensure compliance with the legal requirements applicable to the activity') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(3)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>

                        <div class="text-lg font-bold uppercase mt-4">{!! __('ethics') !!}</div>
                        <x-report.table.table class="!border-t-esg3">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Code of Ethics') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(3)]) </x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('World Tourism Code of Ethics') !!}</x-report.table.td>
                                <x-report.table.td> @include('icons.checkbox', ['color' =>  color(3)]) </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>

                    <div>
                        <div class="text-lg font-bold uppercase mt-4">{!! __('Transparency') !!}</div>
                        <div class="text-base font- mt-4">{!! __('Means by which sustainability performance is communicated') !!}</div>
                        <x-list.bullet bgcolor="bg-esg3" class="mt-4" title="{!! __('Report disseminated internally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Report disseminated internally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('E-mail disseminated internally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('E-mail disseminated externally') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('Internal publication/message') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" class="mt-2" title="{!! __('External publication/message') !!}" />
                    </div>
                </div>
                <div class="grid grid-cols-3 mt-6 gap-6 pb-6">
                    <div></div>
                    <div class="col-span-2">
                        <img src="/images/report/tdp/page42.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 43 --}}
            <x-report.page title="{!! __('governance performance') !!}" footerCount="43" footerborder="border-t-esg3">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Diversity on the governance body') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Composition of the governance body, by gender and age group') !!}</div>

                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="composition_the_governance_body"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'composition_the_governance_body',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>

                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="non-executive_the_governance_body_age"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('Man'), __('Woman'), __('Other')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'non-executive_the_governance_body_age',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>
                    </div>

                    <div>
                        <div class="text-lg font-bold mt-4">{!! __('Number of non-executive members of the governance body, by gender') !!}</div>
                        <x-cards.card-dashboard-version1-withshadow
                            titleclass="!text-sm"
                            class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                            contentplacement="none">

                            <x-charts.bar id="composition_the_governance_body_age"
                                class="m-auto relative !h-full !w-full"
                                x-init="$nextTick(() => {
                                    tenantBarChart(
                                        {{ json_encode([ __('<30'), __('30-50'), __('>50')]) }},
                                        {{ json_encode([53, 80, 63])}},
                                        'composition_the_governance_body_age',
                                        ['#E19B00'],
                                        null,
                                        {
                                            showTitle: true,
                                            simplifiedGrid: true
                                        }
                                    );
                                });" />
                        </x-cards.card>
                    </div>

                    <div>
                        <img src="/images/report/tdp/page43.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 44 --}}
            <x-report.page title="{!! __('governance performance') !!}" footerCount="44" footerborder="border-t-esg3">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Due diligence in the supply chain') !!}</div>
                        <div class="text-lg font-bold mt-4">{!! __('Risks arising from the supply chain') !!}</div>

                        <x-list.bullet bgcolor="bg-esg3" class="mt-4" title="{!! __('Use of child labor and other situations of human rights abuse') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Unsafe working conditions') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Disregard for labor legislation') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Non-compliance with environmental legislation') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Use of dangerous substances') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Bribery and corruption situations') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Failure to comply with competition laws') !!}" />
                        <x-list.bullet bgcolor="bg-esg3" title="{!! __('Other') !!}" />

                    </div>

                    <div>
                        <div class="text-lg font-bold">{!! __('Due diligence methodology adopted') !!}</div>
                        <div class="text-base mt-4 text-esg8">{!! __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet finibus elementum. Ut aliquam enim nec arcu auctor, porttitor cursus arcu malesuada. Ut consequat interdum dolor, at tempus justo facilisis a. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tellus massa, pellentesque a porttitor id, lacinia non risus. Morbi semper finibus nulla, nec tincidunt nulla aliquam eget. Maecenas sollicitudin sodales vestibulum. Morbi ullamcorper aliquet enim, a placerat sem bibendum et. Duis tempor, elit ut euismod facilisis, dolor augue posuere est, a tempus odio ex ut mauris. Pellentesque ut lacus nec arcu porta mattis. Vestibulum ut felis gravida, cursus nibh a, cursus diam. In finibus mauris eget lectus porttitor efficitur. Maecenas at tellus ornare, pretium ante id, tristique elit. Suspendisse non elit nec tortor luctus vestibulum. Proin dignissim, nisl vel malesuada mollis, felis dui imperdiet nisl, sed ultrices eros tortor in mi. Duis ultricies urna non orci con.') !!}</div>
                    </div>

                    <div>
                        <img src="/images/report/tdp/page44.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 45 --}}
            <x-report.page title="{!! __('governance performance') !!}" footerCount="45" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="text-lg font-bold uppercase">{!! __('Risk management') !!}</div>
                    <div class="grid grid-cols-2 gap-6 ">
                        <div>
                            <div class="text-lg font-bold mt-4">{!! __('Average probability of occurrence of risk categories') !!}</div>
                            <x-cards.card-dashboard-version1-withshadow
                                titleclass="!text-sm"
                                class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                                contentplacement="none">

                                <x-charts.bar id="average_probability"
                                    class="m-auto relative !h-full !w-full"
                                    x-init="$nextTick(() => {
                                        tenantBarChart(
                                            {{ json_encode([ __('Ethical'), __('Economic sustainability'), __('Business continuity'), __('Financing'), __('Legal'), __('Physical risks'), __('Political environment'), __('Environmental impact'), __('Human risks'), __('Health impact'), __('Market risk'), __('Supply chain security')]) }},
                                            {{ json_encode([53, 80, 63,53, 80, 63,53, 80, 63,53, 80, 63])}},
                                            'average_probability',
                                            ['#E19B00'],
                                            null,
                                            {
                                                showTitle: true,
                                                simplifiedGrid: true
                                            },
                                            'singlebar',
                                            'y'
                                        );
                                    });" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div>
                            <div class="text-lg font-bold">{!! __('Average impact severity of risk categories') !!}</div>
                            <x-cards.card-dashboard-version1-withshadow
                                titleclass="!text-sm"
                                class="!h-auto !shadow-none !p-0 !mt-4 !bg-transparent"
                                contentplacement="none">

                                <x-charts.bar id="average_impact_severity_risk_categories"
                                    class="m-auto relative !h-full !w-full"
                                    x-init="$nextTick(() => {
                                        tenantBarChart(
                                            {{ json_encode([ __('Ethical'), __('Economic sustainability'), __('Business continuity'), __('Financing'), __('Legal'), __('Physical risks'), __('Political environment'), __('Environmental impact'), __('Human risks'), __('Health impact'), __('Market risk'), __('Supply chain security')]) }},
                                            {{ json_encode([53, 80, 63,53, 80, 63,53, 80, 63,53, 80, 63])}},
                                            'average_impact_severity_risk_categories',
                                            ['#E19B00'],
                                            null,
                                            {
                                                showTitle: true,
                                                simplifiedGrid: true
                                            },
                                            'singlebar',
                                            'y'
                                        );
                                    });" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 46 --}}
            <x-report.page title="{!! __('governance performance') !!}" footerCount="46" footerborder="border-t-esg3">
                <div class="grid grid-cols-3 gap-6 py-6">
                    <div>
                        <div class="text-lg font-bold uppercase">{!! __('Governance policies') !!}</div>
                        <x-report.table.table class="!border-t-esg3">
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Anti-corruption and fraud policy') !!}</x-report.table.td>
                                <x-report.table.td>@include('icons.checkbox', ['color' =>  color(3)])</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Prevention and management of conflicts of interest Policy') !!}</x-report.table.td>
                                <x-report.table.td>@include('icons.checkbox', ['color' =>  color(3)])</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Data privacy policy') !!}</x-report.table.td>
                                <x-report.table.td>@include('icons.checkbox', ['color' =>  color(3)])</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Code of Ethics and Conduct for suppliers') !!}</x-report.table.td>
                                <x-report.table.td>@include('icons.checkbox', ['color' =>  color(3)])</x-report.table.td>
                            </x-report.table.tr>
                            <x-report.table.tr>
                                <x-report.table.td>{!! __('Whistleblowing channel for employees') !!}</x-report.table.td>
                                <x-report.table.td>@include('icons.checkbox', ['color' =>  color(3)])</x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>
                    <div class="col-span-2">
                        <img src="/images/report/tdp/page46.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Page 47 --}}
            <x-report.pagewithimage url="/images/report/tdp/page47.png" footer="true" header="true" footerCount="47" footerborder="border-t-esg5">
                <div class="">
                    <p class="text-6xl text-esg16 uppercase">{{ __('declaration of') }}</p>
                    <p class="text-6xl font-extrabold mt-3 uppercase text-esg5">{{ __('responsibility') }}</p>
                </div>
            </x-report.page>

            {{-- Page 48 --}}
            <x-report.page title="{!! __('governance performance') !!}" footerCount="48" footerborder="border-t-esg5">
                <div class="grid grid-cols-2 gap-6 py-6">
                    <div>
                        <div class="text-base text-esg8">{!! __('I declare, in my capacity as legal representative of (Company Name), that I take responsibility for this information and ensure that the information contained in it is true and that there are no omissions of which I am aware.') !!}</div>
                        <div class="text-base text-esg8">(0000-00-00) (Name)</div>
                    </div>
                    <div>
                        <img src="/images/report/tdp/page48.png" />
                    </div>
                </div>
            </x-report.page>

            {{-- Last Page --}}
            <x-report.page footerborder="border-t-esg3" lastpage="true" noheader="true" nofooter="true">
                <div >
                    <div class="h-[440px] grid place-content-end justify-center pb-10">
                        <div class="flex items-center gap-10">
                            @include(tenant()->views. 'icons.forest-logo')
                            @include(tenant()->views. 'icons.tdp')
                            @include(tenant()->views. 'icons.360')
                            @include('icons.logos.cmore-v2')
                        </div>

                        <div class="text-center uppercase text-base text-esg8 mt-10">{!! __('parceiros') !!}</div>
                        <div class="flex items-center justify-center gap-10 mt-4">
                            @include(tenant()->views. 'icons.pwc')
                            @include(tenant()->views. 'icons.ord')
                        </div>
                    </div>

                    <div class="h-40 bg-esg5 w-full">
                    </div>
                </div>
             </x-report.page>
        </div>
    </div>
@endsection
