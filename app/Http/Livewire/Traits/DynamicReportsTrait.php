<?php

namespace App\Http\Livewire\Traits;

use App\Models\Chart;
use App\Models\Tenant\Company;
use Illuminate\Support\Facades\Session;

trait DynamicReportsTrait
{
    public array $availableGraphs;
    public array $availableText;
    public array $availableTables;
    public $companiesIds;

    /**
     * Mounts the DynamicReportsTrait.
     * @return void
     */
    public function mountDynamicReportsTrait($name = '')
    {
        $this->availableGraphs = Chart::select('id', 'name', 'slug', 'placeholder')
            ->where("type", "chart")->where("name", "like", "%$name%")->get()->toArray();
        $this->availableText = Chart::select('id', 'name', 'slug', 'structure')
            ->where("type", "text")->where("name", "like", "%$name%")->get()->toArray();
        $this->availableTables = Chart::select('id', 'name', 'slug', 'structure')
            ->where("type", "table")->where("name", "like", "%$name%")->get()->toArray();

        $this->companiesIds = Company::all();
    }
}
