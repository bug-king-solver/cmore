<?php

namespace App\Http\Livewire\Components;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class FilterBarComponent extends Component
{
    /** @var array<string,mixed> $availableFilters */
    public array $availableFilters = [];

    /** @var array<string,mixed> $filterSelected */
    public array $filterSelected = [];

    /** @var array<string,mixed> $activeFilters */
    public array $activeFilters = [];

    /** @var array<string,mixed> $globalFilters */
    public array $globalFilters = [];

    /** @var array<string,mixed> $activeFiltersParseValues */
    public array $activeFiltersParseValues = [];

    /** @var string $searchValue */
    public string $searchValue = '';

    /** @var array<string,mixed> $sort */
    public array $sort = [];

    public string $sortDirection;

    protected bool $goToFirstPage = false;

    public bool $isSearchable = true;
    public bool $isSortable = false;

    /** @var array<string,mixed> $availableSorts */
    public array $availableSorts = [];

    /** @var array<string,mixed> $queryString */
    protected $queryString = [
        'activeFilters' => ['except' => '', 'as' => 's'],
        'sort' => ['except' => '', 'as' => 'sort'],
    ];

    /**
     * The component listeners.
     * @return array<string,mixed>
     */
    protected function getListeners(): array
    {
        return array_merge($this->listeners, [
            'updateFiltersValues' => 'updateFiltersValues',
            'removeItemFromFilter' => 'removeItemFromFilter',
        ]);
    }

    /**
     * init the component filters
     */
    public function initFilters(string|Model $model): void
    {
        if (!$model) {
            return;
        }

        if (is_string($model)) {
            $model = new $model();
        }

        $user = Auth()->user();

        $this->globalFilters = $user->globalFilters ?? [];

        $filters = request()->query('s') ?? [];

        $this->fetchAvailableFilters($model->getMorphClass());
        $this->fetchAvailableSorts($model->getMorphClass());

        // Only apply user filter if no filters are in the url
        // due to menus like Companies » All | This allow us to clean the filter in specific cases
        $filters = array_merge($this->globalFilters, $filters);

        if ($filters) {
            foreach ($filters as $column => $values) {
                // search in  $this->availableFilters by the key
                if (array_key_exists($column, $this->availableFilters ?? [])) {
                    $this->updateAvailableFilter($column);
                    $this->updateFiltersValues($column, $values);
                }
            }
        }

        if($this->isSortable && !empty($this->availableSorts)) {

            $defaultSort = [array_key_first($this->availableSorts) => 'desc'];
            $sorts = request()->query('sort') ?? $defaultSort;

            if(!empty($sorts)) {
                $this->updateAvailableSortDirection(reset($sorts));
                $this->updateAvailableSort(array_key_first($sorts));
            }
        }
    }

    /**
     * Search and mount the array of available filters
     * @execute on mount
     * @param string|object $model
     * @return array<string,mixed>
     */
    public function fetchAvailableFilters(string|object $model): array
    {
        $model = new $model();
        if (!method_exists($model, 'filters')) {
            return [];
        }

        $filters = $model->filters() ?? [];

        if (count($this->availableFilters) == 0) {
            foreach ($filters as $filter) {
                $this->availableFilters[$filter->queryName()] = [
                    'component' => $filter->component(),
                    'queryName' => $filter->queryName(),
                    'options' => parseValueByKeyForSelect($filter->options()),
                    'title' => $filter->title(),
                ];
            }
        }

        return $this->availableFilters;
    }

    /**
     * Update the actives filter into filter bar
     * @param string $filter
     * @return void
     */
    public function updateAvailableFilter(string $filter): void
    {
        $filterIsSelected = array_filter($this->filterSelected, function ($item) use ($filter) {
            return $item['queryName'] === $filter;
        });

        if (!empty($filterIsSelected)) {
            $this->removeFromFilter($filter);
            return;
        }

        $this->addToFilter($filter);
        return;
    }

    /**
     * Add filter to filter bar
     * @param string $filter
     * @return void
     */
    public function addToFilter($filter): void
    {
        $filter = array_filter($this->availableFilters, function ($item) use ($filter) {
            $filtered = $item['queryName'] === $filter;
            if ($filtered) {
                return $item;
            }
        });

        $this->filterSelected = array_merge($this->filterSelected, $filter);
    }

    /**
     * Remove filter from filter bar
     * @param string $filter
     * @return void
     */
    public function removeFromFilter($filter): void
    {
        $this->filterSelected = array_filter($this->filterSelected, function ($item) use ($filter) {
            return $item['queryName'] !== $filter;
        });
        unset($this->activeFilters[$filter]);
        unset($this->activeFiltersParseValues[$filter]);
    }

    /**
     * Perform a search in the model
     * @param object $model
     * @return object
     */
    public function search($model): object
    {
        // Go to the first page when the search is changed
        if ($this->goToFirstPage) {
            $this->goToPage(1);
        }

        $this->globalFilters = $this->activeFilters;
        $user = Auth()->user();
        $user->globalFilters = $this->globalFilters;
        $user->save();

        $model = $model->search($this->searchValue)->filter($this->activeFilters);

        if ($this->isSortable) {
            $model = $model->sort($this->sort);
        }

        return $model;
    }

