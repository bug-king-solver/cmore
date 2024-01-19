<div>
    <x-modals.form title="{{ __('Minimun Safeguard') }}" class="!text-esg6 text-bold">

        <div>
            <p>
                {{ __('We declare that we have previously carried out the Minimum Safeguards verification exercise, with the adequate support and resources for their full understanding, and which resulted in:') }}
            </p>
        </div>

        <div class="mt-10">
            <ul class="">
                <li>
                    <x-inputs.radio wire:model="safeguardAligned" name="safeguardAligned" value="1"
                        label="{{ __('Compliance with minimum safeguards verified') }}" class="mr-2" />
                </li>
                <li>
                    <x-inputs.radio wire:model="safeguardAligned" name="safeguardAligned" value="0"
                        label="{{ __('Compliance with minimum safeguards not verified') }}" class="mr-2" />
                </li>
            </ul>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="mt-2 text-sm text-red-500">
                    {{ $error }}
                </p>
            @endforeach
        @endif

    </x-modals.form>
</div>
