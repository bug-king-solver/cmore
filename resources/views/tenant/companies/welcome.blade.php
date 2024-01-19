@extends(customInclude('layouts.tenant'), ['mainBgColor' => 'bg-esg6'])


@section('content')
    <div class="font-encodesans text-center px-4 md:px-0">
        <div class="mt-10">
            <p class="text-2xl text-esg27 font-semibold">{{ __('Welcome!') }}</p>
            <p class="text-2xl text-esg27 font-semibold mt-12">{{ __('Congratulations for creating your 1st company!') }}</p>
            <p class="text-2xl text-esg27 font-semibold mt-12">
                {{ __('You are one step ahead in your journey in sustainability!') }}</p>
        </div>
        <div class="grid justify-center mt-16">
            @include('illustrator.company', ['class' => 'w-full'])
        </div>
        <div class="grid justify-center mt-20">
            <a href="{{ route('tenant.questionnaires.panel') }}"
                class="bg-esg5 p-5 font-encodesans font-semibold text-3xl text-esg27 rounded-lg">{{ __('Start Here') }}</a>
        </div>
    </div>
@endsection
