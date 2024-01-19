@extends(customInclude('layouts.central'), ['isheader' => false, 'nav_background' => true, 'background_image' => false])
@section('content')
<div class="pt-44 pb-10">
    <div class="flex">
        <div class="flex-initial">
            <h1 class="text-left text-5xl font-semibold font-encodesans text-esg29"> {{ __('CONTACTS') }} </h1>
        </div>
        <div class="flex-auto ml-24">
            <p class="w-full border-b-2 border-esg5 mt-5 md:absolute">
                @include('icons.plus-bold', ['color' => color('esg5'), 'class' => 'absolute -mt-3.5 -ml-1.5', 'width' => 30, 'height' => 30])
            </p>
        </div>
    </div>
</div>

<div class="font-encodesans">
    <div class="text-right text-5xl font-semibold text-esg7">{{__('IRELAND')}}</div>
    <div class="">
        <iframe title="{{ __('Ireland office location') }}" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2380.122423870914!2d-6.171944783459059!3d53.37685898012529!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48670fa7194a80a9%3A0x33d349e2d7d6d533!2sFPR%20Chartered%20Accountants!5e0!3m2!1spt-PT!2spt!4v1661217931255!5m2!1spt-PT!2spt" width="100%" height="450" class="border-none border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<div class="mt-10 lg:mt-36">
    <div class="text-right text-5xl font-semibold text-esg7">{{__('PORTUGAL')}}</div>
    <div class="">
        <iframe title="{{ __('Portugal office location') }}" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3112.2422190815346!2d-9.15172068365046!3d38.735203564091435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd19330b1242451b%3A0x3de5ebb026d63ab6!2sAv.%20Duque%20de%20%C3%81vila%2064%207%C2%BAandar%2C%201050-066%20Lisboa!5e0!3m2!1spt-PT!2spt!4v1661217969823!5m2!1spt-PT!2spt" width="100%" height="450" class="border-none border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<div class="mt-14 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="mb-44">
        @include(customInclude('central.contact-forms.contact-us'))
    </div>
</div>
@endsection
