@props([
    'title',
    'savemethod' => null,
    'savebuttontext' => null,
    'savebuttonclass' => null,
    'buttonPosition' => null,
    'class' => null,
    'showButtons' => true,
])

<div class="bg-esg4 px-4 pt-5 pb-5 sm:p-6 sm:pb-4" tabindex="-1" autofocus>
    <div class="mt-3 text-center sm:mt-0 sm:text-left">
        <h3 class="text-esg29 items-center relative text-2xl font-extrabold {{ $class ?? '' }}" id="modal-headline">
            {{ $title }}
            <div wire:click="closeModal()" @click="$dispatch('close')" 
                class="flex text-center bg-esg7/50 absolute right-0 top-0 cursor-pointer rounded-full w-6 h-6 text-sm font-bold z-10">
                <div class="m-auto text-black">
                    @include('icons.close', ['class' => 'w-4 h-4'])
                </div>
            </div>
        </h3>
        <div class="mt-3">
            {{ $slot }}
        </div>
    </div>
</div>

@if ($showButtons)
    <div class="mt-4 flex gap-5 {{ $buttonPosition ?? 'justify-center' }} pb-5 pr-6">

        <x-buttons.cancel
            class="cursor-pointer bg-esg4 border-[1px] border-esg7 text-esg7 uppercase font-inter font-bold text-xs flex items-center !px-5 !rounded-md" />

        @if (isset($savemethod))
            <x-buttons.save savemethod="{{ $savemethod }}" text="{{ $savebuttontext ?? __('Save') }}"
                class="{{ $savebuttonclass ?? '' }}"> </x-save>
            @else
                <x-buttons.save class="flex items-center !px-5" text="{{ $savebuttontext ?? __('Save') }}"
                    class="{{ $savebuttonclass ?? '' }}"> </x-save>
        @endif
    </div>
@endif
