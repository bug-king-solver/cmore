<?php

namespace App\Http\Livewire\Charts\ReportCharts;

use App\Models\Chart;
use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
use App\Models\Traits\DashboardData;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ChartStruct extends Component
{
    use AuthorizesRequests;
    use DashboardData;

    public array $info;
    public $companies;
    public $index;

    public $slug;
    public $chart;
    public $structure;
    public $indicators;
    public $name;
    public $backgroundColor;
    public $type;

    protected $listeners = ['refreshComponent' => 'refreshGraph'];

    /**
     * Mounts the component with the given information.
     *
     * @param array $info The information to mount the component with.
     * @return void
     */
    public function mount(array $info, $companies, $index)
    {
        $this->info = $info;
        $this->slug = $info["value"];
        $this->companies = $companies;
        $this->index = $index;

        $this->chart = Chart::where("slug", $this->slug)->first();
        $this->indicators = parseStringToArray($this->chart->indicators ?? '');

        $this->name = $this->info['structure']['title'] ?? $this->chart->name;
    }

    /**
     * Renders the component and dispatches a browser event to reload the chart data.
     *
     * @return \Illuminate\View\View|null The rendered view or null if the chart type is not doughnut.
     */
    public function render()
    {
        $this->structure = $this->getGraphsStructure();

        return view('livewire.tenant.dynamic-dashboard.chart-struct')->layoutData(['mainBgColor' => 'bg-esg4',]);
    }

    /**
     * GEt chart type
     */
    public function getChartType()
    {
        return $this->info['structure']['type'] ?? $this->chart->structure['type'] ?? null;
    }

    /**
     * Gets the background colors for the chart.
     *
     * @return string The background colors for the chart.
     */
    public function getBackgroundColors()
    {
        return array_values($this->info['structure']['data']['datasets'][0]['backgroundColor'] ??
            $this->chart->structure['data']['datasets'][0]['backgroundColor'] ?? []);
    }

    /**
     * Gets the data for the chart.
     */
    public function getIndicatorData($graph): object
    {
        $filter = Session::get('searchDashboard');

        $companies = $this->companies;
        if (isset($filter["company"]) && $filter["company"] != null) {
            $companies = Company::whereIn("id", $filter["company"])->get();
        }

        if (isset($filter["businessSectors"]) && $filter["businessSectors"] != null) {
            $companies = $companies->whereIn("business_sector_id", $filter["businessSectors"]);
        }

        if (isset($filter["countries"]) && $filter["countries"] != null) {
            $companies = $companies->whereIn("country", $filter["countries"]);
        }

        if (in_array($graph, ['pie', 'doughnut'])) {
            return Indicator::whereIn('id', $this->indicators)
                ->withSum(['data' => function ($query) use ($companies) {
                    $query->whereIn("company_id", $companies->pluck('id')->toArray());
                }], 'value')
                ->get();
        }

        return Indicator::whereIn('id', $this->indicators)
            ->with(['dataNoOrder' => function ($query) use ($companies) {
                $query->whereIn("company_id", $companies->pluck('id')->toArray())
                    ->select(
                        'indicator_id',
                        DB::raw("SUM(data.value) as total"),
                        DB::raw('YEAR(data.reported_at) as report_year'),
                        DB::raw('MONTH(data.reported_at) as report_month'),
                    )
                    ->groupBy(
                        DB::raw('YEAR(data.reported_at)'),
                        DB::raw('MONTH(data.reported_at)'),
                        'data.indicator_id'
                    )
                    ->orderBy('report_year', 'asc')
                    ->orderBy('report_month', 'asc')
                    ->orderBy('total', 'desc');

            }])
            ->get();
    }

    /**
     * Gets the data for the chart.
     * If the search criteria is stored in the session, the data is filtered by the search criteria.
     */
    private function getGraphsStructure(): array|object
    {
        $structure = parseStringToArray($this->info['structure'] ?? '');
        $this->structure = $structure != null
            ? $structure
            : $this->chart->structure;

        $charType = $this->getChartType();

        if (!in_array($charType, ['pie', 'doughnut'])) {
            $this->structure['data']['labels'] = lastTwelweMonth();

            if (count($this->structure['data']['datasets'] ?? []) != count($this->indicators ?? [])) {
                foreach ($this->indicators as $i => $indicator) {
                    if (!isset($this->structure['data']['datasets'][$i])) {
                        $this->structure['data']['datasets'][$i] = $this->structure['data']['datasets'][$i - 1] ?? null;
                    }
                }
            }
        }

        if (!in_array($charType, ['pie', 'doughnut'])) {
            $indicators = $this->getIndicatorData($charType)->toArray();
            for ($i = 0; $i < count($indicators); $i++) {
                $indicator = $indicators[$i];
                $nameData = json_decode($indicator['name'], true);
                $name = ($i + 1) . ' - ' . $nameData[session()->get('locale')];
                if (strlen($name) > 65) {
                    $name = substr($name, 0, 65) . '...';
                }
                $indicators[$i]['name'] = $name;
            }
        } else {
            $indicators = $this->getIndicatorData($charType);
        }

        foreach ($indicators as $i => $indicator) {
            $indicator['data'] = $indicator['data_no_order'];
            if (!isset($this->structure['data']['datasets'][0]["data"])) {
                $this->structure['data']['datasets'][0]["data"] = [];
            }

            $name = $this->info['structure']['data']['datasets'][0]['label'][$indicator['id']] ?? $indicator['name'];
            $name = ($i + 1) . ' - ' . $name;
            if (strlen($name) > 65) {
                $name = substr($name, 0, 65) . '...';
            }

            if (!in_array($charType, ['pie', 'doughnut'])) {
                $datasetData = $this->putDataAccordingMonthYear($indicator['data']);
                $this->structure['data']['datasets'][$i]['data'] = $datasetData;
                $this->structure['data']['datasets'][$i]['label'] = $name;
                $this->structure['data']['datasets'][$i]['backgroundColor'] = $this->getBackgroundColors();

                if (in_array($charType, ['scatter', 'bubble'])) {
                    $this->structure['options']['scales']['xAxes']['ticks']['stepSize'] = 1;

                    foreach ($this->structure['data']['datasets'][$i]['data'] as $key => $value) {
                        $this->structure['data']['datasets'][$i]['data'][$key] = [
                            'x' => $indicator['data'][$key]['report_month'],
                            'y' => $value,
                            'r' => 7,
                        ];
                    }
                }

                if ($charType === 'horizontalBar') {
                    $this->structure['options']['indexAxis'] = 'y';
                }

                if ($charType === 'radar' || $charType === 'polarArea') {
                    $this->structure['options']['scales']['y']['display'] = false;
                }

                if ($charType === 'scatter') {
                    $this->structure['data']['datasets'][$i]['showLine'] = true;
                }
            } else {
                $this->structure['data']['labels'][] = $name;
                $this->structure['data']['datasets'][0]["data"][] = $indicator->data_sum_value ?? 0;
                $this->structure['data']['datasets'][0]['backgroundColor'] = $this->getBackgroundColors();
            }
        }

        $this->structure['type'] = $this->getChartType();

        if ($charType == 'horizontalBar') {
            $this->structure['type'] = 'bar';
        } else {
            $this->structure['type'] = $charType;
        }

        if (in_array($charType, ['pie', 'doughnut'])) {
            $this->structure['data']['datasets'][0]['label'] = [];
        }

        
        return $this->structure;
    }

    public function refreshGraph()
    {
        $this->structure = $this->getGraphsStructure();
        $this->dispatchBrowserEvent('chartUpdated', [
            'chartData' => $this->structure,
        ]);
    }

    public function putDataAccordingMonthYear($data)
    {
        $dataset = array_fill(0, 11, 0);
        $labels = array_flip(lastTwelweMonth());
        
        if (isset($data) && count($data) > 0) {
            foreach ($data as $indicatorData) {
                $month  = strtolower(convertToShortMonthName($indicatorData['report_month']));
                $year  = substr($indicatorData['report_year'], -2);
                $monthYear = $month.'-'.$year;
                $findIndex = isset($labels[$monthYear]) ? $labels[$monthYear] : null;
                
                if (!is_null($findIndex)) {
                    $dataset[$findIndex] = $indicatorData['total'];
                }
            }
        }
       
        return $dataset;
    }
}
