@extends(customInclude('layouts.central'), ['nav_background' => true])

@section('content')

    <div class="bg-[#0E283F] h-auto flex left-[50%] -ml-[50vw] w-screen relative after:absolute after:w-0 after:top-[100%] after:border-t-[20px] md:after:border-t-[50px] after:border-t-[#0E283F] after:border-l-[50vw] after:border-l-transparent after:border-r-[50vw] after:border-r-transparent after:h-0" >
        <div class="w-full mx-auto max-w-7xl pt-8 sm:px-6 lg:px-8 pb-16 sm:pb-0">
            <div class="grid justify-items-center pb-10">
                <div class="font-encodesans text-center text-esg28 font-bold text-6xl pt-40">
                    {{ __('ESG simple and impactful') }}
                </div>
                <x-buttons.btn-central text="Request a Demo" modal="modals.central" class="bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 mt-24 rounded-lg"/>
            </div>
        </div>
    </div>


    <div class="font-encodesans text-center text-esg28 font-bold text-5xl pt-14 mb-14 mt-14">
        {{__('Packages and Features')}}
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div class="flex flex-col m-2 bg-esg6 rounded-2xl px-3 py-4 border-b-[22px] border-esg3">
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
                <x-buttons.btn-central text="Subscribe" modal="modals.central" class="bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 rounded-lg"/>
            </div>

            <div class="flex flex-col-5 w-full mt-5">
                @include('icons/chat', ['class' => 'inline-block mr-2'])
                @include('icons/notes', ['class' => 'inline-block mr-2'])
                @include('icons/target', ['class' => 'inline-block mr-2'])
                @include('icons/faq', ['class' => 'inline-block mr-2'])
                @include('icons/user', ['class' => 'inline-block mr-2'])
            </div>
        </div>

        <div class="flex flex-col m-2 bg-esg6 rounded-2xl p-3 py-4 border-b-[22px] border-esg2">
            <div>
                @include('icons/plus', ['class' => 'inline-block mr-1'])
                @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
            </div>

            <h2 class="text-esg25 text-3xl font-semibold font-inter mt-3">ASSESS</h2>
            <div>
                <span class="bg-esg2 text-xl font-semibold font-inter text-esg29">{{__('FUNDAMENTALS')}}</span>
            </div>

            <p class="text-base font-medium font-encodesans mt-3.5 text-esg27">
                {{__("Captures a snapshot (AS-IS) of the overall ESG maturity status of the company.")}}
            </p>

            <div class="mt-6">
                <p class="text-base font-medium text-esg27"> <span class="text-esg25">|</span> {{__('Complete Questionnaire')}}</p>
                <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('Basic Dashboard (up to 12 charts)')}}</p>
                <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('Action Plan')}}</p>
                <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('Library')}}</p>
                <p class="text-base font-medium text-esg27 mt-6"><span class="text-esg25">|</span> {{__('20 users')}}</p>
            </div>

            <div class="text-center top-64 mt-auto">
                <x-buttons.btn-central text="Subscribe" modal="modals.central" class="bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 rounded-lg"/>
              </div>

            <div class="flex flex-col-5 w-full mt-5">
                @include('icons/chat', ['class' => 'inline-block mr-2'])
                @include('icons/donut', ['class' => 'inline-block mr-2'])
                @include('icons/target', ['class' => 'inline-block mr-2'])
                @include('icons/library', ['class' => 'inline-block mr-2'])
                @include('icons/user', ['class' => 'inline-block mr-1'])
            </div>
        </div>

        <div class="flex flex-col m-2 bg-esg6 rounded-2xl p-3 py-4 border-b-[22px] border-esg1">
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
                <x-buttons.btn-central text="Subscribe" modal="modals.central" class="bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 rounded-lg"/>
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

        <div class="flex flex-col m-2 bg-esg6 rounded-2xl p-3 py-4 border-b-[22px] border-esg5">
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
                <x-buttons.btn-central text="Subscribe" modal="modals.central" class="bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 rounded-lg"/>
            </div>

            <div class="flex flex-col-5 w-full mt-5">
                @include('icons/star', ['class' => 'inline-block mr-2'])
                @include('icons/chart', ['class' => 'inline-block mr-2'])
                @include('icons/eye', ['class' => 'inline-block mr-2'])
            </div>
        </div>
    </div>

    <div class="grid justify-items-center">
        <div class="w-full lg:w-[940px] mt-24 rounded-lg bg-esg15">
            <div class="mx-6 py-6 md:py-0 md:mx-40  my-10 text-esg27 bg-esg6 text-3xl font-semibold font-inter text-center h-30 md:h-20 content-center grid grid-cols-1 rounded-lg">
                <div>{{__('Complement with some functionalities')}}</div>
            </div>

            <div class="grid grid-colos-1 md:grid-cols-2 md:gap-4 p-5">
                <div class="flex bg-esg4 h-auto min-h-24 p-4 content-center rounded-lg border-solid border-2 border-esg7">
                    <div class="flex-initial w-20">
                        @include('icons/api')
                    </div>
                    <div class="flex-2 text-left pl-3 pt-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('APIs')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-initial w-20">
                        @include('icons/brush', ['width' => '56', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Questionnaire Customization')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/target1', ['width' => '56', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Construction and monitorizing targets')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/mail')
                    </div>
                    <div class="flex-2 text-left pl-3 pt-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Alarmistic by email')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/search', ['width' => '56', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pl-3 pt-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Benchmark')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/user', ['width' => '56', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Upgrade the number of users')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/star', ['width' => '56', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pt-2 pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Reputational Analysis')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/book', ['width' => '58', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pt-2 pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Documents Analysis')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/eye-line', ['class'=>'mt-3'])
                    </div>
                    <div class="flex-2 text-left pt-2 pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Value Chain Monitoring')}}
                    </div>
                </div>

                <div class="flex bg-esg4 p-4 h-auto min-h-24 content-center rounded-lg border-solid border-2 border-esg7 mt-5 md:mt-0">
                    <div class="flex-none w-20">
                        @include('icons/polution', ['width' => '58', 'height' => '56', 'color' => color('esg5')])
                    </div>
                    <div class="flex-2 text-left pl-3 text-2xl font-bold text-esg29 font-encodesans">
                        {{__('Calculation of Scope 1,2 and 3 Emissions')}}
                    </div>
                </div>
            </div>
        </div>

        <div class="justify-center text-center mt-24">
            <x-buttons.btn-central text="Request a Demo" modal="modals.central" class="bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 mt-24 rounded-lg"/>
        </div>

        <div class="mt-20 text-esg29 font-encodesans text-lg font-medium mb-20 p-4 md:p-0">
            <span class="font-bold">{{__('NOTE:')}}</span>
            {{__('ESG MATURITY has a minimum 12 months subscription, it is possible to upgrade your plan anytime. It is not possible to downgrade plans. ')}}
        </div>
    </div>
@endsection

<script charset="utf-8" type="text/javascript" src="//js-eu1.hsforms.net/forms/embed/v2.js" nonce="{{ csp_nonce() }}"></script>