    /**
     * Prepare the values to be used in the query
     * @param string $column
     * @param string|int $value
     */
    public function updateFiltersValues($column, $value): void
    {
        $this->goToFirstPage = true;
        $this->setActiveFilterParseValues($value, $column);
        $this->activeFilters[$column] = $value;
    }

    /**
     * Set the values to be used in the page as view filter
     * @param array<string> $value
     * @param string $column
     * @return void
     */
    public function setActiveFilterParseValues(array|string $value, string $column): void
    {
        $this->activeFiltersParseValues[$column] = null;

        $filter = array_filter($this->availableFilters, function ($item) use ($column) {
            return $item['queryName'] === $column;
        });

        if (!$filter) {
            return;
        }

        $filter = $filter[$column];

        if ($value == null || $value == '') {
            unset($this->activeFiltersParseValues[$column]);
            return;
        }

        $this->activeFiltersParseValues[$column]['title'] = $filter['title'];
        $this->activeFiltersParseValues[$column]['component'] = $filter['component'];
        $values = [];
        if (is_array($value)) {
            if (isset($filter['options']) && count($filter['options']) > 0) {
                $filteredValues = array_filter($filter['options'], function ($item) use ($value) {
                    if (in_array($item['id'], $value)) {
                        return $item['title'];
                    }
                });
                $values = [];
                foreach ($filteredValues as $key => $arr) {
                    $values[$arr['id'] ?? $key] = $arr['title'];
                }
            } elseif (!empty($value[0])) {
                $values = $value;
            }
        } elseif ($value != '') {
            $values = [$value];
        }

        if (count($values) > 0) {
            $this->activeFiltersParseValues[$column]['values'] = $values;
        } else {
            $this->activeFiltersParseValues[$column]['values'] = [
                '' => __("Not defined")
            ];
        }
    }

    /**
     * Remove item from filter
     * @param string $filterName
     * @param string|int $filterValue
     * @param string $filterComponent
     * @return void
     */
    public function removeItemFromFilter(string $filterName, string|int $filterValue, string $filterComponent): void
    {
        $parsedValueKey = array_filter(
            $this->activeFiltersParseValues,
            function ($item) use ($filterName) {
                return $item === $filterName;
            },
            ARRAY_FILTER_USE_KEY
        );

        if ($parsedValueKey == '' || $parsedValueKey == false) {
            return;
        }

        if (!isset($parsedValueKey[$filterName]['values'])) {
            return;
        }

        if ($filterComponent != 'select') {
            unset($this->activeFiltersParseValues[$filterName]);
            unset($this->activeFilters[$filterName]);
            return;
        }

        $parseKey = array_search($filterValue, $parsedValueKey[$filterName]['values']);
        if ($parseKey === '' || $parseKey === false) {
            return;
        }
        if (is_array($this->activeFilters[$filterName])) {
            $filterValueKey = array_search($parseKey, $this->activeFilters[$filterName]);
        } else {
            $filterValueKey = $parseKey;
        }

        if ($filterValueKey === '' || $filterValueKey === false) {
            return;
        }

        $this->removeItemFromParsedValues($filterName, $parseKey);
        $this->removeItemFromFilterValues($filterName, $filterValueKey);
    }

    /**
     * Remove item from parsed values
     * @param string $filterName
     * @param string|int $valueKey
     * @return void
     */
    public function removeItemFromParsedValues(string $filterName, string|int $valueKey): void
    {
        unset($this->activeFiltersParseValues[$filterName]['values'][$valueKey]);
        if (count($this->activeFiltersParseValues[$filterName]['values']) == 0) {
            unset($this->activeFiltersParseValues[$filterName]);
        }
    }

    /**
     * Remove item from filter values
     * @param string $filterName
     * @param string|int $valueKey
     */
    public function removeItemFromFiltervalues(string $filterName, string|int $valueKey): void
    {
        if (is_array($this->activeFilters[$filterName])) {
            unset($this->activeFilters[$filterName][$valueKey]);
            if (count($this->activeFilters[$filterName]) == 0) {
                unset($this->activeFilters[$filterName]);
            }
        } else {
            unset($this->activeFilters[$filterName]);
        }
    }

    /**
     * Update the actives filter into filter bar
     * @param string $filter
     * @return void
     */
    public function updateAvailableSort($filter): void
    {
        $this->sort = [$filter => $this->sortDirection];
    }

    /**
     * Update the actives filter into filter bar
     * @param string $direction
     * @return void
     */
    public function updateAvailableSortDirection($direction): void
    {
        $this->sortDirection = $direction;
        $this->sort = [array_key_first($this->sort) => $this->sortDirection];
    }

    /**
     * Search and mount the array of available sort options
     * @execute on mount
     * @param $model
     * @return array<string,mixed>
     */
    public function fetchAvailableSorts(string $model): array
    {
        if (!property_exists($model, 'sortable')) {
            return [];
        }

        if (!method_exists($model, 'getSortable')) {
            return [];
        }

        $this->isSortable = true;

        $model = new $model();
        $this->availableSorts = $model->getSortable();
        return $this->availableSorts;
    }
}
