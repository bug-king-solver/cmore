<?php

namespace App\Http\Controllers\Traits;

use App\Http\Resources\ResourceCollection;
use App\Models\Tenant\Company;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trait ApiScoutResourceTrait
 * Make and execute custom queries on the resource.
 * $resource = Any resource model ( ex: App\Models\Tenant\Company )
 * $relations = array of relations to load ( ex: ['users', 'roles'] )
 */
trait ApiScoutResourceTrait
{
    protected string $table;

    protected array $availableColumnsForApi;

    protected array $availableRelationsForApi;

    protected array $availableWhereHasRelations;

    protected array $availableGroupColumns;

    protected array|null $columnsToSelect;

    /**
     * Prepare the response for the resource.
     * Will be make a select, sort, paginate and search on the resource ( if available )
     * @return mixed
     */
    public function prepareResponse()
    {
        $table = $this->table;
        if ($table === null) {
            $table = method_exists($this->resource, 'getTable')
                ? $this->resource->getTable()
                : null;
        }

        if (!$table) {
            abort(500, __('No table found for this resource'));
        }

        $resourceModel = $this->resource;
        if ($this->resource instanceof Builder) {
            $resourceModel = $this->resource->getModel();
        }

        $this->availableColumnsForApi = Schema::getColumnListing($table);

        $this->availableRelationsForApi = $this->loadedRelations ?? [];
        $this->availableWhereHasRelations = $this->whereHasRelations ?? [];
        $this->availableGroupColumns = $this->groupColumns ?? [];

        if (property_exists($resourceModel, 'availableColumnsForApi')) {
            $this->availableColumnsForApi = $resourceModel->availableColumnsForApi;
        }
        if (property_exists($resourceModel, 'availableRelationsForApi')) {
            $this->availableRelationsForApi = $resourceModel->availableRelationsForApi;
        }
        if (property_exists($resourceModel, 'availableWhereHasRelations')) {
            $this->availableWhereHasRelations = $resourceModel->availableWhereHasRelations;
        }
        if (property_exists($resourceModel, 'availableGroupColumns')) {
            $this->availableGroupColumns = $resourceModel->availableGroupColumns;
        }

        $columnsToSelect = $this->filterAvailableColumns();

        if (!$this->availableColumnsForApi || !$columnsToSelect) {
            abort(500, 'No columns available for this resource');
        }

        $resource = $this->resource->with($this->availableRelationsForApi ?? []);

        if (tenant()->hasSharingEnabled) {
            if (!request()->header('X-sharing-identifier')) {
                abort(403, __("No sharing identifier provided"));
            }

            if ($table != (new Company())->getTable() && !method_exists($resourceModel, 'company')) {
                abort(403, __("No sharing identifier provided"));
            }
        }

        foreach ($this->availableWhereHasRelations as $relation => $constraint) {
            $resource = $resource->whereHas($relation, $constraint ?? null);
        }

        // execute the select only at the columns defined at the Model and filter by API when available
        $resource = $this->makeSelect($resource);

        if (request()->has('search') && request()->input('search') != '') {
            $resource = $this->search($resource);
        }

        if (request()->has('sortBy') && request()->input('sortBy') != '') {
            $resource = $this->makeSortAsc($resource);
        }

        if (request()->has('sortByDesc') && request()->input('sortByDesc') != '') {
            $resource = $this->makeSortDesc($resource);
        }

        if (!empty($this->availableGroupColumns)) {
            $resource = $resource->groupBy($this->availableGroupColumns);
        }

        $this->resource = $resource->paginate(request()->input('paginate', $this->paginate))
            ->withQueryString();

        return $this->resource;
    }


    /**
     * Perform a sort on the resource. Example: sortBy=name,age
     * @param $resource
     * @return mixed
     */
    public function makeSortAsc($resource)
    {
        $sortAsc = request()->input('sortBy');
        $sortAsc = explode(',', $sortAsc);
        if (count($sortAsc) > 0) {
            foreach ($sortAsc as $sort) {
                $sort = trim($sort);
                if (in_array($sort, $this->availableColumnsForApi)) {
                    $resource = $resource->orderBy($sort);
                }
            }
        }

        return $resource;
    }

    /**
     * Perform a sort desc on the resource. Example: sortByDesc=name,age
     * @param $resource
     * @return mixed
     */
    public function makeSortDesc($resource)
    {
        $sortDesc = request()->input('sortByDesc');
        $sortDesc = explode(',', $sortDesc);

        if (count($sortDesc) > 0) {
            foreach ($sortDesc as $sort) {
                $sort = trim($sort);
                if (in_array($sort, $this->availableColumnsForApi)) {
                    $resource = $resource->orderByDesc($sort);
                }
            }
        }

        return $resource;
    }

    /**
     * Perform a select on the resource. Example: select=name,age
     * @param $resource
     * @return mixed
     */
    public function makeSelect($resource)
    {
        return $resource->select($this->columnsToSelect);
    }

    /**
     * Filter the coluns based on the model property and the input send by the user
     * @return array<string,mixed>
     */
    public function filterAvailableColumns(): mixed
    {
        $selectColumnFromUser = request()->input('select');

        $selectColumnFromUser = $selectColumnFromUser
            ? explode(',', $selectColumnFromUser)
            : $this->availableColumnsForApi;

        return $this->columnsToSelect = array_filter(
            $selectColumnFromUser,
            function ($column) {
                return in_array($column, $this->availableColumnsForApi);
            }
        );
    }

    /**
     * search resource by columns
     * Apply a ODATA search to the resource. Example: name eq 'John Doe'
     * @param $resource
     * @return mixed
     */
    public function search($resource)
    {
        $search = request()->input('search');
        $filters = ['eq', 'ne', 'gt', 'ge', 'lt', 'le'];
        $filters = array_combine($filters, ['=', '!=', '>', '>=', '<', '<=']);

        foreach ($filters as $key => $value) {
            $search = \str_replace(' ' . $key . ' ', ' ' . $value . ' ', $search);
        }
        /** explode spaces but not word inside '' */
        $search = preg_split("/\s+(?=([^\']*\'[^\']*\')*[^\']*$)/", $search);

        foreach ($search as $key => $value) {
            if ($key % 4 == 0) {
                if (in_array($value, $this->availableColumnsForApi)) {
                    $whereType = 'where';
                    if (isset($search[$key - 1])) {
                        if ($search[$key - 1] == 'or') {
                            $whereType = 'orWhere';
                        }
                    }

                    if ($value === 'id' || Str::endsWith($value, '_id')) {
                        $search[$key + 2] = decryptValue($search[$key + 2]);
                    }

                    $resource = $resource->$whereType(
                        $value,
                        $search[$key + 1],
                        $search[$key + 2]
                    );
                }
            }
        }

        return $resource;
    }

    /**
     * Get the raw query from the resource
     */
    public function getRawQuery($resource)
    {
        $sql = $resource->toSql();
        $bindings = $resource->getBindings();
        $query = \str_replace('?', '%s', $sql);
        $query = \vsprintf($query, $bindings);

        return $query;
    }
}
