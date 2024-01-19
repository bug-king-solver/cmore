@extends(customInclude('layouts.auth'))

@section('content')
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="shadow sm:rounded-lg">
            <div class="text-center">
                <a href="{{ route('tenant.login') }}" class="w-full text-esg4 rounded-lg bg-esg5 py-[10px] px-12 text-base font-semibold leading-6 font-encodesans focus:outline-none">
                    {{ __('Log In') }}
                </a>
            </div>
        </div>
    </div>
@endsection
