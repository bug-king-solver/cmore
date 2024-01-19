<?php

namespace App\Rules\Tenant88bd07c3_05a9_4a30_b732_c865d7ccbce1;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Http;

class Nif implements InvokableRule
{
    protected $url = 'https://empresasturismo360.turismodeportugal.pt/EmpTur360/rest/EmpTur360/Interlocutor';

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $response = Http::acceptJson()->get($this->url, [
            'Pass' => 'CpD3aUQDF982S3fV8Ap5',
            'NIPC' => '',
            'NIF' => $value,
        ]);

        if ($response->serverError()) {
            return $fail($response->json('Errors.0') ?? 'Estamos a validar a adesão ao Programa Empresas Turismo 360°. Tente novamente.');
        }
    }
}
