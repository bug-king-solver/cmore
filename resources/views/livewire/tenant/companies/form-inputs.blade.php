@push('body')
    <script nonce="{{ csp_nonce() }}">
        window.livewire.on('resetInputField', () => {
            document.querySelector('[data-property-id="relation"]').tomselect.clear();
        });
    </script>
@endpush

<div>
    <x-form.form title="{{ $company->exists ? __('Edit') : __('Create a new company') }}" class="text-esg5 mb-5"
        cancel="{{ route('tenant.companies.index') }}" showButtons="{{ $showButtons }}">

        <x-tabs.panel gap="4" :tablist="$tabList" :activetab="$activeTab ?? null" />

        {{-- General tab --}}
        <div class="{{ $activeTab == $tabList[0]['slug'] || $activeTab == null ? '' : 'hidden' }}">
            <x-form.form-col input="text" id="name" label="{{ __('Company Name') }}"
                class="after:content-['*'] after:text-red-500"
                dataDescription="{{ __('Name that legally identifies the company in the course of its activity.') }}"
                dataTest="company-name" form_div_size="w-full" fieldClass="!bg-esg7/10 h-12 !text-esg8" />

            <x-form.form-col input="text" id="commercial_name" label="{{ __('Company commercial name') }}"
                dataDescription="{{ __('Commercial name that, in the context of marketing actions, identifies the company to its customers and suppliers.') }}"
                form_div_size="w-full" fieldClass="!bg-esg7/10 h-12 !text-esg8" dataTest="company-comm-name" />


            @if (tenant()->usersTypeSelfManaged && auth()->user()->is_internal_user)
                <div x-data="{ type: @this.type }">
                    @php $options =  \App\Models\Enums\Companies\Type::casesArray(); @endphp
                    <x-form.form-col input="select" id="type" label="{{ __('Type') }}"
                        class="after:content-['*'] after:text-red-500" :extra="['options' => $options]"
                        dataDescription="{{ __('Internal companies are the ones that belongs to the organization that owns the tenant. External companies are the opposite, for example: clients and suppliers.') }}"
                        dataTest="user-type" form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                        x-on:change="type = $event.target.value" />

                    @if (tenant()->companiesRelationSelfManaged)
                        <div x-cloak x-show="type == '{{ \App\Models\Enums\Companies\Type::EXTERNAL->value }}'">
                            <x-form.form-col name="relation" input="tomselect" id="relation"
                                label="{{ __('Relation') }}" :options="$relationsList" :items="$relation ?? []"
                                plugins="['remove_button', 'checkbox_options']"
                                placeholder="{{ __('Select the relation types') }}"
                                dataDescription="{{ __('External companies can be classified as: client or/and suppliers. Only internal users can see this option.') }}"
                                dataTest="company-external-company" form_div_size="w-full" />
                        </div>
                    @endif
                </div>
            @endif

            @if (!in_array('parent', $this->hiddenColumns))
                <x-form.form-col input="tomselect" id="parent" label="{{ __('Parent company') }}" :options="$companiesList"
                    items="{{ $parent ?? '' }}" limit="1" placeholder="{{ __('Select the parent company') }}"
                    dataDescription="{{ __('Does your company belongs to another company? If yes, select it. You will only see the companies you are assigned to.') }}"
                    dataTest="company-parent-company" form_div_size="w-full" />
            @endif

            <x-form.form-col input="tomselect" id="business_sector"
                label="{{ App\Models\Tenant\BusinessSectorType::labelForMain() }}" :optgroups="$businessSectorsList['optgroups'] ?? []" :options="$businessSectorsList['options'] ?? []"
                items="{{ $business_sector ?? '' }}" limit="1"
                placeholder="{{ __('Select the main business sector') }}"
                class="after:content-['*'] after:text-red-500"
                dataDescription="{{ __('Economic activity with the greatest representation in your turnover.') }}"
                dataTest="company-business-sector" form_div_size="w-full" />

            <x-form.form-col input="tomselect" id="businessSectorSecondary"
                label="{{ App\Models\Tenant\BusinessSectorType::labelForSecondary() }}" :optgroups="$businessSectorsList['optgroups'] ?? []"
                :options="$businessSectorsList['options'] ?? []" :items="$businessSectorSecondary ?: []" plugins="['no_backspace_delete', 'remove_button']"
                placeholder="{{ __('Select the secondary business sectors.') }}"
                dataDescription="{{ __('Indicate the company secondary economic activities.') }}"
                dataTest="business-sector-secondary" form_div_size="w-full" />

            @isset($customColumnsFields['cus_county'])
                @php($field = $customColumnsFields['cus_county'])
                <x-form.form-col input="{{ $field['type'] }}" id="customColumnsData.{{ $field['id'] }}"
                    label="{{ __($field['label']) }}" :optgroups="$field['optgroups'] ?? []" :options="$field['options'] ?? []"
                    items="{{ $customColumnsData[$field['id']] ?? '' }}" limit="1"
                    class="{{ in_array('required', array_merge(...$customColumnsRules)['customColumnsData.' . $field['id']] ?? []) ? ' after:content-[\'*\'] after:text-red-500' : '' }}"
                    form_div_size="w-full" />
            @endisset

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="">
                    <x-form.form-col input="tomselect" id="vat_country" label="{{ __('VAT Country') }}"
                        :options="$countriesList" items="{{ $vat_country ?? '' }}" limit="1"
                        placeholder="{{ __('Select the vat country') }}" class="after:content-['*'] after:text-red-500"
                        dataDescription="{{ __('The country of the company VAT number.') }}"
                        dataTest="company-vat-country" form_div_size="w-full" />
                </div>
                <div class="">
                    <x-form.form-col input="text" id="vat_number" label="{{ __('VAT number') }}"
                        class="after:content-['*'] after:text-red-500"
                        dataDescription="{{ __('The VAT number of the company.') }}" dataTest="company-vat-nr"
                        form_div_size="w-full" fieldClass="!bg-esg7/10 h-12 !text-esg8" />

                    <div class="text-xs text-gray-400 flex mt-2 mr-7 @error('vat_number') hidden @enderror">
                        <p> {{ __('This field only accepts integer numbers') }}
                        <p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="">
                    <x-form.form-col input="tomselect" id="country" label="{{ __('Headquarters country') }}"
                        :options="$countriesList" items="{{ $country ?? '' }}" limit="1"
                        placeholder="{{ __('Select the headquarters country') }}"
                        class="after:content-['*'] after:text-red-500" dataDescription="{!! __(
                            'Indicate the country in which the company is located in the company\'s articles of association or tax residence in the case of individual entrepreneurs.',
                        ) !!}"
                        dataTest="company-country" form_div_size="w-full" />
                </div>
                <div class="">
                    <x-form.form-col input="date" id="founded_at" label="{{ __('Founded at') }}"
                        dataTest="company-founded-at" form_div_size="w-full"
                        dataDescription="{{ __('The date of the company foundation.') }}"
                        fieldClass="!bg-esg7/10 h-12 !text-esg8" />
                </div>
            </div>

            <div class="">
                <x-form.form-col input="tomselect" id="vat_secundary"
                    label="{{ __('VAT number of subsidiaries included in the consolidation perimeter of the ESG exercise') }}"
                    :options="$vat_secundary_data" :items="$vat_secundary" plugins="['no_backspace_delete', 'remove_button']"
                    placeholder="{{ __('Insert the VAT number of the subsidiaries') }}"
                    dataDescription="{{ __('If it is not completed, the company is not including subsidiaries in the ESG exercise.') }}"
                    dataTest="vat-number-secondary" form_div_size="w-full" allowcreate="true" />
            </div>

            @if (!in_array('size', $this->hiddenColumns))
                <x-form.form-col input="tomselect" id="size" label="{{ __('Size') }}" :options="$companySizesList"
                    items="{{ $size ?? '' }}" limit="1" placeholder="{{ __('Select the company size') }}"
                    class="after:content-['*'] after:text-red-500"
                    dataDescription="{{ __('Large - employs more than 250 people and has an annual turnover of more than 50 million euros; Medium - employs between 51 and 250 people and the annual turnover does not exceed 50 million euros or the annual balance sheet total does not exceed 43 million euros; Small - employs between 11 and 50 people and the annual turnover or annual balance sheet total does not exceed 10 million euros; Micro - employs up to 10 people and the annual turnover or annual balance sheet total does not exceed 2 million euros.') }}"
                    dataTest="company-size" form_div_size="w-full" />
            @endif

            @foreach (array_except($customColumnsFields, ['cus_county']) as $field)
                @if ($field['type'] == 'radio')
                    <div class="mt-4 flex w-full items-center">
                        <label for="customColumnsData.{{ $field['id'] }}"
                            class="text-esg29 block text-lg font-normal">
                            {{ __($field['label']) }}
                        </label>
                    </div>
                    <div class="mt-2 w-full">
                        @foreach ($field['options'] ?? [] as $key => $optionLabel)
                            <x-inputs.radio value="{{ $key }}" id="customColumnsData.{{ $optionLabel }}"
                                name="customColumnsData.{{ $field['id'] }}"
                                wire:model="customColumnsData.{{ $field['id'] }}" label="{{ __($optionLabel) }}"
                                class="{{ !$loop->first ? 'ml-3' : '' }} mr-1" />
                        @endforeach
                    </div>
                @else
                    <x-form.form-col input="{{ $field['type'] }}" id="customColumnsData.{{ $field['id'] }}"
                        label="{{ $field['label'] }}" :optgroups="$field['optgroups'] ?? []" :options="$field['options'] ?? []"
                        items="{{ $customColumnsData[$field['id']] ?? '' }}" limit="1"
                        class="{{ in_array('required', array_merge(...$customColumnsRules)['customColumnsData.' . $field['id']] ?? []) ? ' after:content-[\'*\'] after:text-red-500' : '' }}"
                        form_div_size="w-full" />
                @endif
            @endforeach

            @if ($isOwner)
                <x-form.form-col input="tomselect" id="createdByUserId" label="{{ __('Owner') }}"
                    :options="$ownerUserList" :items="$createdByUserId" plugins="['remove_button']"
                    placeholder="{{ __('Select the owner') }}" limit="1"
                    dataDescription="{{ __('The user that owns this company in the software. By default, who created the company.') }}"
                    dataTest="companyname" form_div_size="w-full" />
            @endif

            <x-form.form-col input="tomselect" id="userablesId" label="{{ __('Users') }}" :options="$userablesList"
                :items="$userablesId" plugins="['remove_button', 'checkbox_options']"
                placeholder="{{ __('Select the users with access to the company') }}"
                dataDescription="{{ __('All users, other than the owner, who have access to the company. This option does not override permissions.') }}"
                dataTest="company-user" form_div_size="w-full" />

            @if (
                !tenant()->see_only_own_data ||
                    auth()->user()->isOwner())
                @if (tenant()->hasTagsFeature)
                    <x-form.form-col input="tomselect" id="taggableIds" label="{{ __('Tags') }}"
                        :options="$taggableList" :items="$taggableIds ?: []" plugins="['no_backspace_delete', 'remove_button']"
                        placeholder="{{ __('Select the tags for the company') }}"
                        dataDescription="{{ __('The tags for the company. They will help you segment the users. Only internal users can see this option.') }}"
                        dataTest="company-tag" form_div_size="w-full" />
                @endif

                <x-form.form-col input="color" id="color" label="{{ __('Main company color') }}"
                    dataDescription="{{ __('The main color for the company. They will help you in some features to clearly identify the company. Only internal users can see this option.') }}"
                    dataTest="company-color" form_div_size="w-full"form fieldClass="!bg-esg7/10 h-12 !text-esg8" />
            @endif
        </div>

        {{-- Location tab --}}
        <div class="{{ $activeTab == $tabList[1]['slug'] ? '' : 'hidden' }}">
            <div class="mt-5">
                <label class="text-esg29 block text-lg font-normal">{!! __('Locations') !!}</label>
            </div>
            <div>
                @foreach ($locations as $key => $value)
                    <div class="flex justify-between w-full mt-3">
                        <div class="">
                            <div class="flex items-center gap-5">
                                <div class="flex items-center gap-4 w-48">
                                    <label class="">{!! __('Is Headquarters ?') !!}</label>
                                    <x-form.form-col input="checkbox" id="locations.{{ $key }}.headquarters"
                                        form_div_size='w-full' placeholder="{{ __('Name') }}"
                                        dataTest="add-checklist-btn" />
                                </div>

                                <div class="grow">
                                    <x-form.form-col input="text" id="locations.{{ $key }}.name"
                                        form_div_size='w-full' placeholder="{{ __('Name') }}"
                                        dataTest="add-checklist-btn"
                                        fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
                                </div>
                            </div>
                            <div class="flex items-center gap-5">
                                <x-form.form-col input="number" id="locations.{{ $key }}.latitude"
                                    form_div_size='w-full' placeholder="{{ __('Latitude') }}"
                                    dataTest="add-checklist-btn" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

                                <x-form.form-col input="number" id="locations.{{ $key }}.longitude"
                                    form_div_size='w-full' placeholder="{{ __('Longitude') }}"
                                    dataTest="add-checklist-btn" fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

                                <x-form.form-col input="tomselect" id="locations.{{ $key }}.country_code"
                                    class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm !w-52"
                                    :options="$countryList" fieldClass="!w-52"
                                    items="{{ $locations[$key]['country_code'] ?? '' }}" limit="1"
                                    placeholder="{{ __('Select the country') }}" form_div_size="w-full"
                                    modelmodifier=".lazy" />

                                <x-form.form-col input="tomselect" id="locations.{{ $key }}.region_code"
                                    class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm !w-52"
                                    :options="$regionList[$key] ?? []" fieldClass="!w-52"
                                    items="{{ $locations[$key]['region_code'] ?? '' }}" limit="1"
                                    placeholder="{{ __('Select the region') }}" form_div_size="w-full"
                                    :wire_ignore="false"
                                    modelmodifier=".lazy" />

                                <x-form.form-col input="tomselect" id="locations.{{ $key }}.city_code"
                                    class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm !w-52"
                                    :options="$citiesList[$key] ?? []" fieldClass="!w-52"
                                    items="{{ $locations[$key]['city_code'] ?? '' }}" limit="1"
                                    placeholder="{{ __('Select the city') }}" form_div_size="w-full"
                                    :wire_ignore="false" />
                            </div>
                        </div>
                        <div class="flex-col">
                            @if ($loop->last)
                                <x-buttons.btn-icon
                                    class="float-right border-0 rounded-md mt-2 w-12 h-12 bg-esg7/10 grid place-content-center"
                                    wire:click.prevent="addNewAddressOptions({{ $key }})">
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
        </div>

        @include('livewire.tenant.wallet.payable', ['exists' => $company->exists])

    </x-form.form>
</div>
