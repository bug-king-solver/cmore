<div class="px-4 border-b border-esg7/30 bg-esg27 hover:bg-esg6/5">
    <div class="flex gap-2 py-4">

        <div class="grow" x-data="{ open: false, hover: false }">
            {{-- Title --}}
            <div @mouseover="hover = true" @mouseover.away = "hover = false">
                <div class="flex items-center justify-between cursor-pointer">
                    <div class="grow">
                        <p class="text-base font-bold text-[#2B2D3B] cursor-pointer py-0.5">{{ $text }} </p>
                    </div>
                </div>

                {{-- View --}}
                <div class="flex items-center justify-between mt-3 cursor-pointer" x-show="!open">
                    <div class="grow">
                        <p class="text-base text-esg16 py-0.5">{{ $value ?? ''}}</p>
                    </div>

                    <div class="w-50">
                        <x-buttons.btn-icon x-show="hover" x-on:click="open=true">
                            @include('icons.edit')
                        </x-buttons.btn-icon>
                    </div>
                </div>
            </div>
            {{-- Edit --}}
            <div class="flex items-center justify-between mt-3" x-show="open">
                <div class="grow">
                    <x-inputs.textarea name="{{ $id ?? $field }}" id="{{ $id ?? $field }}" {{ $attributes->merge() }} />
                </div>

                <div class="w-50 flex items-center gap-1">

                    <x-buttons.btn-icon x-on:click="open=false">
                        @include('icons.close-round')
                    </x-buttons.btn-icon>

                    <x-buttons.btn-icon x-on:click="open=false">
                        @include('icons.back', ['width' => 20, 'height' => 20, 'color' => color(6)])
                    </x-buttons.btn-icon>

                    <x-buttons.btn-icon wire:click='saveData'>
                        @include('icons.save', ['width' => 20, 'height' => 20, 'color' => color(6)])
                    </x-buttons.btn-icon>

                </div>
            </div>
        </div>
    </div>
</div>
