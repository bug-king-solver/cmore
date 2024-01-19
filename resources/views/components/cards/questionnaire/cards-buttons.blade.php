<div>
    @if ($status == 'ongoing')
        <div class="w-full flex flex-row gap-1 items-center">
            @can('questionnaires.submit')
                @if ($questionnaire->hasSubmitRoute() && $questionnaire->isCompleted())
                    <x-buttons.btn modal="questionnaires.modals.submit" :data="$data" text="{{ __('Submit') }}"
                        data-test="submit-questionnaire-btn" />
                @endif
            @endcan

            @if ((!isset($only) || in_array('view', $only)) && (!isset($except) || !in_array('view', $except)))
                <x-buttons.a-icon href="{{ $questionnaire->questionnaireWelcome() }}" title="{!! __('View') !!}"
                    class="cursor-pointer">
                    @include('icons.continue', ['stroke' => color(16)])
                </x-buttons.a-icon>
            @endif

            @if ((!isset($only) || in_array('edit', $only)) && (!isset($except) || !in_array('edit', $except)))
                @if (isset($type) && $type == 'page')
                    <x-buttons.edit-href href="{{ $href }}"
                        :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])"/>
                @else
                    <x-buttons.edit modal="{{ $modalprefix }}.modals.form" :data="$data" class="cursor-pointer"
                        :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])" />
                @endif
            @endif

            @if (
                $questionnaire->type->canDuplicate() &&
                    (!isset($only) || in_array('duplicate', $only)) &&
                    (!isset($except) || !in_array('duplicate', $except)))
                @can('questionnaires.create')
                    <x-buttons.a-icon href="javascript:;" modal="questionnaires.modals.duplicate" :data="$data"
                        title="{{ __('Duplicate') }}" class="cursor-pointer">
                        @include('icons.duplicate', [
                            'width' => 20,
                            'height' => 20,
                            'stroke' => color(16),
                            'strokewidth' => 1.5,
                        ])
                    </x-buttons.a-icon>
                @endcan
            @endif

            @if ((!isset($only) || in_array('delete', $only)) && (!isset($except) || !in_array('delete', $except)))
                <x-buttons.trash modal="{{ $modalprefix }}.modals.delete" :data="$data" class="px-2 py-1"
                    class="cursor-pointer" :param="json_encode(['stroke' => color(16)])" />
            @endif
        </div>
    @else
        {{-- QUESTIONNAIRE SUBMITED --}}
        <div class="flex flex-row gap-1 items-end">
            @can('questionnaires.review')
                <x-buttons.btn-icon modal="{{ $questionnaire->reviewModal() }}" :data="$data"
                    title="{{ __('Review') }}" data-test="review-questionnaire-btn">
                    <x-slot name="buttonicon">@include('icons.review', ['stroke' => color(16)])</x-slot>
                </x-buttons.btn-icon>
            @endcan

            @can('questionnaires.view')
                <div class="export-dropdown inline-block relative w-6">
                    <x-buttons.btn-icon title="{{ __('Export') }}" data-test="export-questionnaire-btn"
                        data-dropdown-toggle="dropdown-menu"
                        class="!py-0.5 absolute w-full -top-[25px] right-1">
                        @include('icons.export', ['color' => color(16)])
                    </x-buttons.btn-icon>

                    <div id="dropdown-menu"
                        class="hidden z-10 w-44 bg-white rounded-lg border-[0.3px] border-esg55 divide-y divide-gray-100 shadow-lg">
                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                            <li class="relative p-2 hover:bg-gray-100">
                                <x-buttons.a-icon target="_blank"
                                    href="{{ route('tenant.questionnaires.export.pdf', ['questionnaire' => $questionnaire->id]) }}"
                                    class="mb-1 md:mb-0 !inline-block whitespace-nowrap" title="{{ __('Export to Pdf') }}">
                                    Export as PDF
                                </x-buttons.a-icon>
                            </li>
                            <li class="relative p-2 hover:bg-gray-100">
                                <x-buttons.a-icon target="_blank"
                                    href="{{ route('tenant.questionnaires.export.csv', ['questionnaire' => $questionnaire->id]) }}"
                                    class="mb-1 md:mb-0 !inline-block whitespace-nowrap" title="{{ __('Export to CSV') }}">
                                    Export as CSV
                                </x-buttons.a-icon>
                            </li>
                        </ul>
                    </div>
                </div>
            @endcan

            @can('questionnaires.view')
                <x-buttons.a-icon href="{{ $questionnaire->questionnaireScreen() }}" class="mb-1 md:mb-0 !inline-block"
                    title="{!! __('View') !!}" data-test="view-questionnaire-btn">
                    @include('icons.eye', ['color' => color(16)])
                </x-buttons.a-icon>
            @endcan

            @if ($questionnaire->isSubmitted() && tenant()->hasReportingFeature)
                <x-buttons.a-icon wire:click="sourceReport({{ $questionnaire->id }})"
                    class="mb-1 md:mb-0 !inline-block" title="{{ __('Report') }}"
                    data-test="report-questionnaire-btn">
                    @include('icons/tables/download', ['color' => color(16)])
                </x-buttons.a-icon>
            @endif

            @can('dashboard.view')
                <x-buttons.a-icon href="{{ $questionnaire->questionnaireReport() }}" class="mb-1 md:mb-0 !inline-block"
                    title="{{ __('Result') }}" data-test="result-questionnaire-btn">
                    @include('icons.dashboard-v1')
                </x-buttons.a-icon>
            @endcan

            @if ($questionnaire->type->canDuplicate())
                @can('questionnaires.create')
                    <x-buttons.a-icon href="javascript:;" modal="questionnaires.modals.duplicate" :data="$data"
                        title="{{ __('Duplicate') }}" class="cursor-pointer">
                        @include('icons.duplicate', [
                            'width' => 20,
                            'height' => 20,
                            'stroke' => color(16),
                            'strokewidth' => 1.5,
                        ])
                    </x-buttons.a-icon>
                @endcan
            @endif
        </div>
    @endif
</div>
