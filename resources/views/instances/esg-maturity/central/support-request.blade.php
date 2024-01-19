@extends(customInclude('layouts.central'), ['isheader' => false, 'nav_background' => true, 'background_image' => true])

@section('content')
    <div class="pt-32 pb-10">
        <h1 class="text-center text-6xl font-bold font-encodesans text-esg28"> {{ __('Support Request') }} </h1>
        <p class="text-center text-4xl font-semibold mt-9 text-esg28"> {{__('Our team is here to help you! ')}} </p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="shadow sm:rounded-lg">
            @include(customInclude('central.contact-forms.support'))
        </div>
    </div>
@endsection
