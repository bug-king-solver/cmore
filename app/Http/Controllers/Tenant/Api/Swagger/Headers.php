<?php

namespace App\Http\Controllers\Tenant\Api\Swagger;

/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="Api Secret token",
 *     name="Authorization",
 *     description="🔒 Authentication token.This is used to identify the company. Format: Bearer {token}",
 * ),
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="Tenant Secret key",
 *     name="X-Tenant",
 *     description="🏢 Tenant Secret key. This is used to identify the tennant",
 * )
 */
class Headers
{
}
