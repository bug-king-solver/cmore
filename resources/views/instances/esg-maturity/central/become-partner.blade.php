@extends(customInclude('layouts.central'), ['isheader' => false, 'nav_background' => true, 'background_image' => true])

@section('content')
    <div class="text-center">
        <h1 class="text-esg28 text-7xl font-encodesans font-bold pt-48 text-center">
            {{__('Become a Partner')}}
        </h1>

        <h3 class="text-esg27 text-3xl font-semibold font-encodesans mt-14">{{__('Be a game changer and become an ESG Maturity Partner.')}} <br/> {{__('As core business or a complement to your activity, becoming an')}} <span class="text-esg28 font-bold">{{__('ESG Maturity Partner')}}</span> {{__('is your chance to make a positive impact and generate additional revenue while delivering more value to your customers in a transformational and unavoidable topic:')}} <span class="text-esg28 font-bold">{{__('ESG')}}</span></h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mt-24">

        <div class="grid justify-items-center md:justify-items-end h-60">
            <div class="bg-esg6 rounded-2xl w-60 h-60 transition ease-in-out hover:-translate-y-1 hover:scale-125 duration-300">
                <div class="text-center p-5">
                    @include('icons/plus', ['class' => 'inline-block mr-1'])
                    @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
                </div>
                <div class="px-10 pb-10 pt-8 text-center">
                    <p class="text-2xl font-semibold text-esg10 w-40">{{__('Up to 50% commision')}}</p>
                </div>
            </div>
        </div>

        <div class="grid justify-items-center mt-20 md:mt-0 h-60">
            <div class="bg-esg6 rounded-2xl w-60 h-60 transition ease-in-out hover:-translate-y-1 hover:scale-125 duration-300">
                <div class="text-center p-5">
                    @include('icons/plus', ['class' => 'inline-block mr-1'])
                    @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
                    @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg1')])
                </div>
                <div class="px-10 pb-10 text-center">
                    <p class="text-2xl font-semibold text-esg10 w-40">{{__('100% of additional related services')}}</p>
                </div>
            </div>
        </div>

        <div class="grid justify-items-center md:justify-items-start mt-20 md:mt-0 h-60">
            <div class="bg-esg6 rounded-2xl w-60 h-60 transition ease-in-out hover:-translate-y-1 hover:scale-125 duration-300">
                <div class="text-center p-5">
                    @include('icons/plus', ['class' => 'inline-block mr-1'])
                    @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg2')])
                    @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg1')])
                    @include('icons/plus', ['class' => 'inline-block', 'color' => color('esg5')])
                </div>
                <div class="px-10 pb-10 text-center">
                    <p class="text-2xl font-semibold text-esg10 w-40">{{__('Access to exclusive ESG content from C-MORE')}}</p>
                </div>
            </div>
        </div>
    </div>

    <h1 class="text-esg28 text-4xl font-encodesans font-bold text-center mt-24">
        {{ __('Let\'s impact the world:') }}
    </h1>

    <div class="grid justify-items-center mb-32 pt-14">
        @include(customInclude('central.contact-forms.partner'))
    </div>
@endsection
