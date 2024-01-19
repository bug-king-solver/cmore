<div>
    <x-modals.white title="{{ $analysisInfo->exists ? __('Edit analysis') : __('New analysis') }}">
        <p class="text-xs text-esg8">{{ __('Add up to 4 words or expressions (within 3 words each) as the analysis parameter.') }}</p>
        <x-modals.white-form-row input="text" id="name" label="{{ __('Name') }}" placeholder="{{ __('Add a name to this analysis') }}" class="after:content-['*'] after:text-red-500 after:m-1"/>

        <x-modals.white-form-row input="tomselect" id="search_terms" class="after:content-['*'] after:text-red-500 after:m-1" label="{{ __('Add words or expressions') }}"
            placeholder="{{ __('Add your keywords and press enter') }}"
            :wire_ignore="false"
            :options="$this->keywordArray"
            :items="$this->search_terms"
            wire:model.defer="attach"
            preload="focus"
            plugins="['remove_button']"
            :limit="4"
            :allowcreate="true"
        />
        <p class="!mt-3 text-xs text-gray-400">{{ __('You can add multiple terms separated by a comma') }}</p>

          {{-- <div class="mt-4">
            <span>{{__('Selected words/expressions')}}:</span>
        </br>
            @if($analysisInfo->exists)
            @foreach($this->analysisInfo->search_terms as $serachTeam)
                        <span class="bg-esg58 text-esg62 text-sm font-medium mr-2 px-2.5 py-1 rounded">{{$serachTeam}}</span>
                                
            @endforeach
            @endif
        </div> --}}
        
    </x-modals-white>
</div>

<style nonce="{{ csp_nonce() }}" >
    .ts-control{
        background-color: rgb(250 249 251 / var(--tw-bg-opacity)) !important;
        border: none;
        border-radius: 0.25rem;
    }
    input{
        border: none;
        font-size: 13px !important;
    }

    

</style>

  