<div>

    <x-slot name="header">
        <x-header title="{{ __('Reporting Periods') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <hr class="mb-10">

    <x-filters.filter-bar-v2 :filters="$availableFilters" :isSearchable="$isSearchable" />

    <x-tables.dynamic.table :resource="$this->resource" :header="[
        __('Name'),
        __('Custom Name'),
        __('Questionnaire Create'),
        __('Questionnaire Reporting'),
        __('Monitoring Create'),
        __('Monitoring Reporting'),
    ]" :body="[
        'name',
        'custom_name',
        'enableQuestionnaireFilter',
        'enableQuestionnaireReporting',
        'enableMonitoringFilter',
        'enableMonitoringReporting',
    ]" :buttons="[
        [
            'permission' => 'reporting-periods.view',
            'route' => 'tenant.reporting-periods.form',
            'params' => 'reportingPeriod',
            'icon' => 'icons.edit',
            'title' => 'Edit',
        ],
    ]" buttonHeader="Action"
        :links="$this->resource->links()" />

</div>
