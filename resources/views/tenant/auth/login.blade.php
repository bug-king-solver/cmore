@extends(customInclude('layouts.tenant-auth'))

@section('content')
    <div class="pt-4 pb-6">
        <h2 class="text-center sm:text-lg"><span
                class="text-esg8 font-semibold leading-9 font-encodesans text-2xl font-medium">{{ __('Log in') }}</span>
        </h2>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md w-[360px] md:w-[360px]">
        <div>
            <form method="POST" action="{{ route('tenant.login') }}">
                @csrf

                <div class="">
                    <div class="w-auto mb-2">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ getUsernameLabel() }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-2 w-full group">
                        <input type="text" id="username" name="username"
                            class="placeholder-shown:text-esg11 placeholder:text-esg11 text-esg8 flex flex-row items-center block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('email') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                            placeholder="{{ __('Enter your username') }}"
                            value="{{ old('username', request()->query('email')) }}" required autocomplete="off" />

                        <div class="absolute top-4 left-3 @error('username') text-red-500 @else text-[#dddddd] @enderror">
                        </div>

                        @error('username')
                            <div class="absolute top-3 right-3 text-red-500">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <div class="w-auto mb-2">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Password') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-1 w-full group">
                        <input type="password" id="password" name="password"
                            class="placeholder-shown:text-esg11 placeholder:text-esg11 text-esg8 block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                            placeholder="{{ __('Enter your password') }}" value="" required autocomplete="off" />

                        <div class="absolute top-4 left-3 @error('password') text-red-500 @else text-[#dddddd] @enderror">
                        </div>

                        @error('password')
                            <div class="absolute top-3 right-3 text-red-500">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                @if (Route::has('tenant.password.request'))
                    <div class="text-right text-sm leading-5 mt-1">
                        <a href="{{ route('tenant.password.request') }}"
                            class="hover:font-medium text-sm font-medium font-encodesans leading-5 text-esg5 transition duration-150 ease-in-out focus:underline focus:outline-none"
                            data-test="forgot-pw">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif

                @if (tenant()->requireToAcceptTermsAndConditions() || tenant()->isUserRequiredToAcceptTermsConditions)
                    <div
                        class="flex flex-row mt-4 text-sm relative w-full items-center z-0 mb-2 w-full group @error('terms') text-red-500 @else text-esg11 @enderror">
                        <input type="checkbox" id="terms" name="terms" class="accent-esg5 mr-2 valid:bg-esg5" required />
                        <span>
                            {!! __('I agree with the :atermsTerms of Service:aclose and :aprivacy Privacy Policy:aclose.', [
                                'aterms' =>
                                    '<a href="' .
                                    tenant()->getTermsConditionsUrl(session()->get('locale')) .
                                    '" class="text-esg28 cursor-pointer font-base">',
                                'aprivacy' =>
                                    '<a href="' .
                                    tenant()->getPrivacyPolicyUrl(session()->get('locale')) .
                                    '" class="text-esg28 cursor-pointer font-base">',
                                'aclose' => '</a>',
                            ]) !!}
                        </span>
                    </div>
                    @error('terms')
                        <div class="absolute top-3 right-3 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                @endif

                <div class="mt-5 w-full text-center">
                    <button type="submit"
                        class="w-full text-esg4 rounded-lg bg-esg5 py-[10px] px-12 text-base font-semibold leading-6 font-encodesans focus:outline-none"
                        data-test="login-btn">
                        {{ __('Log in') }}
                    </button>
                </div>

                @if (tenant()->hasSelfRegistrationEnabled() && Route::has('tenant.register'))
                    <div class="mt-2 mb-1 w-full text-center text-esg11">
                        <span>
                            or
                        </span>
                    </div>

                    <div class="w-full text-center">
                        <a href="{{ route('tenant.register') }}"
                            class="w-full text-esg5 rounded-lg text-base font-semibold leading-6 font-encodesans focus:outline-none"
                            data-test="register-btn">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
