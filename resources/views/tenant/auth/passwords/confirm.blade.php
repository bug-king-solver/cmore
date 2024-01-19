@extends(customInclude('layouts.tenant'))

@section('content')
    <div class="container mx-auto">
        <div class="flex flex-wrap justify-center">
            <div class="w-full max-w-sm">
                <div class="bg-esg4 flex flex-col break-words rounded border border-2 shadow-md">

                    <div class="mb-0 bg-gray-200 py-3 px-6 font-semibold text-gray-700">
                        {{ __('Confirm password') }}
                    </div>

                    <form class="w-full p-6" method="POST" action="{{ route('tenant.password.confirm') }}">
                        @csrf

                        <p class="leading-normal">
                            {{ __(' ') }}
                        </p>

                        <div class="my-6 flex flex-wrap">
                            <label for="password" class="mb-2 block text-sm font-bold text-gray-700">
                                {{ __('Password') }}:
                            </label>

                            <input id="password" type="password" class="form-input w-full @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <p class="mt-4 text-xs italic text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap items-center">
                            <button type="submit" class="focus:shadow-outline rounded bg-blue-500 py-2 px-4 font-bold text-gray-100 hover:bg-blue-700 focus:outline-none">
                                {{ __('Confirm password') }}
                            </button>

                            @if (Route::has('tenant.password.request'))
                                <a class="whitespace-no-wrap ml-auto text-sm text-blue-500 no-underline hover:text-blue-700" href="{{ route('tenant.password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
