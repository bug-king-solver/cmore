<div>
    <x-modals.white-form title="{{ __('Gerar reporte') }}">

        <x-modals.white-form-row input="tomselect"
            id="management"
            label="{{ __('Selecione a origem da Mensagem da Gestão e do logotipo *') }}"
            :options="$questionnaireList"
            limit="1"
            placeholder="{{ __('Selecione a origem da Mensagem da Gestão e do logotipo') }}"
        />
    </x-modals.white-form>
</div>
