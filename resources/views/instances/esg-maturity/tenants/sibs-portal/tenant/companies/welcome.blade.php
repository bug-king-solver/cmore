<div class="mt-20">
    <div class="row py-10 w-1/2 m-auto">
        {!! tenantView('tenant.questionnaires.introduction') !!}
    </div>

    <p class="mt-12 text-center">
        <x-buttons.a-alt href="{{ route('tenant.questionnaires.panel') }}"
            text="{{ __('Continue your journey') }}"
            class="!bg-[#44724D] !px-8 !py-2 !text-esg4 !border-0 !rounded !text-sm !font-medium normal-case"/>
    </p>

    <div class="mt-8 flex justify-center">
        @include(tenant()->views . 'icons.checklist')
    </div>
</div>