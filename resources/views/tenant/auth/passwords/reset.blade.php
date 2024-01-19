@extends(customInclude('layouts.tenant-auth'))

@section('content')
<div class="pt-4 pb-2 flex flex-col flex-wrap items-center w-[360px]">
    <h2 class="pl-4 text-center text-2xl"><span class="text-esg8 font-semibold leading-9 font-encodesans font-medium">{{ __('Create new password') }}</span></h2>
    <p class="text-[#667085] max-w mt-2 text-center text-sm leading-5">
       <span>
        {{  __('Your new password must be different from previous used passwords.') }}
       </span>
    </p>
</div>
<div class="sm:mx-auto sm:w-full sm:max-w-md w-[300px] xl:w-[360px] lg:w-[320px] md:w-[280px]">

    <form method="POST" action="{{ route('tenant.password.update') }}" x-data="{showPassword: false}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="">
                <div class="relative z-0 mb-2 w-full group hidden">
                    <input type="email" id="email" name="email" class="placeholder-shown:text-esg11 placeholder:text-esg11 flex flex-row items-center h-[44px] block py-[10px] px-[14px] w-full text-lg text-esg8 bg-transparent border-[1px] @error('email') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg" placeholder="{{ __('Enter your email') }}" value="{{ old('email', request()->query('email')) }}" required autofocus />
                    <div class="absolute top-4 left-3 @error('email') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('email')
                    <div class="absolute top-3 right-3 text-red-500">
                        <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="ml-3 mt-1 text-xs text-red-500">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
        </div>

        <div class="">
            <div class="h-[20px] mb-2">
                <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                    {{ __('Password') }}
                </span>
                </div>
                <div class="relative z-0 mb-2 w-full group">
                    <input x-bind:type="showPassword ? 'text' : 'password'" id="password" name="password" class="placeholder-shown:text-esg11 placeholder:text-esg11 h-[44px] block py-[10px] px-[14px] w-full text-lg text-esg8 bg-transparent border-[1px] @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg" placeholder="{{ __('Enter your password') }}" value="" required autocomplete />
                    <button type="button" id="show_password" x-on:click="showPassword = !showPassword" x-show="showPassword == false" class="absolute top-3 right-3">
                        @include('icons.eye', ['color' => '#8A8A8A', 'width' => '24px', 'height' => '20px'])
                    </button>
                    <button type="button" id="hide_password" x-on:click="showPassword = !showPassword" x-show="showPassword != false" class="absolute top-3 right-3">
                        @include('icons.eye-slash-fill', ['color' => '#8A8A8A', 'width' => '28px', 'height' => '20px'])
                    </button>
                    <div class="pt-[2px]">
                        <span class="font-encodesans text-xs text-esg11 font-normal">
                            {{ __('Must be at least 8 characters long.') }}
                        </span>
                    </div>
                    <div class="absolute top-4 right-10 @error('password') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('password')
                        <div class="absolute top-3 right-10 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-3 mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
            </div>
        </div>

        <div class="mt-5">
                <div class="w-auto h-[20px] mb-2">
                    <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                        {{ __('Confirm password') }}
                    </span>
                </div>
                <div class="relative z-0 mb-2 w-full group">
                    <input x-bind:type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" class="placeholder-shown:text-esg11 placeholder:text-esg11 h-[44px] block py-[10px] px-[14px] w-full text-lg text-esg8 bg-transparent border-[1px] @error('password_confirmation') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg" placeholder="{{ __('Enter your password') }}" value="" required autocomplete />
                    <button type="button" id="show_confirmation" x-on:click="showPassword = !showPassword" x-show="showPassword == false"  class="absolute top-3 right-3">
                        @include('icons.eye', ['color' => '#8A8A8A', 'width' => '24px', 'height' => '20px'])
                    </button>
                    <button type="button" id="hide_confirmation" class="absolute top-3 right-3" x-on:click="showPassword = !showPassword" x-show="showPassword != false" >
                        @include('icons.eye-slash-fill', ['color' => '#8A8A8A', 'width' => '28px', 'height' => '20px'])
                    </button>
                    <div class="pt-[2px]">
                        <span class="font-encodesans text-xs text-esg11 font-normal">
                            {{ __('Both passwords must match.')}}
                        </span>
                    </div>
                    <div class="absolute top-2 right-10 @error('password_confirmation') text-red-500 @else text-[#dddddd] @enderror">
                    </div>

                    @error('password_confirmation')
                        <div class="absolute top-2 right-5 text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
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

        <div class="mt-4 w-full text-center">
            <button type="submit" class="font-encodesans w-full h-[44px] text-esg4 bg-esg5 rounded-lg py-3 px-12 text-2xl text-base leading-6 font-semibold focus:outline-none">
                {{ __('Reset password') }}
            </button>
        </div>
    </form>
</div>
@endsection
