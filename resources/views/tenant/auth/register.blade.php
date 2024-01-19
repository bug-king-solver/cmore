@extends(customInclude('layouts.tenant-auth'))

@section('content')
    <div class="pt-4 flex mb-4 items-center">
        <h2 class="text-center sm:text-base"><span
                class="text-esg8 font-semibold leading-9 font-encodesans font-medium text-2xl">{{ __('Sign up') }}</span>
        </h2>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md w-[360px] lg:w-[360px] md:w-[320px]">
        <form method="POST" action="{{ route('tenant.register') }}" x-data="{ showPassword: false }">
            @csrf

            <div class="flex flex-col mt-5">
                <div class="w-auto h-[20px] mb-2">
                    <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                        {{ __('Name') }}
                    </span>
                </div>
                <div class="relative z-0 mb-2 w-full group">
                    <input type="text" id="name" name="name"
                        class="placeholder-shown:text-esg11 placeholder:text-esg11 flex flex-row items-center block py-[10px] px-[14px] w-full text-base text-esg8 bg-transparent border-[1px] @error('name') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                        placeholder="{{ __('Enter your name') }}" value="{{ old('name', request()->query('name')) }}"
                        required autofocus />

                    <div class="absolute top-4 left-3 @error('name') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('name')
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

            <div class="flex flex-col mt-5">
                <div class="w-auto h-[20px] mb-2">
                    <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                        {{ __('Email') }}
                    </span>
                </div>
                <div class="relative z-0 w-full group">
                    <input type="email" id="email" name="email"
                        class="placeholder-shown:text-esg11 placeholder:text-esg11 flex flex-row items-center block py-[10px] px-[14px] w-full text-base text-esg8 bg-transparent border-[1px] @error('email') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                        placeholder="{{ __('Enter your email') }}" value="{{ old('email', request()->query('email')) }}"
                        required autofocus />

                    <div class="absolute top-4 left-3 @error('email') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('email')
                        <div class="absolute top-3 right-3 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-3 mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            @if (!tenant()->is_email_the_authentication_username)
                <div class="flex flex-col mt-5">
                    <div class="w-auto h-[20px] mb-2">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ getUsernameLabel() }}
                        </span>
                    </div>
                    <div class="relative z-0 w-full group">
                        <input type="text" id="username" name="username"
                            class="placeholder-shown:text-esg11 placeholder:text-esg11 flex flex-row items-center block py-[10px] px-[14px] w-full text-base text-esg8 bg-transparent border-[1px] @error('username') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                            placeholder="{{ __('Enter your username') }}"
                            value="{{ old('username', request()->query('username')) }}" required autofocus />

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
                            <p class="ml-3 mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="flex flex-col mt-5">
                <div class="w-auto h-[20px] mb-2">
                    <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                        {{ __('Password') }}
                    </span>
                </div>
                <div class="relative z-0 w-full group">
                    <input x-bind:type="showPassword ? 'text' : 'password'" id="password" name="password"
                        class="placeholder-shown:text-esg11 placeholder:text-esg11 block py-[10px] px-[14px] w-full text-base text-esg8 bg-transparent border-[1px] @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                        placeholder="{{ __('Enter your password') }}" value="" required autocomplete
                        x-on:input="checkPasswordStrength()" />
                    <button type="button" id="show_password" x-on:click="showPassword = !showPassword"
                        x-show="showPassword == false" class="absolute top-3 right-3">
                        @include('icons.eye', ['color' => color(8), 'width' => '24px', 'height' => '20px'])
                    </button>
                    <button type="button" id="hide_password" x-on:click="showPassword = !showPassword"
                        x-show="showPassword != false" class="absolute top-3 right-3">
                        @include('icons.eye-slash-fill', [
                            'color' => color(8),
                            'width' => '28px',
                            'height' => '20px',
                        ])
                    </button>
                    <div class="pt-[1px]" id="password_strength">
                        <span class="font-encodesans text-xs text-esg11 font-normal">
                            {{ __('Must be at least 8 characters long.') }}
                        </span>
                    </div>
                    <div class="absolute top-4 right-10 @error('password') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('password')
                        <div class="absolute top-3 right-10 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-3 mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col mt-5">
                <div class="w-auto h-full mb-2">
                    <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                        {{ __('Confirm password') }}
                    </span>
                </div>
                <div class="relative z-0 w-full group">
                    <input x-bind:type="showPassword ? 'text' : 'password'" id="password_confirmation"
                        name="password_confirmation"
                        class="placeholder-shown:text-esg11 placeholder:text-esg11 block py-[10px] px-[14px] w-full text-base text-esg8 bg-transparent border-[1px] @error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                        placeholder="{{ __('Enter your password') }}" value="" required autocomplete />
                    <div class="pt-[1px]">
                        <span class="font-encodesans text-xs text-esg11 font-normal">
                            {{ __('Both passwords must match.') }}
                        </span>
                    </div>
                    <button type="button" id="show_confirmation" x-on:click="showPassword = !showPassword"
                        x-show="showPassword == false" class="absolute top-3 right-3">
                        @include('icons.eye', ['color' => color(8), 'width' => '24px', 'height' => '20px'])
                    </button>
                    <button type="button" id="hide_confirmation" x-on:click="showPassword = !showPassword"
                        x-show="showPassword != false" class="absolute top-3 right-3">
                        @include('icons.eye-slash-fill', [
                            'color' => color(8),
                            'width' => '28px',
                            'height' => '20px',
                        ])
                    </button>
                    <div
                        class="absolute top-2 right-10 @error('password_confirmation') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('password_confirmation')
                        <div class="absolute top-2 right-5 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="w-full text-right">
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        </div>
                    @enderror
                </div>
            </div>

            <div
                class="flex flex-row mt-5 text-sm relative w-full items-center z-0 my-3 w-full group @error('terms') text-red-500 @else text-esg11 @enderror">
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

            <div class="mb-2 w-full text-center mt-6">
                <button type="submit"
                    class="w-full text-esg4 rounded-lg bg-esg5 px-12 py-[10px] text-base font-semibold leading-6 font-encodesans focus:outline-none"
                    data-test="create-acc-btn">
                    {{ __('Create account') }}
                </button>
            </div>
        </form>
        <div class="w-full flex flex-row items-center gap-[4px] place-content-center mt-8">
            <p class="text-esg11 max-w text-sm leading-5 font-medium mt-[2px]">
                {{ __('Already have an account? ') }}
            </p>
            <div>
                <a href="{{ route('tenant.login') }}" class="text-esg28 leading-5 text-center text-sm mb-1">
                    {{ __('Log in') }}
                </a>
            </div>
        </div>
    </div>
