@extends(customInclude('layouts.central'), ['isheader' => false, 'nav_background' => true, 'background_image' => true])
@section('content')
<div class="pt-32 pb-10">
    <h1 class="text-center text-6xl font-bold font-encodesans text-esg28"> {{ __('FAQ\'s') }} </h1>
    <p class="text-center text-4xl font-semibold mt-9 text-esg28"> {{__('Our team is here to help you! ')}} </p>
</div>

<div class="mt-24 text-center">
    <span class="text-esg28 font-semibold font-encodesans text-5xl border-t-2 border-b-2 border-esg5 py-4 leading-[115px] lg:leading-none">{{__('Frequently Asked Questions - FAQ')}}</span>
</div>

<div class="mt-28 text-center">
    <button class="bg-esg5 w-40 h-12 font-encodesans font-semibold text-2xl text-esg27 rounded-lg mr-6">{{ __('Section 1') }}</button>
    <button class="bg-esg4 w-40 h-12 font-encodesans font-semibold text-2xl text-esg28 rounded-lg">{{ __('Section 2') }}</button>
</div>

<div class="mt-20 mb-36 w-full md:w-1/2 m-auto justify-items-center">
    <div id="accordion-flush" data-accordion="collapse" data-active-classes="text-esg28" data-inactive-classes="">
        <h2 id="accordion-flush-heading-1">
            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-esg28" data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
                <span class="text-esg28 text-2xl font-medium">{{__('What is ESG Maturity software?')}}</span>
                <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-flush-body-1" class="" aria-labelledby="accordion-flush-heading-1">
            <div class="py-5 text-esg27 text-lg font-normal">
                <p class="mb-2">{{__('ESG Maturity is a platform that provides comprehensive visual analytical information within a multidimensional assessment. Through cutting edge technology (including AI- Artificial Intelligence) users can provide data, acknowledge international standards, monitor ESG activities, compare with benchmarks and understand future trends.')}}</p>
            </div>
        </div>

        <h2 id="accordion-flush-heading-2">
            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-esg28" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
                <span class="text-esg28 text-2xl font-medium">{{__('Why ESG Maturity is important?')}}</span>
                <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-flush-body-2" class="" aria-labelledby="accordion-flush-heading-2">
            <div class="py-5 text-esg27 text-lg font-normal">
                <p class="mb-2">{{__('ESG Maturity is a platform that provides comprehensive visual analytical information within a multidimensional assessment. Through cutting edge technology (including AI- Artificial Intelligence) users can provide data, acknowledge international standards, monitor ESG activities, compare with benchmarks and understand future trends.')}}</p>
            </div>
        </div>

        <h2 id="accordion-flush-heading-3">
            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-esg28" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
                <span class="text-esg28 text-2xl font-medium">{{__('How do you export the dashboard?')}}</span>
                <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-flush-body-3" class="" aria-labelledby="accordion-flush-heading-3">
            <div class="py-5 text-esg27 text-lg font-normal">
                <p class="mb-2">{{__('ESG Maturity is a platform that provides comprehensive visual analytical information within a multidimensional assessment. Through cutting edge technology (including AI- Artificial Intelligence) users can provide data, acknowledge international standards, monitor ESG activities, compare with benchmarks and understand future trends.')}}</p>
            </div>
        </div>
    </div>
</div>
@endsection
