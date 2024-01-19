<div>
    @php
    $scripts = [
        'en' => [
            'formId' => "446178af-b53e-4afe-8c08-bc8244dc5895",
        ],
        'fr' => [
            'formId' => "a1293b2d-6ce1-43d3-857e-cb9c73175f44"
        ],
        'es' => [
            'formId' => "9a4dbc14-9b52-4077-b0df-83fb6e6cf991"
        ],
        'pt-PT' => [
            'formId' => "3ac11404-aac3-4c7a-8e9f-41d0c3a92920"
        ],
        'pt-BR' => [
            'formId'=> "3ac11404-aac3-4c7a-8e9f-41d0c3a92920"
        ],
    ]
    @endphp
   <script charset="utf-8" type="text/javascript" src="//js-eu1.hsforms.net/forms/embed/v2.js" nonce="{{ csp_nonce() }}"></script>
   <script nonce="{{ csp_nonce() }}">
       hbspt.forms.create({
           region: "eu1",
           portalId: "26859560",
           formId: "{{ $scripts[session()->get('locale') ?? config('app.locale')]['formId'] }}",
       });

   </script>
</div>
