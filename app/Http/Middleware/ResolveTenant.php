<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Builders\OneLoginBuilder;
use Slides\Saml2\Repositories\TenantRepository;
use Slides\Saml2\Http\Middleware\ResolveTenant as MiddlewareResolveTenant;

/**
 * Class ResolveTenant
 *
 * @package Slides\Saml2\Http\Middleware
 */
class ResolveTenant extends MiddlewareResolveTenant
{
    /**
     * ResolveTenant constructor.
     *
     * @param TenantRepository $tenants
     * @param OneLoginBuilder $builder
     */
    public function __construct(TenantRepository $tenants, OneLoginBuilder $builder)
    {
        $this->tenants = $tenants;
        $this->builder = $builder;
    }

}
