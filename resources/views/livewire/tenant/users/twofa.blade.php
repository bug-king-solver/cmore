<div class="p-6">
    <div class="flex flex-row flex-wrap">
        <div class="w-full">
            <div class="pb-4 border-esg16">
                <h3 class="text-lg leading-6 text-esg29 font-semibold">
                    {{ __('Validate your Authenticator app') }}
                    <div wire:click="closeModal()" class="flex text-center border-esg6 absolute right-3 top-3 cursor-pointer rounded-full border-2 w-7 h-7 text-sm font-bold leading-3"><div class="m-auto">X</div></div>
                </h3>
            </div>
        </div>
        <div class="mt-4 w-full pl-0">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default flex justify-center flex-col">
                    @if(isset($secret) && isset($qr_image))
                    <p>{{ __('Scan the QR code below with Google Authenticator or Microsoft Authenticator to enable 2FA. Alternatively you can copy the code manually:') }}</p>
                    <div class="flex justify-center mt-4 sm:px-6">
                        <div class="border border-esg18">
                            {!! $qr_image !!}
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flex flex-row">
                            <div class="relative">
                                <span class="w-[180px] border-b-2 border-esg16 mt-6 absolute right-0"></span>
                            </div>
                            <h4 class="text-2xl m-2 text-esg8">OR</h4>
                            <div class="relative">
                                <span class="w-[180px] border-b-2 border-esg16 mt-6 absolute left-0"></span>
                            </div>
                        </div>
                        <div class="flex items-center p-2 border border-esg16">
                            <p class="text-esg16">{{ $secret }}</p>
                            <button class="ml-1 cursor-pointer text-esg16" x-on:click="copyText('{{ $secret }}')">@include('icons.duplicate')</button>
                        </div>
                    </div>
                    <div class="my-2">
                        <div class="grid">
                            <p>{{ __('After that, insert the code generated by the Authenticator') }}</p>
                            <input type="text" name="otp" wire:model.defer="otp" class="h-11 w-full pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md block sm:leading-5" placeholder="{{ __('Write or paste the code here') }}">
                            @error('otp')
                            <p class="text-xs text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        <div class="flex justify-end mt-2">
                            <x-buttons.save></x-save>
                        </div>
                    </div>
                    @else
                    <p>{{ __('Code already configured') }}</p>
                    <div class="flex justify-end mt-2">
                        <x-buttons.delete></x-delete>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
