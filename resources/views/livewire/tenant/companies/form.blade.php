<div class="px-4 md:px-0 company">
    <x-slot name="header">
        <x-header title="{{ __('Company') }}" data-test="data-header" click="{{ route('tenant.companies.list') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    @if (view()->exists(customInclude("tenant.companies.introduction")))
        <div class="w-full">
            <div class="row py-10 w-1/2 m-auto">
                {!! tenantView('tenant.companies.introduction') !!}
            </div>
        </div>
    @endif


    <div class="w-full">
        @include('livewire.tenant.companies.form-inputs', ['showButtons' => true])
    </div>
</div>
