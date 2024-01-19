<div class="shadow rounded border-b-4 border-esg5 hover:bg-esg5/10 px-4 py-8 ">
    <div class="grid justify-items-center -mt-16">
        @include(tenant()->views . 'icons.1')
    </div>

    <p class="mt-4 text-lg font-bold text-center"> {{ __('Identify the company') }} </p>

    <p class="text-base text-center mt-4 text-esg8">
        {{ __('The first step is to fill in simple information about your company that will allow us to identify the next steps of your journey.') }}
    </p>
</div>

@if (request()->routeIs('tenant.companies.form'))
    <div class="row mt-10 text-sm">
        <p class="text-red-500">
            {{ __('By submitting this form you agree and accept the terms and conditions of use of the SIBS ESG Portal, which means that the information provided by user companies can be shared with Participating Banks.') }}
            <a href="{{tenant()->getTermsConditionsUrl(session()->get('locale'))}}" class="underline">{{ __('See the full text of the terms and conditions here.') }}</a>
        </p>
    </div>
@endif
