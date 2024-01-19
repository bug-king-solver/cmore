<div>
    <x-modals.form-delete>

        <x-slot name="title">
            {{ __('Delete Group') }}
        </x-slot>

        @if ($hasChildren)
            <div class="text-esg8 pt-14 font-medium mt-30" wire:ignore>
                {{ __('The group has :childrens childrens .', ['childrens' => $group->childrens]) }}
            </div>

            <div>
                <x-form.form-col input="select" id="groupDeleteOptions" label="{{ __('What do you want to do ?') }}"
                    :extra="['options' => $hasChildrenOptions]" modelmodifier=".lazy" />
            </div>

            @if ($this->groupDeleteOptions != null)
                <div>
                    <x-form.form-col input="select" id="confirmDelete" label="{{ __('Are you sure ?') }}" :extra="[
                        'options' => [
                            'yes' => 'Yes',
                            'no' => 'No',
                        ],
                    ]"
                        :wire_ignore="false" />
                </div>
            @endif
        @endif

        <x-slot name="question">
            {{ __('Do you want to delete the group ":group"?', ['group' => $group->name]) }}
        </x-slot>
        </x-modals.form-delete>
</div>
