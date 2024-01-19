<div>
    @if ($status == 'ongoing')
        <div class="w-full flex flex-row gap-1 items-end">
            @can('questionnaires.submit')
                @if ($questionnaire->isCompleted())
                    <x-buttons.btn modal="questionnaires.modals.submit" :data="$data"
                        text="{{ __('Submit') }}" data-test="submit-questionnaire-btn"/>
                @endif
            @endcan

            <x-buttons.a-icon href="{{ $questionnaire->questionnaireScreen() }}" title="{!! __('View') !!}" class="cursor-pointer">
                @include('icons.continue', ['stroke' => color(16)])
            </x-buttons.a-icon>

            @if (isset($type) && $type == "page")
                <x-buttons.edit-href href="{{ $href }}" />
            @else
                <x-buttons.edit modal="{{ $modalprefix }}.modals.form"
                    :data="$data" class="cursor-pointer"
                    :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])" />
            @endif

            <x-buttons.trash modal="{{ $modalprefix }}.modals.delete"
                :data="$data" class="px-2 py-1"
                class="cursor-pointer"
                :param="json_encode(['stroke' => color(16)])" />
        </div>
    @else
        <div class="flex flex-row gap-1 items-end">
            @can('questionnaires.review')
                <x-buttons.btn-icon modal="questionnaires.modals.review" :data="$data"
                    title="{{ __('Review') }}"  data-test="review-questionnaire-btn">
                    <x-slot name="buttonicon">@include('icons.review', ['stroke' => color(16)])</x-slot>
                </x-buttons.btn-icon>
            @endcan

            @can('questionnaires.view')
                <x-buttons.a-icon
                    href="{{ $questionnaire->questionnaireScreen() }}"
                    class="mb-1 md:mb-0 !inline-block"
                    title="{!! __('View') !!}"
                    data-test="view-questionnaire-btn">
                    @include('icons.eye', ['color' => color(16)])
                </x-buttons.a-icon>
            @endcan

            @can('dashboard.view')
                <x-buttons.a-icon
                    href="{{ route('tenant.dashboard', $routeParams) }}"
                    class="mb-1 md:mb-0 !inline-block"
                    title="{{ __('Result') }}"
                    data-test="result-questionnaire-btn">
                    @include('icons.dashboard-v1')
                </x-buttons.a-icon>
            @endcan
        </div>
    @endif
</div>
