@extends(customInclude('layouts.tenant-auth'))

@section('content')
    <div class="pt-4 pb-6">
        <h2 class="text-center sm:text-lg"><span class="text-esg8 font-semibold leading-9 font-encodesans text-2xl font-medium">{{ __('Code verification') }}</span></h2>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md w-[360px] md:w-[360px]">
        <div>
            <form method="POST" action="{{ route('tenant.2fa.post') }}">
                @csrf

                <div class="">
                    <div class="w-auto mb-2">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Use the token generated with Google or Microsoft Authenticator app') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-2 w-full group">
                        <input
                            type="text"
                            id="one_time_password"
                            class="placeholder-shown:text-esg11 placeholder:text-esg11 text-esg8 flex flex-row items-center block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('code') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                            name="one_time_password"
                            placeholder="{{ __('Code') }}"
                            required
                            autofocus>

                        <div class="absolute top-4 left-3 @error('code')text-red-500 @elsetext-[#dddddd] @enderror">
                        </div>

                        @if($errors->any())
                        <div class="absolute top-3 right-3 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="mt-1 text-xs text-red-500">
                            {{ $errors->first() }}
                        </p>
                        @endif

                    </div>
                </div>

                @if (tenant()->has2faBackupsCodeEnabled && Route::has('tenant.2fa.recover') && !empty(auth()->user()->getRecoveryTokens()))
                <div class="text-right text-sm leading-5 mt-1">
                    <a href="{{ route('tenant.2fa.recover') }}" class="hover:font-medium text-sm font-medium font-encodesans leading-5 text-esg5 transition duration-150 ease-in-out focus:underline focus:outline-none">
                        {{ __('Use recovery Code') }}
                    </a>
                </div>
                @endif

                <div class="mt-5 w-full text-center">
                    <button type="submit" class="w-full text-esg4 rounded-lg bg-esg5 py-[10px] px-12 text-base font-semibold leading-6 font-encodesans focus:outline-none">
                        {{ __('Log In') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
