<div>
    @if ($isCompleted && !$isSubmitted)
        @can('questionnaires.submit')
            <x-buttons.btn-icon-text wire:click="$emitTo('companies.form', 'nextStep', {{ $currentStep }})" class="!flex !gap-2">
                @include('icons.arrow', [
                    'color' => color(5),
                    'width' => '12',
                    'height' => '12',
                ])
                <x-slot name="buttonicon">
                    {{ __('pagination.next') }}
                </x-slot>
            </x-buttons.btn-icon-text>
        @endcan
    @endif
</div>
