@extends(customInclude('layouts.auth'))

@section('content')

<div class="mt-32 flex h-full justify-center text-center text-xl leading-loose">
    <div>
        <h1 class="text-esg28 mb-2 text-5xl font-medium">{{ __('We\'re building your site.') }}</h1>

        <p class="text-esg27">{{ __('We are creating your account.') }}</p>
        <p class="text-esg27">{{ __('It doesn\'t take more than a minute.') }}</p>

        <a href="javascript:window.location.reload()" class="mt-4 inline-flex rounded-md shadow-sm">
              <button type="button" class="border-esg5 focus-visible:border-esg5 text-esg28 hover:bg-esg5 focus:bg-esg5 focus:ring-esg5 rounded-full border bg-transparent py-3 px-12 text-2xl font-bold uppercase tracking-[3px] hover:bg-opacity-20 focus:bg-opacity-20 focus:outline-none">
                {{ __('Sign In') }}
            </button>
        </a>
    </div>
</div>

@endsection
