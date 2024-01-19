<div>
    <x-slot name="header">
        <x-header title="{{ __('Report') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="flex items-center justify-between mb-10">
        <div>
            <h3 class="font-encodesans text-esg5 w-fit text-xl font-semibold uppercase px-4 lg:px-0">
                {{ $report->sources->name }} {{ __('Table') }}</h3>
            @if ($report->questionnaire)
                <span class="text-esg16">Linked to Questionnaire <a
                        href="{{ $report->questionnaire->questionnaireScreen() }}"
                        class="underline">{{ $report->questionnaire->type->name }} {{ __('Of') }}
                        {{ $report->company->name }}</a></span>
            @endif
        </div>
        <div>
            <div class="flex flex-row justify-end">
                @if ($edit)
                    @php $data = json_encode(["sourceReport" => $report->id]); @endphp
                    <x-buttons.a-icon-simple
                        class="py-2 px-4 !text-esg16 font-lato mr-2 ml-2 border rounded border-esg16 leading-6"
                        href="{{ route('tenant.exports.index') }}">{{ __('Cancel') }}</x-buttons.a-icon-simple>

                    <x-buttons.btn-text class="py-2 px-4 !text-white font-lato ml-2 bg-esg28 border rounded"
                        wire:click="saveData">{{ __('Save') }}</x-buttons.btn-text>
                @else
                    <x-buttons.a-icon-simple
                        class="py-2 px-4 !text-esg16 font-lato border rounded border-esg16 mr-2 leading-6"
                        href="{{ route('tenant.exports.show', ['id' => $report->id, 'action' => 'edit']) }}">
                        {{ __('Edit') }}
                    </x-buttons.a-icon-simple>

                    <x-buttons.btn-text class="py-2 px-4 !text-white font-lato border rounded bg-esg28 ml-2"
                        wire:click="printData">{{ __('Print') }}</x-buttons.btn-text>
                @endif
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mt-4">
            <x-alerts.success class="w-full">{{ session('success') }}</x-alerts.success>
        </div>
    @endif

    <div class="">
        <div class="flex flex-row justify-between items-start items-center gap-2 p-1 mt-7 bg-[#EBEEF4] rounded-lg">
            <div class="overflow-hidden flex gap-2 tabcont transition-all duration-300">
                @foreach ($reportCategories as $label => $category)
                    <div class="flex-none px-4 h-10 cursor-pointer text-sm rounded flex items-center gap-2"
                        :class="'{{ $reportCategories[$activeCategory]['label'] }}' == '{{ $category['label'] }}' ?
                        'bg-esg4 text-esg6 font-extrabold border-esg5' : 'text-esg16'"
                        wire:click="changeCategory('{{ $category['label'] }}')">
                        {!! __($category['label']) !!}
                    </div>
                @endforeach
            </div>
            <div class="w-14 flex">
                <div class="left-arrow cursor-pointer" onclick="moveNavigation('tabcont', 'left')">
                    @include('icons.arrow-slider-left')
                </div>
                <div class="right-arrow cursor-pointer" onclick="moveNavigation('tabcont', 'right')">
                    @include('icons.arrow-slider-right')
                </div>
            </div>
        </div>

        @foreach ($items as $categoryId => $subcategoris)
            @foreach ($subcategoris as $subcategoryId => $itemCatergory)
                <div class="mt-6">
                    <div class="accordion-container gap-2">
                        <x-accordian.export title="{{ $subcategoryId }}" titleclass="text-center" class="grid gap-5">
                            <x-slot:slot>

                                @foreach ($itemCatergory as $i => $item)
                                    <div class="accordion-child">
                                        @php $title = __($item['reference']  . " ". $item['title']  . " | ". $item['subtitle'] ); @endphp
                                        <x-accordian.export title="{!! __($title) !!}" placement="justify-left"
                                            titleclass="text-center normal-case" rounded="rounded-none"
                                            background="bg-esg6/10" helper="{{ __($item['description']) }}"
                                            :reference="$item['reference']">
                                            <x-slot:slot>
                                                @foreach ($item['source_indicator'] as $j => $indicator)
                                                    <x-export.question text="{!! __($indicator['question']) !!}"
                                                        value="{{ $indicator['indicator_value'] }}"
                                                        id="items.{{ $categoryId }}.{{ $subcategoryId }}.{{ $i }}.source_indicator.{{ $j }}.indicator_value"
                                                        modelmodifier=".lazy" />
                                                @endforeach
                                            </x-slot:slot>
                                        </x-accordian.export>
                                    </div>
                                @endforeach

                            </x-slot:slot>
                        </x-accordian.export>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
