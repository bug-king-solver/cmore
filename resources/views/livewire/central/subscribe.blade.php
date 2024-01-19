<x-modals.default title="{{ __('Request a Demo') }}" style="height: 600px; overflow-y: scroll;" x-init="hbspt.forms.create({
    region: 'eu1',
    portalId: '26859560',
    target: '#requestDemoForm',
    formId: '{{ $formId }}',
});">

    <div id="requestDemoForm"> </div>

</x-modals.default>

{{-- always show the scrollbar --}}
<style nonce="{{ csp_nonce() }}">
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-track {
        background-color: #EEE;
        border-radius: 10px;
    }
</style>
