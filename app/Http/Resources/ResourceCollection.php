<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ResourceCollection extends Collection
{
    /** @var bool $preserveKeys - Columns to be removed from the response */
    public $preserveKeys = false;

    /** @var bool $preserveAllQueryParameters - Columns to be removed from the response */
    public $preserveAllQueryParameters = true;

    /** @var array<string,mixed> $columnToRemoveFromOutput - Columns to be removed from the response */
    public array $columnToRemoveFromOutput = [];

    /** @var bool $shouldHiddenNullValues - Columns to be removed from the response */
    public bool $shouldHiddenNullValues;

    /**
     * Create a new resource instance.
     * @param  array<string,object>  $resource
     * @param  array<string,mixed>  $columnToRemoveFromOutput
     * @return void
     */
    public function __construct(object $resource, array $columnToRemoveFromOutput = [])
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        $this->resource = $resource;

        $this->shouldHiddenNullValues = config("app.api.hide_null_values", false);

        $columnToRemoveFromOutputParsed = [];
        foreach ($columnToRemoveFromOutput as $column) {
            $nestedArray = Arr::dot([$column => []]);
            foreach ($nestedArray as $key => $value) {
                Arr::set($columnToRemoveFromOutputParsed, $key, empty($value) ? "" : $value);
            }
        }

        $this->columnToRemoveFromOutput = $columnToRemoveFromOutputParsed;
    }

    /**
     * Transform the resource collection into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array<mixed>
     */
    public function toArray($request): mixed
    {
        $originalResource = $this->collection->toArray();
        $finalResource = $this->parseResourceValuesRecursive($originalResource);
        //sort the array by the keys recursively
        ksort($finalResource);
        return $finalResource;
    }

    /**
     * Search resource by columns
     * @param  array<string,mixed>  $data - the resource data
     * @return array<string,mixed>
     */
    public function parseResourceValuesRecursive($data): array
    {
        foreach ($data as $column => &$item) {
            //Remove the values if is null and the config is set to true
            if ($this->shouldHiddenNullValues) {
                if ($item == "") {
                    unset($data[$column]);
                }
            }

            if (is_object($item)) {
                $item = (array) $item;
            }

            if (is_array($item)) {
                $item = $this->removeColumnsRecursive($item, $this->columnToRemoveFromOutput);
            }

            if (!is_array($item)) {
                if ($column === 'id' || Str::endsWith($column, '_id')) {
                    $data[$column . '_external'] = encryptMd5($item);
                    $item = encryptValue($item);
                } else {
                    $decode = @json_decode($item, true);
                    $item = (json_last_error() === JSON_ERROR_NONE)
                        ? $decode
                        : $item;
                }
                continue;
            }

            // recursive call
            $item = $this->parseResourceValuesRecursive($item);
        }

        return $data;
    }

    /**
     * Search resource by columns
     * @param  array $resource - the resource data
     * @param  array $removeColumn - the columns to be removed
     * @return array
     */
    public function removeColumnsRecursive(array $resource, array $removeColumn): array
    {
        //remove from $resource  , all key that are in $removeColumn

        foreach ($removeColumn as $column => $value) {
            if (is_array($value)) {
                if (isset($resource[$column])) {
                    if (isset($resource[$column][0])) {
                        foreach ($resource[$column] as &$arr) {
                            $arr  = $this->removeColumnsRecursive($arr, $value);
                        }
                    }
                }
            } else {
                if (array_key_exists($column, $resource)) {
                    unset($resource[$column]);
                    unset($removeColumn[$column]);
                }
            }
        }

        return $resource;
    }
}
