@section('content')
<div class="pt-4 pb-6 flex flex-wrap flex-col items-center w-[360px] lg:w-[320px] md:w-[280px] sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="text-center"><span class="text-esg8 font-semibold leading-9 font-encodesans font-medium text-2xl sm:text-xl">{{ __('Check your email') }}</span></h2>
    <p class="text-[#667085] max-w mt-2 text-center text-sm leading-5">
        <span>
            {{  __('We have sent password recovery instructions to your e-mail.') }}
        </span>
    </p>
</div>
@endsection