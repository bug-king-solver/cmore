@extends(customInclude('layouts.central'), ['nav_background' => true, 'background_image' => false])

@section('content')
    <div class="bg-[#0E283F] h-auto flex left-[50%] -ml-[50vw] w-screen relative after:absolute after:w-0 after:top-[100%] after:border-t-[20px] md:after:border-t-[40px] after:border-t-[#0E283F] after:border-l-[50vw] after:border-l-transparent after:border-r-[50vw] after:border-r-transparent after:h-0" >
        <div class="w-full mx-auto max-w-7xl md:pt-8 sm:px-6 lg:px-8 sm:pb-0">
            <div class="grid justify-items-center pb-10">
                <div class="text-center text-esg28 text-base sm:text-5xl font-bold font-encodesans mt-36">
                    {{__('“We translate sustainability into business”.')}}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-14 md:mt-24 md:mb-28 h-full">
        <div class="lg:inline-block w-full lg:w-min items-center pb-10 lg:pb-12">
            <a href="https://cmore-sustainability.com/" >@include('logos.cmore4', ['class' => 'flex justify-center w-full lg:w-min', 'width' => 200, 'height'=>90])</a>
        </div>
        <div class="md:inline-block w-full md:w-10/12 md:border-l-2 border-l-esg7 md:pl-6 py-4">
            <p class="text-esg29 text-sm sm:text-2xl font-medium text-left font-encodesans">
                <a href="https://cmore-sustainability.com/" class="text-esg28">C-MORE</a> {{__('is a tech company with the purpose of')}} <span class="text-esg28 text-sm sm:text-2xl font-semibold text-center font-encodesans">{{__('translating sustainability into business, through its management tool - ESG Maturity.')}}</span> {{__('As such, we')}} <span class="text-esg28 text-sm sm:text-2xl font-semibold text-center font-encodesans">{{__('have developed a contextual "family" of algorithms, combining Data Science and Intelligence on Environmental, Social and Governance (ESG) metrics')}}</span> {{__('with different business models, sectors and companies of different sizes, allowing for')}} <span class="text-esg28 text-sm sm:text-2xl font-semibold text-center font-encodesans">{{__('ESG maturity level scoring')}}</span>, {{__('Benchmarking, metrics and behavioral analysis, and the reduction and risk of non-compliance.')}}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 font-encodesans">
        <div class="flex text-center">
            <div class="flex-initial">
                <p class="text-esg7/40 font-medium text-[300px]">1</p>
                <p class="text-esg26 font-bold text-7xl -mt-[245px]">{{__('Vision')}}</p>
                <p class="text-lg md:text-2xl font-medium text-esg16 mt-36">{{__('Transform the economic paradigm into something better.')}}</p>
            </div>
            <div class="flex-none w-2 mt-24 invisible md:visible">
                @include('icons.central.right-line')
            </div>
        </div>

        <div class="flex text-center">
            <div class="flex-initial">
                <p class="text-esg7/40 font-medium text-[300px]">2</p>
                <p class="text-esg24 font-bold text-7xl -mt-[245px]">{{__('Mission')}}</p>
                <p class="text-lg md:text-2xl font-medium text-esg16 mt-36">{{__('Translating sustaibability into')}} <span class="font-semibold">{{__('YOUR')}}</span> {{__('business')}}</p>
            </div>
            <div class="flex-none w-2 mt-24 invisible md:visible">
                @include('icons.central.right-line')
            </div>
        </div>

        <div class="text-center">
            <p class="text-esg7/40 font-medium text-[300px]">3</p>
            <p class="text-esg25 font-bold text-7xl -mt-[245px]">{{__('Our Values')}}</p>
        </div>

        <div class="mt-10 sm:mt-0">
            <p class="text-esg16 text-lg md:text-2xl font-medium mt-16"><span class="text-esg28 font-semibold">{{__('a) Transparency:')}}</span> {{__('we must be bulletproof - walk the talk')}}</p>
            <p class="text-esg16 text-lg md:text-2xl font-medium mt-16"><span class="text-esg28 font-semibold">{{__('b) Creativity:')}}</span> {{__('the only way to thrive in this world - if you don’t create, you can’t transform')}}</p>
            <p class="text-esg16 text-lg md:text-2xl font-medium mt-16"><span class="text-esg28 font-semibold">{{__('c) Simplicity:')}}</span> {{__('if it is too complex to understand, it is not well explained')}}</p>
        </div>
    </div>

    <div class="bg-esg6 h-auto mt-28 flex left-[50%] -ml-[50vw] w-screen relative py-8">
        <div class="w-full mx-auto max-w-max px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 font-encodesans text-esg27">
                <div class="flex">
                    <div class="flex-none w-16 h-full grid content-center">
                        @include('icons.central.sustainability')
                    </div>
                    <div class="flex-initial grid content-center">
                        <p class="text-xs md:text-xl font-medium">{{__('BCORP focused on impact and expert in sustainability')}}</p>
                    </div>
                </div>

                <div class="flex mt-10 md:mt-0">
                    <div class="flex-none w-16 grid content-center">
                        @include('icons.central.problem_solver')
                    </div>
                    <div class="flex-initial grid content-center">
                        <p class="text-xs md:text-xl font-medium">{{__('Problem Solvers')}}</p>
                    </div>
                </div>

                <div class="flex mt-10 md:mt-0">
                    <div class="flex-none w-16 grid content-center">
                        @include('icons.central.team')
                    </div>
                    <div class="flex-initial grid content-center">
                        <p class="text-xs md:text-xl font-medium">{{__('Multidisciplinary and')}}<br/>{{__('experienced team')}}</p>
                    </div>
                </div>

                <div class="flex mt-10 md:mt-0">
                    <div class="flex-none w-16 grid content-center">
                        @include('icons.central.partnership')
                    </div>
                    <div class="flex-initial grid content-center">
                        <p class="text-xs md:text-xl font-medium">{{__('We work in partnership')}}</p>
                    </div>
                </div>

                <div class="flex mt-10 md:mt-0">
                    <div class="flex-none w-16 grid content-center">
                        @include('icons.central.setting')
                    </div>
                    <div class="flex-initial grid content-center">
                        <p class="text-xs md:text-xl font-medium">{{__('We collaborate, co-create and co-operate with our clients in order to embed sustaibable positive impact in the company')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-10">
        @include('icons.central.history.'. session()->get('locale') .'_history', ['class' => 'w-0 h-0 md:w-full md:h-full invisible md:visible'])
        @include('icons.central.history.'. session()->get('locale') .'_history_mobile', ['class' => 'w-full h-full visible md:w-0 md:h-0 md:invisible'])
    </div>
@endsection
