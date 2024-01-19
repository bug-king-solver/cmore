<?php

namespace App\Http\Controllers\Tenant\Api\Swagger;

class Errors
{
    /**
     *  @OA\Schema(
     *     schema="401",
     *     title="401",
     *     description="Unauthorized",
     *     @OA\Property(
     *       property="error",
     *       type="string",
     *     ),
     *     @OA\Property(
     *        property="message",
     *        type="string",
     *     ),
     * )
     */
    public function error401()
    {
    }

    /**
     *  @OA\Schema(
     *     schema="403",
     *     title="403",
     *     description="Forbidden",
     *     @OA\Property(
     *       property="error",
     *       type="string",
     *     ),
     *     @OA\Property(
     *        property="message",
     *        type="string",
     *     ),
     * )
     */
    public function error403()
    {
    }

    /**
     *  @OA\Schema(
     *     schema="404",
     *     title="404",
     *     description="Not Found",
     *     @OA\Property(
     *       property="error",
     *       type="string",
     *     ),
     *     @OA\Property(
     *        property="message",
     *        type="string",
     *     ),
     * )
     */
    public function error404()
    {
    }

    /**
     *  @OA\Schema(
     *     schema="422",
     *     title="422",
     *     description="Unprocessable Entity",
     *     @OA\Property(
     *       property="error",
     *       type="string",
     *     ),
     *     @OA\Property(
     *        property="message",
     *        type="string",
     *     ),
     * )
     */
    public function error422()
    {
    }
}
