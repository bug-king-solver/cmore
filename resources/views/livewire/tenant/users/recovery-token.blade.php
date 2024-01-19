<div class="p-6">
    <div class="flex flex-row flex-wrap">
        <div class="w-full">
            <div class="pb-4 border-esg16">
                <h3 class="text-lg leading-6 text-esg29 font-semibold">
                    {{ __('Recovery Tokens') }}
                    <div wire:click="closeModal()" class="flex text-center border-esg6 absolute right-3 top-3 cursor-pointer rounded-full border-2 w-7 h-7 text-sm font-bold leading-3"><div class="m-auto">X</div></div>
                </h3>
            </div>
        </div>
        <div class="mt-4 w-full pl-0">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default flex justify-center flex-col">
                    <p class="font-bold pb-4">{{ __('Please save these tokens in a safe place!') }}</p>
                    <ul class="list-none">
                        @foreach ($tokens as $token)
                            <li class="pb-2">{{ $token }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="my-2">
                    <div class="flex justify-end mt-2">
                        <x-buttons.btn text="{{ __('Close') }}" wire:click="closeModal()"></x-buttons>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>