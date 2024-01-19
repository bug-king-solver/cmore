<div class="physcal_risk" x-data="{ open: false }">
    <x-modals.form title="{{ $physicalRisks->exists ? __('Edit Geography') : '' }}" class="!text-esg6"
        buttonPosition="justify-end" buttonColor="!bg-[#44724D]" :showButtons="false">

        <x-form.form-col input="text" id="name" label="{{ __('Name') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm" form_div_size="w-full"
            fieldClass="!border !border-esg7/50 h-12 !text-esg8" />

        <x-form.form-col input="tomselect" id="activity"
            label="{{ App\Models\Tenant\BusinessSectorType::labelForMain() }}" :optgroups="$sectorList['optgroups']" :options="$sectorList['options'] ?? []"
            items="{{ $activity ?? '' }}" limit="1" placeholder="{{ __('Select the activity') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm"
            dataDescription="{{ __('The business sector of the geography.') }}" form_div_size="w-full"
            :wire_ignore="false" />

        <div class="flex items-center gap-5">
            <div class="grow">
                <x-form.form-col input="tomselect" id="locationId" label="{{ __('Location') }}"
                    class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm" :options="$locationList"
                    items="{{ $locationId ?? '' }}" limit="1" placeholder="{{ __('Select location') }}"
                    form_div_size="w-full" />
            </div>

            <div class="pt-10">
                <span>{!! __('Or') !!}</span>
            </div>

            <div class="w-fit pt-10">
                <x-buttons.btn-icon-text class="!bg-esg4 !text-esg8 !py-2.5 hover:!bg-esg7/10 transition duration-150"
                    x-on:click="open = ! open">
                    <x-slot name="buttonicon">
                        @include('icons.tables.plus', [
                            'color' => color(8),
                            'width' => 12,
                            'height' => 12,
                        ])
                    </x-slot>
                    <span class="ml-2">{!! __('Add a new location') !!}</span>
                </x-buttons.btn-icon-text>
            </div>


        </div>

        @if ($errors->has('locations'))
            <div class="flex items-center gap-5">
                <div class="mt-2">
                    <span class="text-red-500 text-xs ">{{ $errors->first('locations') }}</span>
                </div>
            </div>
        @endif

        <div class="mt-5 bg-esg7/10 p-4" x-show="open">
            <div class="w-full">
                @foreach ($locations as $key => $value)
                    <div class="">
                        <div>
                            <x-form.form-col input="text" id="locations.{{ $key }}.name"
                                form_div_size='w-full' placeholder="{{ __('Name') }}" dataTest="add-name-btn"
                                fieldClass="!border !border-esg7/50 h-12 !text-esg8" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5  mt-2">
                            <x-form.form-col input="number" id="locations.{{ $key }}.latitude"
                                form_div_size='w-full' placeholder="{{ __('Latitude') }}" dataTest="add-latitude-btn"
                                fieldClass="!border !border-esg7/50 h-12 !text-esg8" />

                            <x-form.form-col input="number" id="locations.{{ $key }}.longitude"
                                form_div_size='w-full' placeholder="{{ __('Longitude') }}" dataTest="add-longitude-btn"
                                fieldClass="!border !border-esg7/50 h-12 !text-esg8" />

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-2">
                            <x-form.form-col input="tomselect" id="locations.{{ $key }}.country_code"
                                class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm !w-full"
                                :options="$countryList" fieldClass="!w-full"
                                items="{{ $locations[$key]['country_code'] ?? '' }}" limit="1"
                                placeholder="{{ __('Select the country') }}" form_div_size="w-full"
                                modelmodifier=".lazy" />

                            <x-form.form-col input="tomselect" id="locations.{{ $key }}.region_code"
                                class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm !w-full"
                                :options="$regionList[$key] ?? []" fieldClass="!w-full"
                                items="{{ $locations[$key]['region_code'] ?? '' }}" limit="1"
                                placeholder="{{ __('Select the region') }}" form_div_size="w-full" :wire_ignore="false"
                                modelmodifier=".lazy" />

                            <x-form.form-col input="tomselect" id="locations.{{ $key }}.city_code"
                                class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm !w-full"
                                :options="$citiesList[$key] ?? []" fieldClass="!w-full"
                                items="{{ $locations[$key]['city_code'] ?? '' }}" limit="1"
                                placeholder="{{ __('Select the city') }}" form_div_size="w-full" :wire_ignore="false"
                                modelmodifier=".lazy" />
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        <x-form.form-col input="tomselect" id="relevant" label="{{ __('Relevance') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm" :options="$relevantList"
            items="{{ $relevant ?? '' }}" limit="1" placeholder="{{ __('Select the relevance Options') }}"
            dataDescription="{{ __('Indicate the level of relevance of this location.') }}" form_div_size="w-full"
            tooltipModel="true" wire:model='relevant' />

        <div class="p-2" id="relevanceDescription">
            @if ($relevanceDescription)
                <span class="text-sm leading-3 text-[#757575]" wire:poll>{{ $relevanceDescription }}</span>
            @endif
        </div>

        <x-form.form-col input="textarea" id="note" label="{{ __('Relevance Justification') }}"
            class="!text-esg8 !font-normal !text-sm resize-none" form_div_size="w-full"
            dataDescription="{{ __('Justification for the level of relevance.') }}" tooltipModel="true"
            fieldClass="!border !border-esg7/50 h-auto !text-esg8" />

        <div class="mt-4 flex justify-center gap-5">
            <x-buttons.save class="!normal-case !text-sm !font-medium px-8 hover:bg-esg5/80" saveMethod="saveAndClear"
                text="{!! __('Add another') !!}">
                </x-save>

                <x-buttons.save class="!normal-case !text-sm !font-medium px-8"
                    text="{!! __('Add') !!}"></x-save>
        </div>
    </x-modals.form>
</div>
