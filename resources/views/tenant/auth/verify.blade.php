@extends(customInclude('layouts.tenant-auth'))

@section('content')
<div class="">
    <div class="w-full max-w-sm flex flex-col justify-between items-center w-[360px] md:w-[320px] sm:w-[280px]">
        @if (session('resent'))
            @include('tenant.auth.verify-email-sent')
        @else
        <div class="pt-4 pb-6">
            <h2 class="pl-4 text-center text-2xl"><span class="text-esg8 font-semibold leading-9 font-encodesans font-medium">{{ __('Verify your email') }}</span></h2>
            <p class="text-[#667085] max-w mt-2 text-center text-sm leading-5">
                <span>
                    {{  __('Use the link below to verify your e-mail address and start enjoying ESG Maturity.') }}
                </span>
            </p>
        </div>
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <form id="resend-verification-form" method="POST" action="{{ route('tenant.verification.resend') }}">
                    @csrf
                    <button type="submit" class="w-[360px] md:w-[280px] sm:w-[240px] h-[44px] bg-esg5 text-esg4 rounded-lg py-2 px-12 text-base font-semibold">
                        {{ __('Verify') }}
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
