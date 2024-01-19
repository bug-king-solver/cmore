@extends(customInclude('layouts.tenant-auth'))

@section('content')
<div class="pt-4 pb-10 flex flex-col flex-wrap items-center">
    <h2 class="pl-4 text-center sm:text-lg"><span class="text-esg8 font-semibold leading-9 font-encodesans font-medium text-2xl">{{ __('Reset password') }}</span></h2>
    <p class="text-[#667085] w-[360px] max-w mt-2 text-center text-sm leading-5">
       <span>
        {{  __('Please enter the email address associated with your account and we will send an e-mail with instructions to reset your password.') }}
       </span>
    </p>
</div>
<div class="sm:mx-auto sm:w-full sm:max-w-md flex flex-col items-center w-[360px] lg:w-[360px] md:w-[320px]">
    @if (session('status'))
        @include('tenant.auth.check-email')
    @else
    <form method="POST" action="{{ route('tenant.password.email') }}">
        @csrf

        <div class="">
            <div class="w-[40px] h-[20px] mb-2">
                <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                    {{ __('Email') }}
                </span>
            </div>
            <div class="relative z-0 mb-6 group">
                <input type="email" id="email" name="email" class="w-[360px] placeholder-shown:text-esg11 placeholder:text-esg11 flex flex-row items-center h-[44px] block py-[10px] px-[14px] text-lg text-esg8 bg-transparent border-[1px] @error('email') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg" placeholder="{{ __('Enter your email') }}" value="{{ old('email', request()->query('email')) }}" required autofocus />


                @error('email')

                @include('tenant.auth.check-email')

                @enderror
            </div>
        </div>

        <div class="mt-6 text-center">
            <button type="submit" class="h-[44px] w-[360px] border-esg5 bg-esg5 text-esg4 rounded-lg border py-2 px-12 text-2xl text-base font-semibold leading-6" data-test="submit-btn">
                {{ __('Submit') }}
            </button>
        </div>
    </form>
    @endif
</div>
@endsection
