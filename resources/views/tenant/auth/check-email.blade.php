@section('content')
    <div class="pt-4 pb-6 flex flex-wrap flex-col items-center w-[360px] lg:w-[320px] md:w-[280px] sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center"><span class="text-esg8 font-semibold leading-9 font-encodesans font-medium text-2xl sm:text-xl">{{ __('Check your email inbox') }}</span></h2>
        <p class="text-[#667085] max-w mt-2 text-center text-sm leading-5">
            <span>
                {{  __('If you have an account you will recieve an email with password recover instructions.') }}
            </span>
        </p>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md lg:w-[320px] md:w-[280px] sm:w-[240px]">
        <div class="text-center">
            <button type="button" class="w-full h-[44px] bg-esg5 text-esg4 rounded-lg py-2 px-12 text-2xl font-semibold text-base">
                <a href="{{ route('tenant.login') }}">{{ __('Return to login') }}</a>
            </button>
        </div>

        <div class="mt-8">
            <p class="text-[#667085] max-w text-center text-sm leading-5">
                <span>
                    {!!  __('Did not receive the e-mail? Check your spam filter, or :aanotherEmailtry another e-mail address:aclose', ['aanotherEmail' => '<b><a href="'. url('/password/reset') . '" class="text-esg28 cursor-pointer font-base">', 'aclose' => '</a></b>']) !!}
                </span>
            </p>
        </div>
    </div>
@endsection
