<?php

namespace App\Http\Controllers\Builders;

use Illuminate\Support\Facades\URL;
use Slides\Saml2\OneLoginBuilder as Saml2OneLoginBuilder;

/**
 * Class OneLoginBuilder
 *
 * @package Slides\Saml2
 */
class OneLoginBuilder extends Saml2OneLoginBuilder
{

    /**
     * Configuration default values that must be replaced with custom ones.
     *
     * @return array
     */
    protected function configDefaultValues()
    {
        return [
            'sp.entityId' => URL::route('tenant.saml.metadata', ['uuid' => $this->tenant->uuid]),
            'sp.assertionConsumerService.url' => URL::route('tenant.saml.acs', ['uuid' => $this->tenant->uuid]),
            'sp.singleLogoutService.url' => URL::route('tenant.saml.sls', ['uuid' => $this->tenant->uuid])
        ];
    }
}
