<?php

namespace App\Http\Livewire\SourceReport;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Data;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\SourceReport as TenantSourceReport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use PDF;

class SourceReport extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    public bool $isChecked = false;

    public $indicators;

    public $report;

    public $items;

    public $reportCategories;

    public $activeCategory;

    public bool $edit = false;

    /**
     * @var array<string, string>
     */
    protected $listeners = [
        'changeCategory' => 'changeCategory',
        'useSuggestion' => 'useSuggestion',
        'updatedData' => '$refresh',
    ];

    protected array $rules = [
        'items' => 'array',
    ];


    public function mount(Request $request, $id, $action): void
    {
        $this->edit = $action == "edit";
        $this->report = TenantSourceReport::findOrFail($id);

        $this->loadData();

        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Reports'), route('tenant.exports.index'));
    }

    /**
     * Render the component.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $reportData = $this->report->data;
        $this->items = collect($reportData)
            ->filter(function ($item, $key) {
                return $key == $this->activeCategory;
            });
        //new layout
        return view('livewire.tenant.source-reports.source-report-v1');
    }

    public function loadData(): void
    {
        $this->items = collect($this->report->data);

        foreach ($this->items as $categoryId => $item) {
            $active = $this->activeCategory != null
                ? false
                : true;

            $this->reportCategories[$categoryId] = [
                'label' => $categoryId,
                'active' => $active,
            ];

            if ($active) {
                $this->activeCategory = $categoryId;
            }
        }
    }

    public function changeCategory($id)
    {
        $this->activeCategory = $id;
    }


    public function updatedItems($value, $nexted)
    {

        $arr = explode('.', $nexted);

        $currtValue = $this->items[$arr[0]][$arr[1]][$arr[2]][$arr[3]][$arr[4]][$arr[5]] ?? '';
        if ($currtValue) {
            $this->saveData($withMessage = false);
        }
    }

    public function saveData($withMessage = true): void
    {
        $reportData = $this->report->data;

        $reportData = collect($reportData)
            ->map(function ($item, $key) {
                if ($key == $this->activeCategory) {
                    $item = $this->items[$key];
                }
                return $item;
            });

        // Save the updated data to the database
        $this->report->data = $reportData;
        $this->report->save();

        if ($withMessage) {
            session()->flash('success', __('Export successfully updated.'));
        }
    }

    // Print Report data as a pdf
    public function printData(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = [
            'report_data' => $this->report,
        ];

        $pdf = PDF::loadView('source-reports.1.print', $data)->output();
        return response()->streamDownload(static fn () => print($pdf), 'Sources Report.pdf');
    }

    public function useSuggestion(array $sugestions, string $key): void
    {
        $this->items[$key]['location'] = implode("\n", $sugestions);
        $this->emit('updatedData');
    }
}
