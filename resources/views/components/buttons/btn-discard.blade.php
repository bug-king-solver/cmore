<div x-data="{ showModal: false }">
    <button @click="showModal = true"
        {{ $attributes->merge(['class' => 'py-2.5 px-6 uppercase text-base font-bold rounded-md text-esg8 bg-white cur cursor-pointer border border-esg8']) }}>
        {!! $text ?? __('Discard') !!}
    </button>

    <div x-show="showModal">
        <x-modals.discard title="{{ __('Discard Changes') }}"
            text="{{ __('You have unsaved changes. Are you sure you want to leave this page?') }}"
            discard="{{ $discard ?? '' }}" showModal="showModal" />
    </div>
</div>
