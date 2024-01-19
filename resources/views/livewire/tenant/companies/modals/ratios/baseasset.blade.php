<div class="company_assets">
    <x-modals.form title="{{ isset($asset->id) ? __('Edit asset') : __('Add asset') }}" class="!text-esg6"
        buttonPosition="justify-end" buttonColor="!bg-[#44724D]">

        @if ($isSimulation)
            <div class="w-full mt-6">
                <x-form.form-col input="tomselect" id="assetType" label="{{ __('Asset type') }}"
                    class="after:content-['*'] after:text-red-500 !text-esg8  !text-sm !-mt-4 !font-bold"
                    :options="$assetTypeList ?? []" items="{{ $assetType ?? '' }}" limit="1"
                    placeholder="{{ __('Select the type') }}" form_div_size="w-full" modelmodifier=".lazy" />
            </div>
        @endif

        @if (!empty($this->taxonomyList))
            <div class="w-full mt-6">
                <label for="fillMode"
                    class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500 !text-esg8 !text-sm">
                    {{ __('Fill mode?') }}
                </label>

                <div class="flex items-center gap-5 w-full mt-6">

                    <x-form.form-col input="radio" id="fillMode" flex="true" :showError="false" name="fillMode"
                        wire:model="fillMode" value="1" label="{!! __('Automatic') !!}"
                        class="!text-esg8  !text-sm !rounded-full" form_div_size="w-full !-mt-4" />

                    <x-form.form-col input="radio" id="fillMode" flex="true" :showError="false" name="fillMode"
                        wire:model="fillMode" value="0" label="{!! __('Manual') !!}"
                        class="!text-esg8  !text-sm !rounded-full" form_div_size="w-full !-mt-4" />
                </div>

                @error('fillMode')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        @endif

        <div class="w-full mt-6 {{ isset($assetType) && isset($fillMode) && $assetType != 3 ? '' : 'hidden' }}">
            <label for="purpose"
                class="text-esg29 block !font-bold text-sm after:content-['*'] after:text-red-500 !text-esg8 !text-sm">{{ __('Does it have a specific purpose?') }}
            </label>
            <div class="flex items-center gap-5 w-full mt-6">

                <x-form.form-col input="radio" id="purpose" flex="true" :showError="false" name="purpose"
                    wire:model="purpose" value="1" label="{!! __('Yes') !!}"
                    class="!text-esg8  !text-sm !rounded-full" form_div_size="w-full !-mt-4" />

                <x-form.form-col input="radio" id="purpose" flex="true" :showError="false" name="purpose"
                    wire:model="purpose" value="0" label="{!! __('No') !!}"
                    class="!text-esg8  !text-sm !rounded-full" form_div_size="w-full !-mt-4" />
            </div>

            @error('purpose')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Automatic Mode -->
        <div class="{{ isset($fillMode) && $fillMode ? '' : 'hidden' }}">

            <div class="w-full mt-6 {{ $purpose ? '' : 'hidden' }}">
                <label for="fillMode"
                    class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500 !text-esg8  !text-sm">
                    {{ __('Taxonomy') }}
                </label>

                <div :wire:ignore wire:key='{{ time() }}'>
                    <x-form.form-col input="tomselect" id="taxonomy" class="after:content-['*'] after:text-red-500"
                        :options="$taxonomyList" items="{{ $taxonomy ?? '' }}" limit="1"
                        placeholder="{{ __('Select the taxonomy') }}" form_div_size="w-full" :wire_ignore="false" modelmodifier=".lazy" />
                </div>
            </div>

            <div class="w-full mt-6 {{ $purpose ? '' : 'hidden' }}">
                <label for="fillMode"
                    class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500 !text-esg8  !text-sm">{{ __('Activity') }}</label>

                <x-form.form-col input="tomselect" id="taxonomyActivity" class="after:content-['*'] after:text-red-500"
                    :options="$activityList ?? []" items="{{ $taxonomyActivity ?? '' }}" limit="1"
                    placeholder="{{ __('Select the activity') }}" form_div_size="w-full" :wire_ignore="false" modelmodifier=".lazy" />

            </div>
        </div>

        <!-- Manual Mode -->
        <div class="">
            <div class="w-full mt-6 {{ isset($fillMode) && !$fillMode ? '' : 'hidden' }}">
                <label for="eligible"
                    class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500  !text-sm !text-esg8">{{ __('Is it eligible to the Taxonomy?') }}</label>
                <div class="flex items-center gap-5 w-full mt-6">
                    <x-form.form-col input="radio" id="eligible" flex="true" :showError="false" name="eligible"
                        wire:model="eligible" value="1" label="{!! __('Yes') !!}"
                        class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />

                    <x-form.form-col input="radio" id="eligible" flex="true" :showError="false" name="eligible"
                        wire:model="eligible" value="0" label="{!! __('No') !!}"
                        class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />
                </div>
                @error('eligible')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="w-full mt-6 {{ isset($fillMode) && !$fillMode ? '' : 'hidden' }}">
                <label
                    class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500  !text-sm !text-esg8">{{ __('Is it aligned to the Taxonomy?') }}</label>
                <div class="flex items-center gap-5 w-full mt-6">
                    <x-form.form-col input="radio" id="aligned" flex="true" :showError="false" name="aligned"
                        wire:model="aligned" value="1" label="{!! __('Yes') !!}"
                        class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />

                    <x-form.form-col input="radio" id="aligned" flex="true" :showError="false" name="aligned"
                        wire:model="aligned" value="0" label="{!! __('No') !!}"
                        class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />
                </div>
                @error('aligned')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="w-full mt-6 {{ $isSimulation && isset($fillMode) ? '' : 'hidden' }}">
                <label class="text-esg29 block text-sm !font-bold !font-bold !text-sm !text-esg8">{{ __('Totais:') }}
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2 {{ isset($fillMode) ? '' : 'hidden' }}">
                    <div class="w-full">
                        <label
                            class="block text-lg !font-bold after:content-['*'] after:text-red-500 !text-esg8  !text-sm">{{ __('Asset amount') }}
                        </label>
                        <div class="flex items-center w-full">
                            <x-form.form-col input="text" id="amount"
                                class="!text-esg8  !text-sm !rounded-r-none" form_div_size="w-full"
                                placeholder="{!! __('Insert value') !!}" />
                            <span
                                class="text-base text-esg8 h-[38px] w-8 text-center grid place-content-center !rounded-r-md mt-1 bg-esg7/20 border border-esg67 border-l-0">€​</span>
                        </div>
                    </div>

                    <div class="w-full">
                        <label
                            class="block text-lg !font-bold after:content-['*'] after:text-red-500 !text-esg8  !text-sm">{{ __('Change in the financial year') }}</label>
                        <div class="flex items-center w-full">
                            <x-form.form-col input="text" id="flow"
                                class="!text-esg8  !text-sm !rounded-r-none" form_div_size="w-full"
                                placeholder="{!! __('Insert value') !!}" />
                            <span
                                class="text-base text-esg8 h-[38px] w-8 text-center grid place-content-center !rounded-r-md mt-1 bg-esg7/20 border border-esg67 border-l-0">€​</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full mt-6 {{ isset($fillMode) && !$fillMode ? '' : 'hidden' }}">
                <label
                    class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500 !font-bold !text-sm !text-esg8">{{ __('Environmental objectives:') }}
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                    <div class="w-full">
                        <label
                            class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500  !text-sm !text-esg8">{{ __('CCM') }}</label>
                        <div class="flex items-center w-full">
                            <x-form.form-col input="text" id="ccm"
                                class="!text-esg8  !text-sm !rounded-r-none" form_div_size="w-full"
                                placeholder="{!! __('Insert value') !!}" />
                            <span
                                class="text-base text-esg8 h-[38px] w-8 text-center grid place-content-center !rounded-r-md mt-1 bg-esg7/20 border border-esg67 border-l-0">%</span>
                        </div>
                    </div>

                    <div class="w-full">
                        <label
                            class="text-esg29 block text-sm !font-bold after:content-['*'] after:text-red-500  !text-sm !text-esg8">{{ __('CCA') }}</label>
                        <div class="flex items-center w-full">
                            <x-form.form-col input="text" id="cca"
                                class="!text-esg8  !text-sm !rounded-r-none" form_div_size="w-full"
                                placeholder="{!! __('Insert value') !!}" />
                            <span
                                class="text-base text-esg8 h-[38px] w-8 text-center grid place-content-center !rounded-r-md mt-1 bg-esg7/20 border border-esg67 border-l-0">%</span>
                        </div>
                    </div>
                </div>
                @error('ccm_cca')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div
            class="w-full mt-6 {{ isset($fillMode) ? (!$fillMode ? '' : (isset($purpose) && !$purpose ? '' : 'hidden')) : 'hidden' }}">
            <label
                class="text-esg29 block text-sm !font-bold !font-bold after:content-['*'] after:text-red-500  !text-sm !text-esg8">{{ __('How do you characterize the activity?') }}
            </label>

            <div class="grid grid-cols-3 items-center gap-5 w-full mt-6">
                <x-form.form-col input="radio" id="activity" flex="true" :showError="false" name="activity"
                    wire:model="activity" value="enabling" label="{!! __('Enabling') !!}"
                    class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />

                <x-form.form-col input="radio" id="activity" flex="true" :showError="false" name="activity"
                    wire:model="activity" value="transitional" label="{!! __('Transitional ') !!}"
                    class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />

                <x-form.form-col input="radio" id="activity" flex="true" :showError="false" name="activity"
                    wire:model="activity" value="not-applicable" label="{!! __('Not applicable') !!}"
                    class=" !text-sm !rounded-full !text-esg8" form_div_size="w-full !-mt-4" />
            </div>
            @error('activity')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="mt-1 text-xs text-red-500">
                    {{ $error }}
                </p>
            @endforeach
        @endif

    </x-modals.form>
</div>
