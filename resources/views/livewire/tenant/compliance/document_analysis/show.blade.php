@push('head')
    <x-comments::styles />
@endpush

<div>
    <x-slot name="header">
        <x-header title="{{ __('Compliance') }}" data-test="compliance-header" click="{{ route('tenant.compliance.document_analysis.index') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
            </x-slot>
        </x-header>
    </x-slot>
</div>

<div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0">
    <div class="text-esg5 text-sm font-bold">
        {{ $result->type->title }}

        <span class="ml-1 text-xs inline-flex items-center p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg32 text-esg8 rounded">
            @include('partials/labels/level', ['level' => $result->compliance_level])
        </span>
    </div>
    <p class="mt-1 font-xs text-esg8">{{ $result->type->description }}</p>

    <!-- Accordian -->

    <div id="accordion-flush"
        data-accordion="collapse"
        data-active-classes="bg-esg37"
        data-inactive-classes=""
        class="mt-6 mb-36">
        @foreach ($domainsWithoutSnippets as $domain)
            @php
                $snippets = $domain->snippets;
                $loopEncoded = json_encode($loop);
                $terms = $snippets ? $snippets->pluck('term') : [];
            @endphp
            @if(!$domain->description && empty($terms->toArray()))
                @continue
            @endif
            <x-accordian.legislation.heading loop="{!! $loopEncoded !!}">
                <x-slot name="heading">
                    <div class="w-full md:w-1/2 ">
                        {{ $domain->title}}
                    </div>
                    <div class="w-full md:w-1/5   inline-flex items-center">
                        @include('icons/document_analysis/snippet_counter', ['count' => $snippets->count() ]) {{ $snippets->count() > 0 ? ($snippets->count() . ' ' .__('snippetes found')) : __('Snippet not found') }}
                    </div>
                    <div class="w-full md:w-1/2">
                        @if ($snippets->count() === 0)
                            {{-- <button class="text-xs text-esg6 bg-transparent font-medium py-1.5 px-3 border border-esg6 rounded hover:bg-esg6 hover:text-white hover:border-transparent" data-modal-toggle="createtask-modal">Create Task</button> --}}
                        @endif
                    </div>
                </x-slot>
            </x-accordian.legislation.heading>
            <x-accordian.legislation.body loop="{!! $loopEncoded !!}">
                <div class="py-3 px-4 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                    <p class="mb-4">
                        <strong>{{ __('Description:') }}</strong> {{ $domain->description }}
                    </p>
                    <p class="mb-4">
                        <strong>{{ __('Keywords') }}: </strong>
                        @foreach ($terms as $term)
                            <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">{{ $term }}</span>
                        @endforeach
                    </p>
                    <div class="flex">
                        <div class="mr-3"><strong>{{ __('Snippets') }}:</strong></div>
                        <div class="w-full text-esg35">
                            @foreach ($snippets as $snippet)
                                <p>
                                    {!! $snippet->text !!}
                                    <span class="mx-2">|</span>
                                    <strong>{{ __('Page :page', ['page' => $snippet->page]) }}</strong>
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-accordian.legislation.body>
        @endforeach

        @foreach ($domainsWithSnippets as $domain)
            @php
                $snippets = $domain->snippets;
                $loop->iteration = 'withSnippets'.$loop->iteration;
                $loopEncoded = json_encode($loop);
                $terms = $snippets ? $snippets->pluck('term') : [];
            @endphp
            <x-accordian.legislation.heading loop="{!! $loopEncoded !!}">
                <x-slot name="heading">
                    <div class="w-full md:w-1/2 ">
                        {{ $domain->title}}
                    </div>
                    <div class="w-full md:w-1/5   inline-flex items-center">
                        @include('icons/document_analysis/snippet_counter', ['count' => $snippets->count() ]) {{ $snippets->count() > 0 ? ($snippets->count() . ' ' .__('snippetes found')) : __('Snippet not found') }}
                    </div>
                    <div class="w-full md:w-1/2">
                        @if ($snippets->count() === 0)
                            {{-- <button class="text-xs text-esg6 bg-transparent font-medium py-1.5 px-3 border border-esg6 rounded hover:bg-esg6 hover:text-white hover:border-transparent" data-modal-toggle="createtask-modal">Create Task</button> --}}
                        @endif
                    </div>
                </x-slot>
            </x-accordian.legislation.heading>
            <x-accordian.legislation.body loop="{!! $loopEncoded !!}">
                <div class="py-3 px-4 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                    <p class="mb-4">
                        <strong>{{ __('Description:') }}</strong> {{ $domain->description }}
                    </p>
                    <p class="mb-4">
                        <strong>{{ __('Keywords') }}: </strong>
                        @foreach ($terms as $term)
                            <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">{{ $term }}</span>
                        @endforeach
                    </p>
                    <div class="flex">
                        <div class="mr-3"><strong>{{ __('Snippets') }}:</strong></div>
                        <div class="w-full text-esg35">
                            @foreach ($snippets as $snippet)
                                <p>
                                    {!! $snippet->text !!}
                                    <span class="mx-2">|</span>
                                    <strong>{{ __('Page :page', ['page' => $snippet->page]) }}</strong>
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-accordian.legislation.body>
        @endforeach
    </div>
    {{-- TODO:  --}}
    {{-- <div class="flex items-center justify-end text-[10px]">
        @include('icons/document_analysis/warning-icon') {{ __('Found something wrong with the analysis?') }}

        <x-buttons.btn-text modal="compliance.document-analysis.modals.report" class="bg-transparent font-medium p-1.5 ml-2 border border-esg8 rounded text-esg8">
        {{__('Report an issue')}}
        </x-buttons.btn-text>
    </div> --}}
</div>
