<div>

    <div class="flex justify-between">
        <div class="">
            <label class="text-base font-semibold text-esg5 ">{{ __('Color') }}</label>
            <p class="text-xs text-esg8 mt-2.5">{{ __('Customize your own brand colours into your website page.') }}</p>
        </div>
        <div class="">
            <button type="button" wire:click="update" class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out">
                {{ __('Save') }}
            </button>
        </div>
    </div>

    {{-- <div class="p-6 border border-esg6/20 rounded-md grid grid-cols-1 md:grid-cols-2 gap-10 mt-6">
        <div class="col-span-2 text-base font-bold text-esg8">
            {{ __('Live Preview') }}
        </div>

        <div class="grid place-content-center">
            @include('icons.setting.frame1')
        </div>
        <div class="grid place-content-center">
            @include('icons.setting.frame2')
        </div>

        <div class="w-full">
            @error('enabled')
                <p class="mt-4 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div> --}}

    <div class="bg-esg4 mt-6">
        <h4 class="mt-10 text-lg font-bold text-esg29">{{ __('Background') }}</h4>

        <div class="mt-4 w-full grid grid-cols-1 md:grid-cols-4 gap-10">
            @foreach ($background as $name => $value)
                <div class="w-full">
                    <label for="background.{{ $name }}" class="block text-sm font-medium leading-5 text-esg29"> {{ __('Color') }} {{ $loop->iteration }} </label>
                    <div class="relative mt-1 rounded-md">
                        <x-inputs.color-v1 input="color" id="background.{{ $name }}" colorvalue="{{$value}}" type="hex"/>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="">
            <h4 class="mt-10 text-lg font-bold text-esg29">{{ __('Lettering') }}</h4>

            <div class="mt-4 w-full grid grid-cols-1 md:grid-cols-4 gap-10">
                @foreach ($lettering as $name => $value)
                    <div class="w-full">
                        <label for="lettering.{{ $name }}" class="block text-sm font-medium leading-5 text-esg29"> {{ __('Color') }} {{ $loop->iteration }} </label>
                        <div class="relative mt-1 rounded-md">
                            <x-inputs.color-v1 input="color" id="lettering.{{ $name }}" colorvalue="{{$value}}" type="hex"/>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="">
            <h4 class="mt-10 text-lg font-bold text-esg29">{{ __('Other') }}</h4>

            <div class="mt-4 w-full grid grid-cols-1 md:grid-cols-4 gap-10">
                @foreach ($other as $name => $value)
                    <div class="w-full">
                        <label for="other.{{ $name }}" class="block text-sm font-medium leading-5 text-esg29"> {{ __('Color') }} {{ $loop->iteration }} </label>
                        <div class="relative mt-1 rounded-md">
                            <x-inputs.color-v1 input="color" id="other.{{ $name }}" colorvalue="{{$value}}" type="hex"/>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
</div>
