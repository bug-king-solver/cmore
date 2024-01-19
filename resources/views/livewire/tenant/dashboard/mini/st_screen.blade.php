<div>
{{-- TODO fix the descriptions  --}}
<div class="" x-data="{ selected: {{$readiness['current_level']}}, activeClass : 'text-lg text-esg8', current_level : {{$readiness['current_level']}} }">
    @php $text = json_encode([]); @endphp
    <div class="text-esg5 font-encodesans flex flex-col text-base  uppercase font-bold">{!! __('Readiness level') !!}</div>
    <div class="grid grid-cols-1 gap-10 mt-6 px-9">
        <div class="relative">
            <div class="flex justify-center items-start">
                @if ($readiness['current_level'] == 5)
                    @include('icons.dashboard.9.readiness.5')
                @else
                    @include('icons.dashboard.9.readiness.' . ($readiness['current_level'] === 0 ? 1 : $readiness['current_level']) )
                @endif
            </div>
        </div>

        <div class="flex items-end">
            <ol class="relative text-esg8">
                {{-- Level 5 --}}
                <li class="mb-5" :class="selected === 5 ? activeClass : 'ml-6'">
                    <div x-cloak x-show="selected === 5" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                        <label class="text-lg font-bold text-esg5 grid justify-center"> {{ __('Ready for the next step') }} </label>
                        <p class="text-base font-normal {{ $readiness['current_level'] != 5 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('This means that you are more than ready to try out the in-depth questionnaire.') }}</p>
                    </div>
                </li>

                {{-- Level 4 --}}
                <li class="mb-5" :class="selected === 4 ? activeClass : 'ml-6'">
                    <div x-cloak x-show="selected === 4" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                        <label class="text-lg font-bold text-esg5 grid justify-center"> {{ __('Maturity Ready') }} </label>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisation is highly committed to complying with and respecting the ESG pillars and has begun to define and implement strategies that contribute to reducing, mitigating and preventing their impacts.') }}</p>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('It is notable for its external collaborations in the field of sustainability and for its contribution to sustainable development objectives within the scope of its activity.') }}</p>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The efforts made by the organisation give it credibility and reputation, setting an example for others.') }}</p>
                    </div>
                </li>

                {{-- Level 3 --}}
                <li class="mb-5" :class="selected === 3 ? activeClass : 'ml-6'">
                    <div x-cloak x-show="selected === 3" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                        <label class="text-base font-bold text-esg5 grid justify-center"> {{ __('Performance Ready') }} </label>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisation monitors and communicates its performance in relation to a set of indicators relating to the three ESG pillars. It has implemented a strategy for receiving complaints in the event of non-compliance or irregularities, reflecting its commitment to stakeholders.') }}</p>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisations performance is oriented towards transparency, credibility and evolution and, as such, continuous investment in ESG issues is encouraged.') }}</p>
                    </div>
                </li>

                {{-- Level 2 --}}
                <li class="mb-5" :class="selected === 2 ? activeClass : 'ml-6'">

                    <div x-cloak x-show="selected === 2" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                        <label class="text-lg font-bold text-esg5 grid justify-center"> {{ __('Knowledge Ready') }} </label>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 1 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisation is committed to implementing a set of training programmes, practices and processes that ensure the implementation of the policies defined by the organisation in the areas of health and safety at work, ethics and conduct, human rights, anti-corruption and conflicts of interest.') }}</p>
                        <p class="text-base font-normal {{ $readiness['current_level'] < 1 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('There is a clear effort to improve the organisations conditions with regard to ESG themes, and there is room for progress.') }}</p>
                    </div>
                </li>

                {{-- Level 1 --}}
                <li class="mb-1" :class="selected === 1 ? activeClass : 'ml-6'" >
                    <div x-cloak x-show="selected === 1" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                        <label class="text-lg font-bold text-esg5 grid justify-center"> {{ __('Awareness Ready') }} </label>
                        <p class="text-base font-normal text-esg16 mt-1">{{ __('The organisation is committed to the core themes of the ESG pillars and has defined and implemented a set of basic policies relating to them.') }}</p>
                        <p class="text-base font-normal text-esg16 mt-1">{{ __('The organisation has the necessary foundations to ensure its future growth and development, and a commitment to acquiring additional knowledge is recommended.') }}</p>
                    </div>
                </li>
            </ol>
        </div>
    </div>
</div>
</div>
