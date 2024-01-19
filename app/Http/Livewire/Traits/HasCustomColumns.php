<?php

namespace App\Http\Livewire\Traits;

trait HasCustomColumns
{
    protected array $customRules;

    protected array $customColumns;

    protected array $customColumnsIds;

    public array $customColumnsRules;

    public array $customColumnsFields;

    public $customColumnsData;

    abstract protected function rules();

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->customRules = tenant()->features[$this->feature]['custom_rules'] ?? [];
        $this->customColumns = tenant()->features[$this->feature]['custom_columns'] ?? [];

        $this->parseCustomColumnsIds();
        $this->parseCustomColumnsFields();
        $this->parseCustomColumnsRules();
    }

    /**
     * Parse custom columns ids - useful to get all model attributes
     */
    protected function parseCustomColumnsIds()
    {
        $this->customColumnsIds = array_pluck($this->customColumns, 'id');
    }

    /**
     * Parse custom columns fields
     */
    protected function parseCustomColumnsFields()
    {
        $fields = [];

        array_walk(
            $this->customColumns,
            function ($column) use (&$fields) {
                $only = ['id', 'label', 'type', 'optgroups', 'options'];
                $data = array_only($column, $only);

                $fields[$data['id']] = array_map(fn ($prop) => parseDynamicProperty($prop), $data);
            }
        );

        $this->customColumnsFields = $fields;
    }

    /**
     * Parse custom columns rules
     */
    protected function parseCustomColumnsRules()
    {
        $this->customColumnsRules = array_filter(
            array_map(
                function ($column) {
                    $key = 'customColumnsData.' . $column['id'];
                    $rules = explode('|', $column['rules']);
                    $rules = array_map(fn ($rule) => parseDynamicProperty($rule), $rules);

                    return [$key => $rules];
                },
                $this->customColumns
            )
        );
    }

    /**
     * Add custom data to the model
     */
    protected function addCustomData($data): array
    {
        return array_merge($data, $this->customColumnsData ?? []);
    }

    /**
     * Merge custom columns rules
     */
    protected function mergeCustomRules(array $rules): array
    {
        return mergeCustomRules($rules, $this->customRules, $this->customColumnsRules);
    }
}
