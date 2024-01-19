
<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('List of Questionnaires') }}" data-test="questionnaires-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('questionnaires.create')
                    <div class="float-right">
                        <x-buttons.a-icon href="{{ route('tenant.questionnaires.form') }}" data-test="add-questionnaires-btn" class="flex place-content-end cursor-pointer">
                            <div class="flex gap-2 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                                @include('icons.add', ['width' => 12, 'height' => 12, 'color' => color(4)])
                                <label class="uppercase font-medium text-sm cursor-pointer">{{ __('Add') }}</label>
                            </div>
                        </x-buttons.a-icon>
                    </div>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    <x-menus.panel :buttons="[
        [
            'route' => 'tenant.questionnaires.panel',
            'label' => __('PANEL'),
            'icon' => 'panel',
        ],
        [
            'route' => 'tenant.questionnaires.index',
            'label' => __('Ongoing'),
            'params' => [
                's[questionnaire_status][0]' => 'ongoing'
            ],
            'reference' => 'ongoing',
            'icon' => 'performance',
        ],
        [
            'route' => 'tenant.questionnaires.index',
            'label' => __('Submitted'),
            'params' => [
                's[questionnaire_status][0]' => 'submitted'
            ],
            'reference' => 'submitted',
            'icon' => 'checkbox',
        ],
    ]" activated='tenant.questionnaires.index' :reference="$status" />

    <x-filters.filter-bar :filters="$availableFilters" :isSearchable="$isSearchable" />

    <x-cards.questionnaire.list :questionnaires="$questionnaires" />

    @if ($questionnaires->isEmpty())
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-md">{{ __('No questionnaires available yet. Click the “Add” button to create a new one.') }}</h3>
        </div>
    @endif

</div>
