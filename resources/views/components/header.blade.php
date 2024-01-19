<div>
    @if (isset($this->breadcrumbs))
        <div {{ $attributes->merge(['class' => 'mt-5 mb-5 px-4 lg:px-0 ' . (!isset($questionnaire) && !isset($fixed) ? (tenant()->show_topbar ? 'pt-[6.5rem]' : 'pt-16') . ' h-auto' : '')]) }} class="justify-content content-start mx-auto grid h-full max-w-7xl items-center sm:px-6 lg:px-8">
            <x-breadcrums.container>
                @foreach ($this->breadcrumbs as $key => $item)
                    @if ($loop->first)
                        <x-breadcrums.top_li url="{{ $item['href'] ?? '' }}">
                            {{ $item['text'] }}
                        </x-breadcrums.top_li>
                    @elseif (!$loop->last)
                        <x-breadcrums.middle_li url="{{ $item['href'] ?? '' }}">
                            {{ $item['text'] }}
                        </x-breadcrums.middle_li>
                    @else
                        <x-breadcrums.last_li>
                            {{ $item['text'] }}
                        </x-breadcrums.last_li>
                    @endif
                @endforeach
            </x-breadcrums.container>
        </div>
    @endif

    <header
        @if(! isset($this->breadcrumbs)) {{ $attributes->merge(['class' => 'mt-5 mb-5 px-4 lg:px-0 ' . (!isset($questionnaire) && !isset($fixed) ? (tenant()->show_topbar ? 'pt-[6.5rem]' : 'pt-16') . ' h-auto' : '')]) }} @else class="mb-5" @endif>
        <div class="justify-content content-start mx-auto grid h-full max-w-7xl items-center sm:px-6 lg:px-8 {{ $extraclass ?? '' }}">
            <div class="w-full flex items-center justify-between {{ $classTitle ?? '' }}">
                @if (isset($left))
                    <div
                        class="{{ !isset($right) ? 'w-full' : '' }} {{ isset($chip) ? 'flex flex-row items-center gap-4' : '' }}">
                        @if (isset($title))
                            @if (isset($click) && $click != null)
                                <a href="{{ $click }}"
                                    class="{{ $textcolor ?? 'text-esg5 ' }} w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                                    @include('icons.back', [
                                        'color' => $iconcolor ?? color(5),
                                        'width' => '20',
                                        'height' => '16',
                                    ])
                                    {!! ucfirst($title) !!}
                                </a>
                            @else
                                <h1 class="{{ $textcolor ?? 'text-esg5 ' }} w-fit text-2xl font-bold"
                                    data-test="{{ $dataTest ?? '' }}">{!! ucfirst($title) !!}</h1>
                            @endif
                        @endif

                        {{ $left }}

                        @if (isset($chip))
                            <x-chip>
                                ID: {{ str_pad($chip, 6, '0', STR_PAD_LEFT) }}
                            </x-chip>
                        @endif

                    </div>
                @endif

                @if (isset($right))
                    <div class="flex items-center">{{ $right }}</div>
                @endif
            </div>
        </div>
    </header>

</div>
