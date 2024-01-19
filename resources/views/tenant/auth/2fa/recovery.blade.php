@extends(customInclude('layouts.tenant-auth'))

@section('content')
    <div class="pt-4 pb-6">
        <h2 class="text-center sm:text-lg"><span class="text-esg8 font-semibold leading-9 font-encodesans text-2xl font-medium">{{ __('Code verification') }}</span></h2>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md w-[360px] md:w-[360px]">
        <div>
            <form method="POST" action="{{ route('tenant.2fa.recovery') }}">
                @csrf

                <div class="">
                    <div class="w-auto mb-2">
                        <span class="text-esg8 not-italic text-base leading-5 font-encodesans font-medium">
                            {{ __('Use a recovery token') }}
                        </span>
                    </div>
                    <div class="relative z-0 mb-2 w-full group">
                        <input
                            type="text"
                            id="token"
                            class="placeholder-shown:text-esg11 placeholder:text-esg11 text-esg8 flex flex-row items-center block py-[10px] px-[14px] w-full text-lg bg-transparent border-[1px] @error('code') border-red-500 focus:border-esg5 focus:ring-esg5 @else border-[#dddddd] focus:border-esg5 focus:ring-esg5 @enderror rounded-lg"
                            name="token"
                            placeholder="{{ __('Recovery Token') }}"
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

                <div class="mt-5 w-full text-center">
                    <button type="submit" class="w-full text-esg4 rounded-lg bg-esg5 py-[10px] px-12 text-base font-semibold leading-6 font-encodesans focus:outline-none">
                        {{ __('Log In') }}
                    </button>
                </div>
            </form>
            <div class="flex flex-col items-center">
                <div class="flex flex-row">
                    <div class="relative">
                        <span class="w-40 border-b-2 border-esg16 mt-4 absolute right-0"></span>
                    </div>
                    <h4 class="text-sm m-2 text-esg8">OR</h4>
                    <div class="relative">
                        <span class="w-40 border-b-2 border-esg16 mt-4 absolute left-0"></span>
                    </div>
                </div>
                <div class="flex w-full">
                    <form action="{{ route('tenant.logout') }}" method="post" class="w-full">
                        @csrf
                        <button type="submit" wire:click="{{ route('tenant.logout') }}" class="w-full border border-esg16 text-esg16 rounded-lg py-[10px] px-12 text-base font-semibold leading-6 font-encodesans focus:outline-none">
                            {{ __('Return to log in') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
