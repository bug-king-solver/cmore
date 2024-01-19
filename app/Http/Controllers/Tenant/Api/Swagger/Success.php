<?php

namespace App\Http\Controllers\Tenant\Api\Swagger;

class Success
{
    /**
     *  @OA\Schema(
     *     schema="DefaultMetaResponse",
     *     title="Success Response",
     *     description="Success Response",
     *        @OA\Property(
     *          property="total",
     *          type="integer",
     *          description="The total number of records",
     *        ),
     *        @OA\Property(
     *          property="page_count",
     *          type="integer",
     *          description="The total number of pages",
     *        ),
     *        @OA\Property(
     *          property="links",
     *          type="object",
     *          description="This contains the links for navigate the pages",
     *            @OA\Property( property="first",type="string" , maxLength=255, minLength=1 , description="The first page link"),
     *            @OA\Property( property="last",type="string" , maxLength=255, minLength=1 , description="The last page link"),
     *            @OA\Property( property="prev",type="string" , maxLength=255, minLength=1 , description="The previous page link"),
     *            @OA\Property( property="next",type="string" , maxLength=255, minLength=1 , description="The next page link"),
     *          ),
     *          @OA\Property(
     *            property="meta",
     *            type="object",
     *            description="The meta object",
     *              @OA\Property(property="current_page",type="integer", minLength=1, nullable=true, description="The current page number"),
     *              @OA\Property(property="from",type="integer", minLength=1,  description="The starting record number"),
     *              @OA\Property(property="last_page",type="integer", minLength=1,  description="The last page number"),
     *              @OA\Property(property="path",type="string",  maxLength=255, minLength=1, description="The current path"),
     *              @OA\Property(property="per_page",type="integer", minLength=1 , description="The number of records per page"),
     *              @OA\Property(property="to", type="integer", minLength=1 ,  description="The ending record number"),
     *              @OA\Property(property="total",type="integer", minLength=1 , description="The total number of records"),
     *          @OA\Property(
     *             property="links",
     *             type="array",
     *             description="This contains the links for navigate the pages",
     *                @OA\Items(
     *                 @OA\Property( property="url",type="string" , maxLength=255, minLength=1 , description="The url generated to this search"),
     *                 @OA\Property( property="label",type="string" , maxLength=255, minLength=1 , description="The url label name"),
     *                 @OA\Property( property="active",type="boolean", description="The url is active or not"),
     *               ),
     *             ),
     *           ),
     *        )
     *     )
     * )
     */
    public function response200()
    {
    }
}
