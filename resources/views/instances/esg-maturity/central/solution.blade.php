@extends(customInclude('layouts.central'), ['isheader' => false, 'nav_background' => true, 'background_image' => true, 'background_fix' => false, 'nav_text_white' => false])


@section('content')
    <div class="grid text-center justify-items-center">
        <div class="text-esg28 text-7xl font-encodesans font-bold pt-80 text-center relative w-full">
            <span id="text1" class="absolute left-0 right-0 m-auto inline-block !-ml-[24]">{{__('Thrive')}}</span>
            <span id="text2" class="absolute left-0 right-0 m-auto inline-block !-ml-[24]">{{__('Thrive')}}</span>
        </div>

        <h3 class="text-esg27 text-4xl font-semibold font-encodesans mt-32">{{__('with the most complete ESG tool in the market. We help you translate Sustainability into business.')}}</h3>

        <h5 class="text-esg27 text-xl font-normal mt-40 w-28">{{__('proudly powered by')}}</h5>

        @include('logos/ibm', ['width' => '115px', 'height' => '42px' ])

        <div class="text-esg28 font-bold text-base mt-20"> <span class="inline-block mr-4">@include('icons/scroll-down')</span> {{__('Scroll to Experience')}}</div>
        <div class="text-base text-esg28 ml-20 mb-10">{{__('the game changer')}} <span class="text-base font-bold text-esg27">{{__('ESG SaaS')}}</span></div>
    </div>

    <div class="bg-[#0E283F] h-full flex left-[50%] mt-6 -ml-[50vw] w-screen relative after:absolute after:w-0 after:top-[100%] after:border-t-[20px] md:after:border-t-[40px] after:border-t-[#0E283F] after:border-l-[50vw] after:border-l-transparent after:border-r-[50vw] after:border-r-transparent after:h-0 after:z-10">
        <div class="w-full mx-auto max-w-7xl item-center py-10 md:py-28 px-4">
            <img
                alt="{{ __('What we do') }}"
                src="{{ global_asset('images/gif/'. session()->get('locale') .'_esg_block.gif') }}"
                class="w-full h-full" id="process">
        </div>
    </div>

    <div class="bg-white h-full flex left-[50%] -ml-[50vw] w-screen relative">
        <div class="w-full mx-auto max-w-7xl pt-28 px-4 pb-28">
            <div class="mt-10">
                <div class="text-center text-esg28 text-5xl font-bold font-encodesans mt-16">
                    {{__('What is ESG MATURITY?')}}
                </div>

                <div class="text-4xl font-bold font-encodesans mt-16 text-center text-esg28">
                    <p><span class="text-esg29 font-medium">{{__('ESG MATURITY delivers superior')}}</span> {{__('Action Plans')}}</p>
                    <p><span class="text-esg29 font-medium">{{__('focused on')}}</span> {{__('impact and outcomes')}}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 mt-16 gap-4 px-4 md:px-0">
                    <div class="text-center font-encodesans mt-20 md:mt-0 px-4">
                        <h2 class="text-3xl text-esg25 font-bold">{{__('Environmental​')}}</h2>
                        <p class="text-2xl text-esg29 font-medium mt-6 mb-5">{{__('A company’s impact on the natural environment and the energy and Resources it uses to operate.​')}}</p>
                        @include('icons.central.action_plan.'. session()->get('locale') .'_enviroment', ['class'=>'w-full'])
                    </div>

                    <div class="grid justify-items-center text-center font-encodesans mt-20 md:mt-0 px-4">
                        @include('icons/central/plus-sign')

                        <h2 class="text-3xl text-esg24 font-bold mt-16">{{__('Social')}}</h2>
                        <p class="text-2xl text-esg29 font-medium mt-6 mb-5">{{__('How a company manages its relations with employees, customers, suppliers and society in general.​​')}}</p>

                        @include('icons.central.action_plan.'. session()->get('locale') .'_social', ['class'=>'w-full'])
                    </div>

                    <div class="text-center font-encodesans mt-20 md:mt-0 px-4">
                        <h2 class="text-3xl text-esg26 font-bold">{{__('Governance')}}</h2>
                        <p class="text-2xl text-esg29 font-medium mt-6 mb-5">{{__('How the internal policies and procedures of a company make effective decisions for the greater good.​​​')}}</p>

                        @include('icons.central.action_plan.'. session()->get('locale') .'_governance', ['class'=>'w-full'])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#0E283F] h-72 flex left-[50%] -ml-[50vw] w-screen relative after:absolute after:w-0 after:top-[100%] after:border-t-[20px] md:after:border-t-[40px] after:border-t-[#0E283F] after:border-l-[50vw] after:border-l-transparent after:border-r-[50vw] after:border-r-transparent after:h-0 after:z-10">
        <div class="w-full mx-auto max-w-7xl pt-8 sm:px-6 lg:px-8">
            <div class="grid justify-items-center">
                @include('logos/esg2')
                <div class="text-center text-esg28 text-5xl font-bold font-encodesans mt-16">
                    {{__('How it Works?')}}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-esg6 h-full flex left-[50%] -ml-[50vw] w-screen relative bg-[#13304A]">
        <div class="w-full mx-auto max-w-7xl pt-8 sm:px-6 lg:px-8 md:mt-24">
            <div class="grid justify-items-center">
                <img
                    alt="{{ __('Dashboard example') }}"
                    src="{{ global_asset('images/gif/dashboard.gif') }}"
                    class="w-full h-full">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-10 md:mt-40 pb-24">
                <div class="px-4 md:p-0">
                    <img
                        alt="{{ __('Questionnaire example') }}"
                        src="{{ global_asset('images/gif/questionnaire.gif') }}"
                        class="w-full h-full">
                </div>
                <div class="justify-items-center gap-0 text-esg27 font-encodesans text-xl font-semibold md:pt-28 px-4 md:px-0">
                    <div class="flex mb-10">
                        <div class="flex-none w-20"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex">{{__('Customized questionnaire according to the business sector, country and size')}}</div>
                    </div>
                    <div class="flex">
                        <div class="flex-none w-20"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex">{{__('Questionnaire based on more than 350 to 450 metrics that are academically assessed and validated, inspired by GRI, SASB and GIIN methodologies')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white h-full flex left-[50%] -ml-[50vw] w-screen relative">
        <div class="w-full mx-auto max-w-7xl pt-5 md:mt-44 pb-24 px-4 md:px-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 font-encodesans ">
                <div class="mb-10 md:mb-0">
                    <div class="grid justify-items-center">
                        @include('icons.central.informs')
                    </div>
                    <h2 class="text-center text-esg28 font-bold uppercase text-2xl underline underline-offset-2">{{__('INFORMS')}}</h2>
                    <div class="flex mt-6">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('ESG Library')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg2')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Provide rapid and verified responses to RFPs and other audits')}}</div>
                    </div>
                </div>

                <div class="mb-10 md:mb-0">
                    <div class="grid justify-items-center">
                        @include('icons.central.connects')
                    </div>
                    <h2 class="text-center text-esg28 font-bold uppercase text-2xl underline underline-offset-2 mt-2.5">{{__('CONNECTS')}}</h2>
                    <div class="flex mt-6">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Reads unstructured information')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg2')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Performs a reputation analysis')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg1')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Connects with other platforms through an API')}}</div>
                    </div>
                </div>

                <div class="mb-10 md:mb-0">
                    <div class="grid justify-items-center">
                        @include('icons.central.aggregates')
                    </div>
                    <h2 class="text-center text-esg28 font-bold uppercase text-2xl underline underline-offset-2">{{__('AGGREGATES')}}</h2>
                    <div class="flex mt-6">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Aggregates all company’s ESG information and documentation')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg2')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Scope 1, 2, 3 GHG Accounting and Reporting')}}</div>
                    </div>
                </div>

                <div class="mb-10 md:mb-0">
                    <div class="grid justify-items-center">
                        @include('icons.central.analyses')
                    </div>
                    <h2 class="text-center text-esg28 font-bold uppercase text-2xl underline underline-offset-2 mt-2.5">{{__('ANALYSES')}}</h2>
                    <div class="flex mt-6">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('View the current status of the company (T0)')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg2')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Maps a set of initiatives (initiative vs effort level vs impact)')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg1')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Presents the Benchmark (by sector and country)')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg5')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Utility Bill Analytics')}}</div>
                    </div>
                </div>

                <div class="mb-10 md:mb-0">
                    <div class="grid justify-items-center">
                        @include('icons.central.moniters')
                    </div>
                    <h2 class="text-center text-esg28 font-bold uppercase text-2xl underline underline-offset-2">{{__('MONITORS')}}</h2>
                    <div class="flex mt-6">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full'])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Generates alerts (updating the tool with new legislation or document analysis)')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg2')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Customizable Dashboard (more than 100 possible charts)')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg1')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Target Setting and Tracking')}}</div>
                    </div>
                    <div class="flex mt-11">
                        <div class="flex-none w-10"> @include('icons/plus', ['class'=>'w-full', 'color'=>color('esg5')])</div>
                        <div class="flex text-base font-semibold text-esg29 h-14">{{__('Descriptive, Predictive and Prescriptive Analytics')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-esg6 h-full flex left-[50%] -ml-[50vw] w-screen relative bg-[#13304A]">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 md:px-0 pb-16">
            <h1 class="text-center text-esg28 text-5xl font-bold font-encodesans mt-16 mb-16">{{__('Features That Make us Unique')}}</h1>
            <div class="grid justify-items-center">
                @include('icons.central.unique_feature.'. session()->get('locale') .'_unique_feature', ['class'=>'w-full h-full'])
            </div>
        </div>
    </div>

    <div class="bg-white h-full flex left-[50%] -ml-[50vw] w-screen relative">
        <div class="w-full mx-auto max-w-7xl pt-20 pb-24 px-4 md:px-0">
            <div class="">
                <h1 class="text-center text-esg28 text-5xl font-bold font-encodesans mb-9">{{__('Packages and Features')}}</h1>
                <p class="font-encodesans text-2xl font-semibold text-center mb-20 text-esg29"> {{__('We believe that the best ESG practices in companies not only mitigate risks but also have the potential to generate above-average financial returns')}} </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 ">
                    <div class="flex flex-col mb-8 sm:m-2 bg-esg6 rounded-2xl px-3 py-4 border-b-[22px] border-esg3">
                        @include('icons/plus')

                        <h2 class="text-esg26 text-3xl font-semibold font-inter mt-3">SCREEN</h2>
                        <div>
                            <span class="bg-esg3 text-xl font-semibold font-inter text-esg29">{{__('DO IT YOURSELF')}}</span>
                        </div>

                        <p class="text-base font-medium font-encodesans mt-3.5 text-esg27">
                            {{__("Macro analysis of the company's ESG stage, identifying key focus areas and initiatives (First steps towards sustainability).")}}
                        </p>

                        <div class="mt-6">
                            <p class="text-base font-medium text-esg27"> <span class="text-esg26">|</span> {{__('Macro Questionnaire (up to 40 questions)')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg26">|</span> {{__('Basic Report (up to 6 charts)')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg26">|</span> {{__('Action Plan')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg26">|</span> {{__('FAQs')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg26">|</span> {{__('3 users')}}</p>
                        </div>

                        <div class="text-center mt-auto">
                            <x-buttons.btn-central text="Subscribe" modal="modals.central" />
                        </div>

                        <div class="flex flex-col-5 w-full mt-5">
                            @include('icons/chat', ['class' => 'inline-block mr-2'])
                            @include('icons/notes', ['class' => 'inline-block mr-2'])
                            @include('icons/target', ['class' => 'inline-block mr-2'])
                            @include('icons/faq', ['class' => 'inline-block mr-2'])
                            @include('icons/user', ['class' => 'inline-block mr-2'])
                        </div>
                    </div>

                    <div class="flex flex-col mb-8 sm:m-2 bg-esg6 rounded-2xl p-3 py-4 border-b-[22px] border-esg2">
                        <div>
                            @include('icons/plus', ['class' => 'inline-block mr-1'])
                            @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
                        </div>

                        <h2 class="text-esg25 text-3xl font-semibold font-inter mt-3">ASSESS</h2>
                        <div>
                            <span class="bg-esg2 text-xl font-semibold font-inter text-esg29">{{__('FUNDAMENTALS')}}</span>
                        </div>

                        <p class="text-base font-medium font-encodesans mt-3.5 text-esg27">
                            {{__("Captures a snapshot (AS-IS) of the overall ESG MATURITY status of the company.")}}
                        </p>

                        <div class="mt-6">
                            <p class="text-base font-medium text-esg27"> <span class="text-esg25">|</span> {{__('Complete Questionnaire')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('Basic Dashboard (up to 12 charts)')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('Action Plan')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('Library')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('20 users')}}</p>
                        </div>

                        <div class="text-center top-64 mt-auto">
                            <x-buttons.btn-central text="Subscribe" modal="modals.central" />
                        </div>

                        <div class="flex flex-col-5 w-full mt-5">
                            @include('icons/chat', ['class' => 'inline-block mr-2'])
                            @include('icons/donut', ['class' => 'inline-block mr-2'])
                            @include('icons/target', ['class' => 'inline-block mr-2'])
                            @include('icons/library', ['class' => 'inline-block mr-2'])
                            @include('icons/user', ['class' => 'inline-block mr-1'])
                        </div>
                    </div>

                    <div class="flex flex-col mb-8 sm:m-2 bg-esg6 rounded-2xl p-3 py-4 border-b-[22px] border-esg1">
                        <div>
                            @include('icons/plus', ['class' => 'inline-block mr-1'])
                            @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
                            @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg1')])
                        </div>

                        <h2 class="text-esg24 text-3xl font-semibold font-inter mt-3">DISCOVER</h2>
                        <div>
                            <span class="bg-esg1 text-xl font-semibold font-inter text-esg29">{{__('POSITIONING')}}</span>
                        </div>

                        <p class="text-base font-medium font-encodesans mt-3.5 text-esg27">
                            {{__("Complements the Assess with a set of highly relevant details for companies who want to position themselves in the market.")}}
                        </p>

                        <div class="mt-6 mb-10">
                            <p class="text-base font-medium text-esg27"> <span class="text-esg24">|</span> {{__('Dynamic Dashboard')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg24">|</span> {{__('Benchmark (sector and country)')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg24">|</span> {{__('Document Analysis')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg24">|</span> {{__('Alarming (Legislation)')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg24">|</span> {{__('Unlimited number of users')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg24">|</span> {{__('Customization of Software Layout Colors')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg24">|</span> {{__('Definition of Targets and Monitoring')}}</p>
                        </div>

                        <div class="text-center mt-auto">
                            <x-buttons.btn-central text="Subscribe" modal="modals.central" />
                        </div>

                        <div class="flex flex-col-5 w-full mt-5">
                            @include('icons/donut', ['class' => 'inline-block mr-2'])
                            @include('icons/search', ['class' => 'inline-block mr-2'])
                            @include('icons/book', ['class' => 'inline-block mr-2'])
                            @include('icons/alert', ['class' => 'inline-block mr-2'])
                            @include('icons/user', ['class' => 'inline-block mr-1'])
                            @include('icons/brush', ['class' => 'inline-block mr-2'])
                            @include('icons/target1', ['class' => 'inline-block mr-2'])

                        </div>
                    </div>

                    <div class="flex flex-col mb-8 sm:m-2 bg-esg6 rounded-2xl p-3 py-4 border-b-[22px] border-esg5">
                        <div>
                            @include('icons/plus', ['class' => 'inline-block mr-1'])
                            @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
                            @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg1')])
                            @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg5')])
                        </div>

                        <h2 class="text-esg28 text-3xl font-semibold font-inter mt-3">ACT</h2>
                        <div>
                            <span class="bg-esg5 text-xl font-semibold font-inter text-esg29">{{__('REPUTATION')}}</span>
                        </div>

                        <p class="text-base font-medium font-encodesans mt-3.5 text-esg27">
                            {{__("Suitable for companies that want to differentiate themselves in the market for their good practices not only by implementing their measures but also their impact on the business. This package allows clients to analyze their data to anticipate and prevent risks and optimize resources, resulting in well-founded strategic decision-making.​")}}
                        </p>

                        <div class="mt-6">
                            <p class="text-base font-medium text-esg27"> <span class="text-esg28">|</span> {{__('Reputational Analysis')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg28">|</span> {{__('Descriptive Analytics, Predictive Analytics and Prescriptive Analytics')}}</p>
                            <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg28">|</span> {{__('Risk Anticipation and Prevention')}}</p>
                        </div>

                        <div class="text-center mt-auto">
                            <x-buttons.btn-central text="Subscribe" modal="modals.central" />
                        </div>

                        <div class="flex flex-col-5 w-full mt-5">
                            @include('icons/star', ['class' => 'inline-block mr-2'])
                            @include('icons/chart', ['class' => 'inline-block mr-2'])
                            @include('icons/eye', ['class' => 'inline-block mr-2'])
                        </div>
                    </div>
                </div>

                <div class="justify-center text-center mt-14">
                    <x-buttons.btn-central text="Request a Demo" modal="modals.central" />
                </div>
            </div>
        </div>
    </div>

    <div class="bg-esg6 h-full flex left-[50%] -ml-[50vw] w-screen relative bg-[#13304A]">
        <div class="w-full mx-auto max-w-7xl pt-8 px-4 md:px-0 pb-16 grid justify-items-center">
            <div class="grid justify-items-center">
                @include('icons.library')
            </div>

            <h1 class="text-center text-esg28 text-5xl font-bold font-encodesans mt-10 mb-16">{{__('Our Customers')}}</h1>

            <div class="lgmd:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-5 text-center">
                    <div class="m-auto"><img class="mx-4 max-h-14" src="{{ global_asset('images/customers/ibm.svg')}}" alt=""></div>
                    <div class="m-auto"><img class="mx-4 max-h-14 mt-8 md:mt-0" src="{{ global_asset('images/customers/closer.png')}}" alt=""></div>
                    <div class="m-auto"><img class="mx-4 max-h-14 mt-8 md:mt-0" src="{{ global_asset('images/customers/loba.svg')}}" alt=""></div>
                    <div class="m-auto"><img class="mx-4 max-h-14 mt-8 md:mt-0" src="{{ global_asset('images/customers/endeavor.png')}}" alt=""></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 md:mt-24 mb-16 text-center">
                    <div class="m-auto"><img class="mx-4 max-h-14 mt-8 md:mt-0" src="{{ global_asset('images/customers/primasea.png')}}" alt=""></div>
                    <div class="m-auto"><img class="mx-4 max-h-14 mt-8 md:mt-0" src="{{ global_asset('images/customers/scope-invest.png')}}" alt=""></div>
                    <div class="m-auto"><img class="mx-4 max-h-14 mt-8 md:mt-0" src="{{ global_asset('images/customers/solum.png')}}" alt=""></div>
                </div>
            </div>
        </div>
    </div>

    <script charset="utf-8" type="text/javascript" src="//js-eu1.hsforms.net/forms/embed/v2.js" nonce="{{ csp_nonce() }}"></script>

    <script nonce="{{ csp_nonce() }}">
        const elts = {
            text1: document.getElementById("text1"),
            text2: document.getElementById("text2")
        };

        const texts = [
            "{{__('Thrive')}}",
            "{{__('Explore')}}",
            "{{__('Monitor')}}",
            "{{__('Manage')}}"
        ];

        const morphTime = 1;
        const cooldownTime = 0.25;

        let textIndex = texts.length - 1;
        let time = new Date();
        let morph = 0;
        let cooldown = cooldownTime;

        elts.text1.textContent = texts[textIndex % texts.length];
        elts.text2.textContent = texts[(textIndex + 1) % texts.length];

        function doMorph() {
            morph -= cooldown;
            cooldown = 0;

            let fraction = morph / morphTime;

            if (fraction > 1) {
                cooldown = cooldownTime;
                fraction = 1;
            }

            setMorph(fraction);
        }

        function setMorph(fraction) {
            elts.text2.style.filter = `blur(${Math.min(8 / fraction - 8, 100)}px)`;
            elts.text2.style.opacity = `${Math.pow(fraction, 0.4) * 100}%`;

            fraction = 1 - fraction;
            elts.text1.style.filter = `blur(${Math.min(8 / fraction - 8, 100)}px)`;
            elts.text1.style.opacity = `${Math.pow(fraction, 0.4) * 100}%`;

            elts.text1.textContent = texts[textIndex % texts.length];
            elts.text2.textContent = texts[(textIndex + 1) % texts.length];
        }

        function doCooldown() {
            morph = 0;

            elts.text2.style.filter = "";
            elts.text2.style.opacity = "100%";

            elts.text1.style.filter = "";
            elts.text1.style.opacity = "0%";
        }

        function animate() {
            requestAnimationFrame(animate);

            let newTime = new Date();
            let shouldIncrementIndex = cooldown > 0;
            let dt = (newTime - time) / 1000;
            time = newTime;

            cooldown -= dt;

            if (cooldown <= 0) {
                if (shouldIncrementIndex) {
                    textIndex++;
                }

                doMorph();
            } else {
                doCooldown();
            }
        }

        animate();

        let lastKnownScrollPosition = 0;

        document.addEventListener("DOMContentLoaded", function(event) {
            document.addEventListener('scroll', (e) => {
                lastKnownScrollPosition = window.scrollY;
                const img = document.getElementById("process");
                let src = img.getAttribute('src');
                window.requestAnimationFrame(() => {
                    if(lastKnownScrollPosition >= 260 &&  lastKnownScrollPosition <= 320) {
                        img.setAttribute('src', src);
                    }
                });
            });
        });

    </script>

@endsection
