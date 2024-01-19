<?php

namespace App\Http\Controllers\Tenant\Api\Swagger;

$baseServer = config('app.url');
define('API_SERVER', "$baseServer/api/v1");
define('API_DOC', file_get_contents(base_path('resources/views/vendor/l5-swagger/api-description.md')));

/**
 * @SWG\Swagger(
 *   host=API_SERVER,
 *   schemes={"http", "https"},
 *   @OA\Info(
 *         version="1.0",
 *         title="CMORE",
 *         description=API_DOC,
 *      )
 *  )
 *  @OA\Server(
 *     url=API_SERVER,
 *     description="API server",
 *   )
 */
class Server
{
}
