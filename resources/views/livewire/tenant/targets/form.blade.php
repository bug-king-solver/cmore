<div class="px-4 md:px-0 target">
    <x-slot name="header">
        <x-header title="{{ __('Targets') }}" data-test="targets-header" click="{{ route('tenant.targets.index') }}">
            <x-slot name="left" ></x-slot>
        </x-header>
    </x-slot>
    <div class="w-full">
        <x-form.form title="{{ $target->exists ? __('Edit: :title', ['title' => $target->title]) : __('Create a new target') }}" class="text-esg5 mb-5" cancel="{{ route('tenant.targets.index') }}">

            <x-form.form-col input="text" id="title" label="{{ __('Title') }}"
                class="after:content-['*'] after:text-red-500"
                dataTest="target-title"
                form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                />

            <x-form.form-col input="tomselect" id="company" label="{{ __('Company') }}" class="after:content-['*'] after:text-red-500"
                :options="$companiesList"
                items="{{ $company ?? '' }}" limit="1" placeholder="{{ __('Select the company') }}"
                dataTest="target-company"
                form_div_size="w-full"
                />

            <x-form.form-col input="select" id="indicator" label="{{ __('Indicator') }}" class="after:content-['*'] after:text-red-500"
                :extra="['options' => array_pluck($indicatorsList, 'name', 'id')]"
                dataTest="target-indicator"
                form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                />

            <x-form.form-col input="text" id="our_reference" label="{{ __('Our reference') }}"
                dataTest="target-our-ref"
                form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                />

            <x-form.form-editor label="{{ __('Description') }}" id="description" form_div_size='w-full'
                value="{{ $this->description }}"
                class="after:content-['*'] after:text-red-500"
                dataTest="target-dcp"
                form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                />

            <x-form.form-col input="text" id="goal" label="{{ __('Goal') }}"
                class="after:content-['*'] after:text-red-500"
                dataTest="target-goal"
                form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.form-col input="date" id="start_date" label="{{ __('Start Date') }}"
                    dataTest="target-start-date"
                    form_div_size="w-full"
                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                    />

                <x-form.form-col input="date" id="due_date" label="{{ __('Due Date') }}"
                    class="after:content-['*'] after:text-red-500"
                    dataTest="target-due-date"
                    form_div_size="w-full"
                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                    />
            </div>

            <x-form.form-col input="tomselect" id="userablesId" label="{{ __('Users') }}"
                :options="$userablesList"
                plugins="['remove_button', 'no_backspace_delete']"
                placeholder="{{ __('Select users') }}"
                :items="$userablesId"
                :wire_ignore="false"
                dataTest="target-users"
                form_div_size="w-full"
                />

            @if ($isOwner)
                <x-form.form-col input="tomselect" id="createdByUserId" label="{{ __('Owner') }}"
                    :options="$ownerUserList"
                    :items="$createdByUserId"
                    plugins="['remove_button']"
                    placeholder="{{ __('Select the owner') }}"
                    limit="1"
                    dataTest="target-owner"
                    form_div_size="w-full"
                    />
            @endif

            <x-form.form-col input="tomselect" id="taggableIds" label="{{ __('Tags') }}"
                :options="$taggableList"
                :items="$taggableIds ?: []"
                plugins="['no_backspace_delete', 'remove_button']"
                placeholder="{{ __('Select tags') }}"
                dataTest="target-tags"
                form_div_size="w-full"
                />

        </x-form.form>
    </div>
</div>
