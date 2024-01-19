<div>
    <x-modals.form title="{{ __('Alterar risco: :risk', ['risk' => $risk['name']]) }}" buttonPosition="justify-end">
        <x-slot name="title" class="!text-esg6 !text-left">
            @if ($risk['enabled'])
                {!! __('Disable risk') !!}
            @else
                {!! __('Enable risk') !!}
            @endif
        </x-slot>

        <h4 class="text-esg8 text-sm font-bold pt-8">
            {!! __('Foi identificado o risco abaixo para a localização inserida:') !!}
        </h4>

        <div class="flex items-center gap-8 mt-5">
            <div class="w-8">
                @include('icons.physical_risks.' . strtolower($risk['name_slug']))
            </div>
            <div class="text-sm w-auto text-black">
                {!! __($risk['name']) !!}
            </div>
            <div>
                <span
                    class="w-24 h-8 grid place-content-center block text-sm rounded-md {{ getRiskLevelColor($risk['risk_slug']) }} text-esg4">
                    {!! __(getRiskLevelLabel($risk['risk_slug'])) !!}
                </span>
            </div>
        </div>

        <h4 class="text-esg8 text-sm font-bold pt-8">
            @if ($risk['enabled'])
                {!! __('Insira uma justificativa para a desativação:') !!}
            @else
                {!! __('Insira uma justificativa para a ativação:') !!}
            @endif
        </h4>

        <x-form.form-col input="textarea" id="textChange"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal" form_div_size="w-full" />

    </x-modals.form>
</div>