@endsection

<script nonce="{{ csp_nonce() }}">
    function checkPasswordStrength() {
        var password = document.getElementById('password').value;

        var strengthIndicator = document.getElementById('password_strength');
        var strengthText = '';
        var strengthColor = '';

        if (password === '') {
            strengthIndicator.innerHTML =
                '<span class="font-encodesans text-xs text-esg11 font-normal">{{ __('Must be at least 8 characters long.') }}</span>';
            return;
        }

        if (/^(?=.*[0-9a-zA-Z!@#$%^&*()_+{}|:;<>?~`-]).{0,}$/i.test(password)) {
            strengthText = '{{ __('Password is too weak.') }}';
            strengthColor = 'red';
        }
        if (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!]).{8,}$/.test(password)) {
            strengthText = '{{ __('Password is moderate.') }}';
            strengthColor = 'orange';
        }
        if (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=.*[^\da-zA-Z]).{12,}$/.test(password)) {
            strengthText = '{{ __('Password is strong.') }}';
            strengthColor = 'yellowgreen';
        }
        if (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=.*[^\da-zA-Z]).{16,}$/.test(password)) {
            strengthText = '{{ __('Password is very strong.') }}';
            strengthColor = 'green';
        }

        strengthIndicator.innerHTML = '<span class="font-encodesans text-xs font-normal text-[' + strengthColor +
            ']">' + strengthText + '</span>';
    }
</script>
