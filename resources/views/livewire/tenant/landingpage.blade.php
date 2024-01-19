<div class="w-full">
    <div class="flex justify-between">
        <div class="">
            <label class="text-base font-semibold text-esg5 ">{{ __('Landing Screen') }}</label>
            <p class="text-xs text-esg8 mt-2.5">{{ __('Customize your Landing Screen') }}</p>
        </div>
    </div>

    {{-- <div class="flex justify-between mt-6">
        <div class="">
            <label class="text-base font-semibold text-esg8">{{ __('Main Section') }}</label>
            <p class="text-xs text-esg8 mt-2.5">{{ __('Choose images for Main Section.') }}</p>
        </div>
    </div>

    <div class="mt-6">
        <div class="relative mt-1 rounded-md flex">

            @foreach ($images as $row)
                @php $data = json_encode(["media" => $row->id]); @endphp
                <div class="relative">
                    <img src="{{ tenantPrivateAsset($row->id, 'attachments') }}"
                        class="w-44 h-44 rounded-xl bg-center mr-4" alt="{{ __('Preview') }}">
                    <x-buttons.btn-icon modal="modals.media-delete" :data="$data"
                        class="!bg-esg4 rounded-full absolute top-2 right-5 !p-2 !shadow ">
                        <x-slot name="buttonicon">
                            @include('icons.close', ['class' => 'w-4 h-4'])
                        </x-slot>
                    </x-buttons.btn-icon>
                </div>
            @endforeach


            <div class="flex items-center justify-center w-44 h-44 p-2">
                <button for="logo" type="button"
                    class="flex flex-col items-center justify-center p-4 w-full h-full outline-dashed outline-offset-4 outline-2 outline-esg7 rounded-lg cursor-pointer"
                    x-on:click="document.getElementById('dropzone-file1').click();">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p class="text-xs text-esg16 text-center">{{ __('Choose file to upload') }}</p>

                        <div wire:loading>
                            <div class="flex items-center mt-4">
                                <div role="status">
                                    @include('icons.loader')
                                </div>
                                {{ __('Please wait') }}
                            </div>
                        </div>
                    </div>
                </button>
                <input id="dropzone-file1" type="file" name="upload" wire:model.lazy="upload" multiple
                    class="hidden" />
            </div>

        </div>

    </div> --}}

    {{-- LineBreak --}}
    <div class="my-7 border-b border-esg7/30"></div>

    <div class="" x-data="{ open: {{ $manifesto ? 'true' : 'false' }} }">
        <div class="flex items-center gap-2">
            <p class="text-base font-semibold text-esg8">{{ __('Our Manifesto Section') }}</p>
            <div class="mt-1"> <x-inputs.switch id="manifesto" x-on:click="open = ! open" /> </div>
            <div class="">
                <span data-tooltip-target="tooltip-default" data-tooltip-placement="right" type="button"
                    class="cursor-help">@include('icons.info', ['color' => color(16)])</span>
                <div id="tooltip-default" role="tooltip"
                    class="absolute z-10 w-40 invisible inline-block p-4 text-xs text-esg16 transition-opacity duration-300 bg-esg27 rounded-lg shadow opacity-0 tooltip ">
                    {{ __('You can choose to change this section texts or to hide it completely by turning off the toggle. ') }}
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden sm:rounded-md mt-6" x-show="open">
            <div class="bg-esg4">
                <div>
                    <label for="section_title"
                        class="block text-sm leading-6 text-esg8 after:content-['*'] after:text-red-500">{{ __('Section title') }}
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <x-inputs.text wire:model.defer="section_title" name="section_title" id="section_title"
                            value="{{ old('section_title', tenant('section_title')) }}"
                            placeholder="{{ __('Section title') }}" />
                    </div>
                </div>

                @error('section_title')
                    <p class="mt-4 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            <div class="bg-esg4 mt-4">
                <div>
                    <label for="main_title"
                        class="block text-sm leading-6 text-esg8 after:content-['*'] after:text-red-500">{{ __('Main title') }}
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <x-inputs.text wire:model.defer="main_title" name="main_title" id="main_title"
                            value="{{ old('main_title', tenant('main_title')) }}"
                            placeholder="{{ __('Main title') }}" />
                    </div>
                </div>

                @error('main_title')
                    <p class="mt-4 text-xs text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="bg-esg4 mt-4">

                <x-form.form-editor label="{{ __('Description') }}" id="description" form_div_size='w-full'
                    value="{{ $this->description }}"
                    class="after:content-['*'] after:text-red-500 block !text-sm !leading-6 !text-esg8 !font-normal -mb-3"
                    dataTest="setting-description" form_div_size="w-full"
                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <div class="">
            </div>
            <div class="">
                <button
                    class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out"
                    wire:click="update">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
