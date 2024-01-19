<?php

namespace App\Http\Controllers\Tenant\Api\Swagger;

/**
 *  @OA\Schema(
 *   schema="QueryBuilder",
 *   title="QueryBuilder",
 *   @OA\Property(property="paginate", type="int", description="The number of items to return per page", example="10"),
 *   @OA\Property(property="page", type="int", description="The page number to return", example="1"),
 *   @OA\Property(property="sortBy", type="string", description="The field to order by ASC", example=""),
 *   @OA\Property(property="sortByDesc", type="string", description="The direction to order by DESC", example=""),
 *   @OA\Property(property="select", type="string", description="The field to select", example=""),
 *   @OA\Property(
 *    property="search",
 *    type="string",
 *    description="For more information, see OData expression syntax for filters at:
 *       1. Schema > QueryBuilder > ExternalDocumentation;
 *       2. Access the oficial dcumentation:https://learn.microsoft.com/en-us/azure/search/search-query-odata-filter#operator-precedence-in-filters
 *    ",
 *    example={
 *      "name eq 'test'",
 *      "name eq 'test' and id eq 1",
 *      "name eq 'test' or id eq 1",
 *      "name lt 1",
 *      "name gt 1",
 *      "name le 10",
 *      "name ge 10",
 *    }
 *  ),
 *   @OA\ExternalDocumentation(
 *     description="ODATA",
 *     url="https://learn.microsoft.com/en-us/azure/search/search-query-odata-filter#operator-precedence-in-filters",
 *    )
 *  )
 */
class QueryBuilder
{
    /**
     * @OA\Parameter(
     *   name="id",
     *   in="path",
     *   description="The resource model id",
     *   required=true,
     *   @OA\Schema(type="string", maxLength=255, minLength=50),
     *   example="1"
     * )
     */
    public string $id;

    /**
     * @OA\Parameter(
     *   name="vat_number",
     *   in="path",
     *   description="Companies vat number",
     *   required=true,
     *   @OA\Schema(type="string", maxLength=15, minLength=5),
     *   example="123456AB"
     * )
     */
    public string $vat_number;

    /**
     * @OA\Parameter(
     *     parameter="paginate",
     *     name="paginate",
     *     in="query",
     *     description="The number of items to return per page",
     *     required=false,
     *     @OA\Schema( type="int",  format="int", minimum=1, maximum=100 ),
     * )
     */
    public int $paginate;

    /** @OA\Parameter(
     *     parameter="page",
     *     name="page",
     *     in="query",
     *     description="The page number to return",
     *     required=false,
     *     @OA\Schema( type="int",  format="int",  ),
     * )
     */
    public int $page;

    /** @OA\Parameter(
     *     parameter="sortBy",
     *     name="sortBy",
     *     in="query",
     *     description="The field to order by ASC",
     *     required=false,
     *     @OA\Schema( type="string",  format="string",  ),
     * )
     */
    public string $sortBy;

    /** @OA\Parameter(
     *     parameter="sortByDesc",
     *     name="sortByDesc",
     *     in="query",
     *     description="The direction to order by DESC",
     *     required=false,
     *     @OA\Schema( type="string",  format="string",  ),
     * )
     */
    public string $sortByDesc;

    /** @OA\Parameter(
     *     parameter="select",
     *     name="select",
     *     in="query",
     *     description="The field to select",
     *     required=false,
     *     @OA\Schema( type="string",  format="string",  ),
     * )
     */
    public string $select;

    /**
     * @OA\Parameter(
     *   parameter="search",
     *   name="search",
     *   in="query",
     *   required=false,
     *   @OA\Schema( type="string",  format="string" ),
     *   description="For more information, see OData expression syntax for filters at: Schema > QueryBuilder ",
     *   example={
     *      "name eq 'test'",
     *      "name eq 'test' and id ne 1",
     *      "name ne 'test' or id eq 1",
     *      "name lt 1",
     *      "name gt 1",
     *      "name le 10",
     *      "name ge 10",
     *    }
     *  ),
     *  )
     */
    public string $search;
}
