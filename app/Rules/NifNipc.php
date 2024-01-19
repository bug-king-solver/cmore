<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Http;

class NifNipc implements InvokableRule
{
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
        // Source: https://www.webdados.pt/2014/08/validacao-de-nif-portugues-em-php/
        if (! is_numeric($value) || strlen($value) != 9 || ! in_array((int) $value[0], [1, 2, 3, 5, 6, 8, 9], true)) {
            return $fail(__('Invalid VAT Number.'));
        }

        // Calculamos o dígito de controlo
        $checkDigit = 0;

        for ($i = 0; $i < 8; $i++) {
            $checkDigit += (int) $value[$i] * (10 - $i - 1);
        }

        $checkDigit = 11 - ($checkDigit % 11);

        if ($checkDigit >= 10) {
            $checkDigit = 0;
        }

        if ($checkDigit !== (int) $value[8]) {
            return $fail(__('Invalid VAT Number.'));
        }
    }
}
