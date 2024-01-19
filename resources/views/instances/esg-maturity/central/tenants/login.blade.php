@extends(customInclude('layouts.auth'))
@section('content')

    <div class="pt-4 pb-10">
        <h2 class="text-center text-2xl"><span class="text-esg28">{{ __('Hello!') }}</span> <span class="text-esg27">{{ __('Fill in the credentials to enter the platform:') }}</span></h2>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="shadow sm:rounded-lg">
            <form method="POST" action="{{ route('central.tenants.login.submit') }}">
                @csrf

                <div class="">
                    <div class="relative z-0 mb-6 w-full group">
                        <input type="email" id="email" name="email" class="h-14 block pt-2.5 pl-12 pb-0 pr-4 w-full text-lg text-[#dddddd] bg-transparent border-1 @error('email') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-full text-[#dddddd] appearance-none peer" placeholder=" " value="{{ old('email', request()->query('email')) }}" required autofocus />
                        <label for="email" class="peer-focus:text-esg28 absolute top-3 left-12 -z-10 origin-[0] -translate-y-3 scale-75 transform text-lg text-[#dddddd] duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-3 peer-focus:scale-75">{{ __('Email') }}</label>

                        <div class="absolute top-4 left-3 @error('email') text-red-500 @else text-[#dddddd] @enderror">
                            <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <div class="absolute top-4 right-3 text-[#dddddd]">
                            <a id="clear-email" class="cursor-pointer">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                        </div>
                        @error('email')
                            <div class="absolute top-4 right-12 text-red-500">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-3 mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button type="submit" class="border-esg5 focus-visible:border-esg5 text-esg28 hover:bg-esg5 focus:bg-esg5 focus:ring-esg5 rounded-full border bg-transparent py-3 px-12 text-2xl font-bold uppercase tracking-[3px] hover:bg-opacity-20 focus:bg-opacity-20 focus:outline-none">
                        {{ __('Login') }}
                    </button>
                </div>

                @if (config('app.self_registration') && Route::has('central.tenants.register'))
                    <div class="mt-6 text-center">
                        <a href="{{ route('central.tenants.register') }}" class="text-esg28 hover:bg-esg5 focus:bg-esg5 rounded-full bg-transparent py-3 px-12 text-2xl font-bold uppercase tracking-[3px] underline hover:bg-opacity-20 focus:bg-opacity-20 focus:outline-none">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <script nonce="{{ csp_nonce() }}">
        document.getElementById("clear-email").addEventListener('click', () => document.getElementById('email').value = '')
    </script>
@endsection
