@extends(customInclude('layouts.auth'))

@section('content')
    <div class="pt-4 pb-1 flex items-center">
        <h2 class="text-center text-xl"><span
                class="text-esg8 font-semibold leading-9 font-encodesans font-medium">{{ __('Sign up') }}</span></h2>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md lg:h-full">
        <form method="POST" action="{{ route('central.tenants.register.submit') }}">
            @csrf

            <div class="flex flex-col justify-between">
                <div class="">
                    <div class="w-[40px] h-[20px] mb-1">
                        <span class="text-esg8 not-italic text-base leading-3 font-encodesans font-medium ml-[4px]">
                            {{ __('Company') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-1 w-full group">
                        <input type="text" id="company" name="company"
                            class="placeholder-shown:text-[#8A8A8A] placeholder:text-[#8A8A8A] placeholder:text-base flex flex-row items-center h-[32px] block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('company') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg text-esg29"
                            placeholder="{{ __('Enter the company name') }}"
                            value="{{ old('company', request()->query('email')) }}" required autofocus />

                        <div class="absolute top-4 left-3 @error('company') text-red-500 @else text-[#dddddd] @enderror">
                        </div>

                        @error('company')
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

                <div class="">
                    <div class="relative z-0 mb-2 w-full group">
                        <div class="w-[40px] h-[20px] mb-1">
                            <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                                {{ __('Domain') }}
                            </span>
                        </div>
                        <input type="text" id="domain" name="domain"
                            class="placeholder-shown:text-[#8A8A8A] placeholder:text-[#8A8A8A] placeholder:text-base flex flex-row items-center h-[32px] block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('domain') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg text-esg29 appearance-none"
                            placeholder="{{ __('Enter the domain') }}" value="{{ old('domain', '') }}" required />
                        <span class="text-[#8A8A8A] absolute right-10 top-7 z-10 pt-[1px]">
                            .{{ config('tenancy.central_domains')[0] }}
                        </span>

                        <div class="absolute top-3 left-3 @error('domain') text-red-500 @else text-[#dddddd] @enderror">
                        </div>

                        @error('domain')
                            <div class="absolute top-2 right-12 text-red-500">
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

                <div class="">
                    <div class="w-[40px] h-[20px] mb-1">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Name') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-1 w-full group">
                        <input type="text" id="name" name="name"
                            class="placeholder-shown:text-[#8A8A8A] placeholder:text-[#8A8A8A] placeholder:text-base flex flex-row items-center h-[32px] block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('name') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg text-esg29"
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

                <div class="">
                    <div class="w-[40px] h-[20px] mb-1">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Email') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-1 w-full group">
                        <input type="email" id="email" name="email"
                            class="placeholder-shown:text-[#8A8A8A] placeholder:text-[#8A8A8A] placeholder:text-base flex flex-row items-center h-[32px] block py-[10px] px-[14px] w-full text-lg  bg-transparent border-[1px] @error('email') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg text-esg29"
                            placeholder="{{ __('Enter your email') }}"
                            value="{{ old('email', request()->query('email')) }}" required autofocus />

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
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="">
                    <div class="w-auto h-[20px] mb-2">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Password') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-2 w-full group">
                        <input type="password" id="password" name="password"
                            class="placeholder-shown:text-[#8A8A8A] placeholder:text-[#8A8A8A] placeholder:text-base h-[32px] block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg text-esg29"
                            placeholder="{{ __('Enter your password') }}" value="" required autocomplete />
                        <button type="button" id="show_password" class="absolute top-2 right-3">
                            @include('icons.eye', [
                                'color' => '#8A8A8A',
                                'width' => '24px',
                                'height' => '20px',
                            ])
                        </button>
                        <button type="button" hidden id="hide_password" class="absolute top-3 right-3">
                            @include('icons.eye-slash-fill', [
                                'color' => '#8A8A8A',
                                'width' => '28px',
                                'height' => '20px',
                            ])
                        </button>
                        <div class="pt-[2px]">
                            <span class="font-encodesans text-xs text-[#8A8A8A] font-normal">
                                {{ __('Must be at least 8 characters long.') }}
                            </span>
                        </div>
                        <div
                            class="absolute top-4 right-10 @error('password') text-red-500 @else text-[#dddddd] @enderror">
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

                <div class="">
                    <div class="w-auto h-[20px] mb-1">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Confirm password') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-2 w-full group">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="placeholder-shown:text-[#8A8A8A] placeholder:text-[#8A8A8A] placeholder:text-base h-[32px] block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg text-esg29"
                            placeholder="{{ __('Enter your password') }}" value="" required autocomplete />
                        <button type="button" id="show_confirmation" class="absolute top-2 right-3">
                            @include('icons.eye', [
                                'color' => '#8A8A8A',
                                'width' => '24px',
                                'height' => '20px',
                            ])
                        </button>
                        <button type="button" hidden id="hide_confirmation" class="absolute top-2 right-3">
                            @include('icons.eye-slash-fill', [
                                'color' => '#8A8A8A',
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
                    class="flex flex-row text-sm relative w-full items-center z-0 mb-1 w-full group @error('terms') text-red-500 @else text-[#8A8A8A] @enderror">
                    <input type="checkbox" id="terms" name="terms" class="accent-esg5 mr-2 valid:bg-esg5"
                        required />
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

                <div class="mt-2 w-full text-center">
                    <button type="submit"
                        class="w-full h-[32px] text-esg4 rounded-lg bg-esg5 py-1 px-12 text-base font-semibold leading-6 font-encodesans focus:outline-none">
                        {{ __('Register') }}
                    </button>
                </div>
            </div>
        </form>
        <div class="w-full flex flex-row items-center gap-[4px] place-content-center mt-2">
            <p class="text-[#8A8A8A] max-w text-sm leading-5 mt-[2px]">
                {{ __('Already have an account? ') }}
            </p>
            <div>
                <a href="{{ route('tenant.login') }}" class="text-esg28 leading-5 text-center text-sm">
                    {{ __('Log in') }}
                </a>
            </div>
        </div>
        <script nonce="{{ csp_nonce() }}">
            function flipType(inputId, buttonId) {
                var input1 = document.getElementById(inputId);
                var input2 = inputId == 'password' ? document.getElementById('password_confirmation') : document.getElementById(
                    'password');

                if (input1.type == 'password') {
                    input1.type = 'text';
                    input2.type = 'text';

                    if (buttonId == 'show_password' || buttonId == 'show_confirmation') {
                        document.getElementById('hide_password').style.display = 'block';
                        document.getElementById('hide_confirmation').style.display = 'block';
                        document.getElementById('show_password').style.display = 'none';
                        document.getElementById('show_confirmation').style.display = 'none';
                    } else {
                        document.getElementById('hide_password').style.display = 'none';
                        document.getElementById('hide_confirmation').style.display = 'none';
                        document.getElementById('show_password').style.display = 'block';
                        document.getElementById('show_confirmation').style.display = 'block';
                    }
                } else {
                    input1.type = 'password';
                    input2.type = 'password';

                    if (buttonId == 'hide_password' || buttonId == 'hide_confirmation') {
                        document.getElementById('show_password').style.display = 'block';
                        document.getElementById('show_confirmation').style.display = 'block';
                        document.getElementById('hide_password').style.display = 'none';
                        document.getElementById('hide_confirmation').style.display = 'none';
                    } else {
                        document.getElementById('hide_password').style.display = 'block';
                        document.getElementById('hide_confirmation').style.display = 'block';
                        document.getElementById('show_password').style.display = 'none';
                        document.getElementById('show_confirmation').style.display = 'none';
                    }
                }
            }
            document.getElementById("show_password").addEventListener('click', () => flipType('password', 'show_password'))
            document.getElementById("hide_password").addEventListener('click', () => flipType('password', 'hide_password'))
            document.getElementById("show_confirmation").addEventListener('click', () => flipType('password_confirmation',
                'show_confirmation'))
            document.getElementById("hide_confirmation").addEventListener('click', () => flipType('password_confirmation',
                'hide_confirmation'))
        </script>
    </div>
@endsection
