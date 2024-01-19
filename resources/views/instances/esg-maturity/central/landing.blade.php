@extends(customInclude('layouts.auth'))

@section('content')

    <div class="mt-10 flex h-full justify-center text-center text-xl leading-loose">
        <div>
            <p class="text-esg27">
                <a href="{{ route('central.tenants.login') }}" class="border-esg5 focus-visible:border-esg5 text-esg28 hover:bg-esg5 focus:bg-esg5 focus:ring-esg5 rounded-full border bg-transparent py-3 px-12 text-2xl font-bold uppercase tracking-[3px] hover:bg-opacity-20 focus:bg-opacity-20 focus:outline-none">
                    {{ __('Login') }}
                </a>
            </p>

            <p class="text-esg27 mt-10">
                <a href="{{ route('central.tenants.register') }}" class="border-esg5 focus-visible:border-esg5 text-esg28 hover:bg-esg5 focus:bg-esg5 focus:ring-esg5 rounded-full border bg-transparent py-3 px-12 text-2xl font-bold uppercase tracking-[3px] hover:bg-opacity-20 focus:bg-opacity-20 focus:outline-none">
                    {{ __('Register') }}
                </a>
            </p>
        </div>
    </div>

@endsection
