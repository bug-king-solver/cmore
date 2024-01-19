<div>
    <header {{ $attributes->merge(['class' => 'bg-esg6 px-4 lg:px-0 ' . (! isset($questionnaire) ? (tenant()->show_topbar ? 'pt-[6.5rem]' : 'pt-16') . ' h-44' : '')]) }}>
        <div class="justify-content mx-auto flex h-full max-w-7xl items-center sm:px-6 lg:px-8">
            <div class="w-full flex justify-between {{ $classTitle ?? '' }}">
                @if (isset($left))
                    <div class="{{ ! isset($right) ? 'w-full' : '' }}">
                        @if (isset($title)) <h1 class="text-esg27 w-fit text-2xl font-bold">{!! ucfirst($title) !!}</h1> @endif
                        {{ $left }}
                    </div>
                @endif

                @if (isset($right))
                    <div class="flex items-center">{{ $right }}</div>
                @endif
            </div>
        </div>
    </header>
</div>
