<div>
 @php
 $scripts = [
             'en' => [
                 'formId' => '348334e2-277e-4bff-b7e9-5192ad2d086c',
             ],
             'fr' => [
                 'formId' => 'ae201ed5-1158-43d2-a861-8d1f81cf0dcd',
             ],
             'es' => [
                 'formId' => 'd185dc50-02b5-42fd-857f-0ce462f594a4',
             ],
             'pt-PT' => [
                 'formId' => '7e65052c-50bc-47a3-bb57-8cc9bfd04cb7',
             ],
             'pt-BR' => [
                 'formId' => '7e65052c-50bc-47a3-bb57-8cc9bfd04cb7',
             ],
         ];
 @endphp
 <script charset="utf-8" type="text/javascript" src="//js-eu1.hsforms.net/forms/embed/v2.js" nonce="{{ csp_nonce() }}"></script>
    <script nonce="{{ csp_nonce() }}">
        hbspt.forms.create({
            region: "eu1",
            portalId: "26859560",
            formId: "{{ $scripts[session()->get('locale') ?? config('app.locale')]['formId'] }}",
        });
    </script>
<div>
