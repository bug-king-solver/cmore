<?php

namespace App\Http\Controllers\Tenant\Api\Swagger;

class Responses
{
    /**
     *     @OA\Response(
     *         response="unauthorized",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/401"
     *         )
     *    )
     */
    public function unauthorized()
    {
    }

    /**
     *     @OA\Response(
     *         response="forbidden",
     *         description="Forbidden",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/403"
     *         )
     *    )
     */
    public function forbidden()
    {
    }

    /**
     *     @OA\Response(
     *         response="not_found",
     *         description="Not Found",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/404"
     *         )
     *    )
     */
    public function notFound()
    {
    }

    /**
     *     @OA\Response(
     *         response="unprocessable_entity",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/422"
     *         )
     *    )
     */
    public function unprocessableEntity()
    {
    }
}
