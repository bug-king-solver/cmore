<div class="mb-9">
   <span class="text-2xl text-esg28">{{__('Become a Solution Provider')}}</span>
</div>
<div class="w-[21vw]">
@php
$scripts = [
            'en' => [
                'formId' => '9250693b-e7ee-44af-9e87-64a2ebfb6ab6',
            ],
            'fr' => [
                'formId' => '6a861b51-95db-43a1-aba0-6f93f3b83f88',
            ],
            'es' => [
                'formId' => 'ea6196c8-2ea8-47b1-b4aa-035fcede8e1f',
            ],
            'pt-PT' => [
                'formId' => '377fa0ce-c75c-4c50-b6ca-2c4852e0f5df',
            ],
            'pt-BR' => [
                'formId' => '377fa0ce-c75c-4c50-b6ca-2c4852e0f5df',
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
