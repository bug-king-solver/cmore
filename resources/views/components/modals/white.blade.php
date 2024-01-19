<div class="relative w-full max-w-xl h-full md:h-auto">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow">
        <button type="button"
            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center  "
            wire:click="closeModal()">
            @include('icons/close')
            <span class="sr-only">Close modal</span>
        </button>
        <div class="py-6 px-6 lg:px-8">
            <h3 class="mb-4 text-lg font-bold text-esg6">{{ $title }}</h3>
            <div class="space-y-6">
                {{ $slot }}

                <div class="flex items-center justify-end space-x-2">
                    <x-buttons.btn-outline class="text-esg6 bg-transparent px-5 py-2 mr-4"
                        wire:click="closeModal()">{{ __('Cancel') }}</x-buttons.btn-outline>

                    <x-buttons.btn-save text="save"
                        class="!text-white bg-esg6 border border-esg6 rounded text-sm text-center px-6 py-2 uppercase {{ $class ?? '' }}">
                        @if (isset($btnText))
                            {{ $btnText }}
                        @else
                            {{ __('Add') }}
                        @endif
                    </x-buttons.btn-save>
                </div>
            </div>
        </div>
    </div>
</div>
