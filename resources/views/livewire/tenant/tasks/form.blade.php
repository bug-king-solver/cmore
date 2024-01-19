<div class="px-4 md:px-0 tasks">
    <x-slot name="header">
        <x-header title="{{ $task->exists ? __('Edit :name', ['name' => $task->name]) : __('New Task') }}"
            data-test="task-header" click="{{ route('tenant.users.tasks.index') }}" textcolor="text-esg6">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="w-full">
        <x-form.form class="!text-esg5 mb-5" btnClass="!bg-esg5 !cursor-pointer"
            cancel="{{ route('tenant.users.tasks.index') }}"
            discard="{{ $task->exists ? route('tenant.users.tasks.index') : false }}"
            saveButtontext="{{ $task->exists ? __('Save') : __('Create') }}">
            <x-form.form-col input="text" id="name" label="{{ __('Title') }}"
                placeholder="{{ __('Set a name to your Task') }}" form_div_size='w-full'
                class="after:content-['*'] after:text-red-500" dataTest="task-name"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <x-form.form-editor label="{{ __('Description') }}" id="description" form_div_size='w-full'
                value="{{ $this->description }}" class="after:content-['*'] after:text-red-500" dataTest="task-dcp"
                form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 !text-esg8 !h-32"
                placeholder="{{ __('Add a description') }}" />

            <x-form.label label="{{ __('Add checklist') }}" dataTest="task-checklist" />

            <div>
                @foreach ($taskCheckList as $key => $value)
                    <div class="flex justify-around w-full">
                        <div class="flex-col w-full pr-2">
                            @if ($loop->last)
                                <x-form.form-col input="text" id="taskCheckList.{{ $key }}"
                                    form_div_size='w-full' keyDownEnter="addNewChecklistOptions({{ $key }})"
                                    placeholder="{{ __('Add a checklist') }}" dataTest="task-checklist"
                                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                            @else
                                <x-form.form-col input="text" id="taskCheckList.{{ $key }}"
                                    form_div_size='w-full' placeholder="{{ __('Add a checklist') }}"
                                    dataTest="add-checklist-btn" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                            @endif
                        </div>
                        <div class="flex-col">
                            @if ($loop->last)
                                <x-buttons.btn-icon
                                    class="float-right border-0 rounded-md mt-2 w-12 h-12 bg-esg7/10 grid place-content-center"
                                    wire:click.prevent="addNewChecklistOptions({{ $key }})">
                                    @include('icons.add', [
                                        'color' => color(16),
                                        'width' => '20',
                                        'height' => '20',
                                    ])
                                </x-buttons.btn-icon>
                            @else
                                <x-buttons.btn-icon
                                    class="float-left border-0 rounded-md mt-2 w-12 h-12 bg-esg7/10 grid place-content-center"
                                    wire:click.prevent="removeChecklistOptions({{ $key }})">
                                    @include('icons.trash', [
                                        'stroke' => color(16),
                                        'width' => '20',
                                        'height' => '20',
                                    ])
                                </x-buttons.btn-icon>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-form.form-col input="number" id="weight" label="{{ __('Weight') }}" min="1"
                        max="100" class="in-range:border-green-500" form_div_size='w-full'
                        placeholder="{{ __('Add a weight') }}"
                        class="after:content-['*'] after:text-red-500 placeholder:text-gray-300" dataTest="task-weight"
                        fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                </div>

                <div>
                    <x-form.form-col input="date" id="due_date" label="{{ __('Due date') }}" form_div_size='w-full'
                        class="after:content-['*'] after:text-red-500" dataTest="task-due-date"
                        fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label
                        class="text-esg29 mt-4 block text-lg font-normal after:content-['*'] after:text-red-500 placeholder:text-gray-300">{{ __('Associate') }}</label>
                    <div class="flex flex-row gap-3 items-center !bg-[#C4C4C41A] min-h-[3rem] mt-2 rounded p-1">
                        @foreach ($this->entitiesModelList as $entityModel)
                            <div>
                                <x-inputs.radio id="{{ $entityModel['id'] }}" wire:model="entity" name="entity"
                                    value="{{ strtolower($entityModel['id']) }}" />

                                <label for="{{ $entityModel['id'] }}"
                                    class="inline-flex text-sm font-medium text-gray-700">
                                    {{ $entityModel['title'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <x-form.form-col input="tomselect" id="taskableId"
                        label="{{ $this->taskableId ? __(':name', ['name' => ucfirst($this->entity)]) : __('Select Associate') }}"
                        :options="$this->entitiesList" :items="$this->taskableId ?: []" limit="1"
                        placeholder="{{ __('Select the associate') }}" form_div_size='w-full' :wire_ignore="false"
                        key="{{ time() . $this->entity }}" class="after:content-['*'] after:text-red-500"
                        dataTest="task-associable" />
                </div>
            </div>

            @if ($task->exists)
                <div class="flex items-center p-3 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 mt-4"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">{!! __('Info') !!}</span>
                    <div>
                        <span class="font-bold">{{ __('Warning') }}</span>
                        <p>{{ __('By changing the associate, this task will no longer be linked/associated with the previous item.') }}
                        </p>
                    </div>
                </div>
            @endif

            <x-form.form-col input="tomselect" id="userablesId" label="{{ __('Team members') }}" :options="$userablesList"
                :items="$userablesId" plugins="['remove_button', 'checkbox_options']"
                placeholder="{{ __('Select the members') }}" form_div_size='w-full' dataTest="task-team-members" />

            <x-form.form-col input="tomselect" id="taggableIds" label="{{ __('Tags') }}" :options="$taggableList"
                :items="$taggableIds ?: []" plugins="['no_backspace_delete', 'remove_button']"
                placeholder="{{ __('Select the tags') }}" form_div_size='w-full' dataTest="task-tags" />



        </x-form.form>
    </div>
</div>
