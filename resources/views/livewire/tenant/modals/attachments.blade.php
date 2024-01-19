<div class="p-10">
    <div class="flex justify-end -mt-5 -mr-5">
        <button type="button" wire:click="$emit('closeModal')" class="dark:hover:text-esg27 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800">
            <svg class="text-esg8 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>
    @if ($isActive)
    <div class="text-center mb-10">
        <h2 class="text-esg29 text-2xl font-extrabold">{{ __('Add document') }}</h2>
    </div>
    @endif
    @error('upload.*')
        <p class="mb-5 text-center text-sm font-bold text-red-600">
            {{ $message }}
        </p>
    @enderror

    @error('attach')
        <p class="mb-5 text-center text-sm font-bold text-red-600">
            {{ $message }}
        </p>
    @enderror

    @if(session('error'))
        <p class="mb-5 text-center text-sm font-bold text-red-600">
            {{ session('error') }}
        </p>
    @endif

    @if(session('success'))
        <p x-init="$nextTick(() => { waitCloseModal(1,'{{  $model }}') });"
         class="mb-5 text-center text-sm font-bold text-green-600">
            {{ session('success') }}
        </p>
    @endif
    @if ($model)
        <div class="mb-5 flex items-center">
            @if ($isActive)
            <div class="grow">
                <x-inputs.tomselect
                    :wire_ignore="false"
                    wire:model.defer="attach"
                    remote="{{ $attachmentsUrl }}"
                    preload="focus"
                    placeholder="{{ __('Choose an attachment already uploaded. Search by file name.') }}"
                    multiple />
            </div>

            <div class="pl-4">
                <x-buttons.btn text="{{ __('Add') }}" wire:click="updateAttach" />
            </div>
            @endif
        </div>
        @if($isActive)
        <div class="my-5 text-center text-esg29 text-xl font-bold">{{ __('OR') }}</div>
        @elseif($attachments->count() === 0)
        <div class="my-5 text-center text-esg29 text-xl font-bold">This questions doesn't have attachments</div>
        @endif
    @endif

    @if($isActive)
    <div class="relative flex flex-col">
        <label for="dropzone-file">
        <div class="bg-esg6/10 border-esg5 relative flex cursor-pointer flex-col rounded border border-dashed">
            <div class="text-esg5 flex flex-col items-center justify-center py-5 text-center">
                <p class="flex justify-center">@include('icons/upload', ['class' => 'w-10 h-10'])</p>
                <p><button x-on:click="document.getElementById('dropzone-file').click();" class="text-esg27 bg-esg5 font-inter mt-5 rounded p-1.5 font-bold uppercase leading-3">{{ __('Upload') }}</button></p>
                <input id="dropzone-file" type="file" wire:model.lazy="upload" multiple class="hidden" />
                <div wire:loading>
                    <div class="flex items-center mt-4">
                        <div role="status">
                            @include('icons.loader')
                        </div>
                        {{ __('Please wait') }}
                    </div>
                </div>
            </div>
        </div>
        </label>
    </div>
    @endif

    @if ($attachments->count())
        <div class="mb-4">
            @if($isActive)

            <div class="mt-6">
                {{ __('Added documents') }}
                <x-inputs.checkbox-nolabel wire:model="selectPage" class="ml-3"/>

            </div>
            @endif
            @if(count($selected) > 0)
            <div class="bg-esg58 mt-5 grow flex flex-nowrap items-center justify-center flex-row rounded p-1.5">
                <div class="text-esg8 text-left flex items-center justify-start grow">
                @if ($selectPage)
                    {{ __('All') }}
                @else
                    {{count($selected)}}
                @endif
                {{ __('Files selected') }}
                </div>
                <div class="text-right flex items-center justify-end grow min-w-fit">
                    <div class="mr-2">
                        <x-inputs.select-media
                            class="text-esg8 text-xs"
                            :extra="['options' => $mediaTypesList]"
                            wire:change="bulkFileTypeChnage($event.target.value)"
                            placeholder="{{__('Select file type')}}"
                            name="bulkSelected"
                        />
                    </div>
                    <div class="mr-2">
                        @include('icons/library/right')
                    </div>
                    <button type="button" wire:click="deleteMultipleMedia()">
                        @include('icons/trash', ['stroke' => config('theme.default.colors.esg6')])
                    </button>
                </div>
            </div>
            @endif
            @foreach ($attachments as $key=> $attachment)
                <div class="mt-5 grow flex flex-nowrap items-center justify-center flex-row p-1.5">
                    <div class="text-left flex items-center justify-start grow">
                        
                        @if($isActive)
                        <x-inputs.checkbox-nolabel wire:model="selected" value="{{ $attachment->id }}" />
                        @endif
                        <div class="mr-4">
                            <img
                                class="comments-avatar"
                                src="{{ $attachment->user()->avatar }}"
                                alt="avatar"
                                title="{{ __('Added at :date by :name', ['date' => $attachment->created_at, 'name' => $attachment->name]) }}"
                            >
                        </div>
                        <div class="mr-4">
                            @include('icons/document')
                        </div>

                        <div class="text-esg8 text-sm font-medium">
                            <p class="text-esg8 pb-1 text-sm font-medium leading-3">
                                <a
                                target="_blank"
                                rel="noopener"
                                href="{{ tenantPrivateAsset($attachment->id, 'attachments') }}">
                                    {{ $attachment->name }}
                                </a>
                            </p>
                            <p class="text-esg12 text-xs font-semibold">{{ getSizeForHumans($attachment->size) }}</p>
                        </div>
                    </div>
                    <div class="text-right flex items-center justify-end grow min-w-fit">
                        @if($isActive)
                        <div class="mr-2">
                            <x-inputs.select-media
                                class="text-esg8 text-xs"
                                :extra="['options' => $mediaTypesList]"
                                wire:change="updateMediaFileType($event.target.value, '{{$attachment->id}}')"
                                placeholder="{{__('Select file type')}}"
                                :items="$attachment->getCustomProperty('file_type')"
                            />
                        </div>
                        <div class="mr-2 w-7">
                            @if($attachment->hasCustomProperty('file_type'))
                                @include('icons/library/right')
                            @endif
                        </div>
                        <button type="button" wire:click="destroy('{{ $attachment->id }}')">
                            @include('icons/trash', ['stroke' => config('theme.default.colors.esg6')])
                        </button>
                        @endif
                    </div>
                </div>


            @endforeach
        </div>
    @endif
</div>
