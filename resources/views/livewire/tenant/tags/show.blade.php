@push('head')
    <x-comments::styles />
@endpush

<div>
    <x-slot name="header">
        <x-header  title="{{ __('Tag') }}" data-test="tag-header" click="{{ route('tenant.tags.index') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="px-4 md:px-0 divide-y">
        <div class="w-full flex flex-row gap-2 items-center">
            <span class="text-esg8 text-2xl font-bold">
                {{ $tag->name }}
            </span>
            <div class="w-4 h-4 rounded-full" style="background-color: {{ $tag->color }}"></div>
        </div>
    </div>

    <hr>

    <div class="px-4 md:px-0 pt-10 w-full">
        <div class="w-full flex flex-row gap-2 items-center">
            <span class="font-encodesans text-esg5  w-fit text-xl font-semibold uppercase px-4 lg:px-0">
                {{ __('Questionnaires Ongoing') }}
            </span>
        </div>

        <div class="pt-6 w-full">
            <x-tables.dynamic.table :resource="$tag
                ->questionnaires()
                ->where('completed_at', null)
                ->get()" :header="[__('Name'), __('Type'), __('From'), __('To'), __('Created at'), __('Progress')]" :body="[
                'company.name',
                'type.name',
                'from->format(\'Y-m-d\')',
                'to->format(\'Y-m-d\')',
                'created_at->format(\'Y-m-d\')',
                'progress %',
            ]" :buttons="[
                [
                    'permission' => 'questionnaire.view',
                    'route' => 'tenant.questionnaires.show',
                    'params' => 'questionnaire',
                    'icon' => 'icons.continue',
                ],
            ]" />
        </div>
    </div>

    <div class="px-4 md:px-0 pt-10 w-full">
        <div class="w-full flex flex-row gap-2 items-center">
            <span class="font-encodesans text-esg5  w-fit text-xl font-semibold uppercase px-4 lg:px-0">
                {{ __('Questionnaires Submitted') }}
            </span>
        </div>

        <div class="pt-6 w-full">
            <x-tables.dynamic.table :resource="$tag
                ->questionnaires()
                ->where('completed_at', '!=', null)
                ->get()" :header="[__('Name'), __('Type'), __('From'), __('To'), __('Created at'), __('Progress')]" :body="[
                'company.name',
                'type.name',
                'from->format(\'Y-m-d\')',
                'to->format(\'Y-m-d\')',
                'created_at->format(\'Y-m-d\')',
                'progress %',
            ]" :buttons="[
                [
                    'permission' => 'dashboard.view',
                    'route' => 'tenant.dashboard',
                    'params' => 'questionnaire',
                    'icon' => 'icons.report',
                ],
            ]" />
        </div>
    </div>

    <div class="px-4 md:px-0 pt-10">
        <div class="w-full flex flex-row gap-2 items-center">
            <span class="font-encodesans text-esg5  w-fit text-xl font-semibold uppercase px-4 lg:px-0">
                {{ __('Targets') }}
            </span>
        </div>

        <div class="pt-6 w-full">
            <x-tables.dynamic.table :resource="$tag->targets" :header="[__('Title'), __('Start date'), __('Due date')]" :body="['title', 'start_date->format(\'Y-m-d\')', 'due_date->format(\'Y-m-d\')']" :buttons="[
                [
                    'permission' => 'target.view',
                    'route' => 'tenant.targets.show',
                    'params' => 'target',
                ],
            ]" />
        </div>
    </div>

    <div class="px-4 md:px-0 pt-10">
        <div class="w-full flex flex-row gap-2 items-center">
            <span class="font-encodesans text-esg5  w-fit text-xl font-semibold uppercase px-4 lg:px-0">
                {{ __('Users') }}
            </span>
        </div>

        <div class="pt-6 w-full">
            <x-tables.dynamic.table :resource="$tag->users" :header="[__('Name'), __('Email')]" :body="['name', 'email']" />
        </div>
    </div>
</div>
