<?php

namespace App\Http\Livewire\Questionnaires;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Source;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View as ViewView;
use Livewire\WithPagination;
use PDF;

class Data extends FilterBarComponent
{
    use WithPagination;

    public Questionnaire $questionnaire;

    public function mount(Questionnaire $questionnaire)
    {
        parent::initFilters($model = \App\Models\Tenant\Data::class);
        $this->model = new \App\Models\Tenant\Data();
    }

    public function getSourcesProperty()
    {
        return isset($this->activeFiltersParseValues) && count($this->activeFiltersParseValues) > 0
            ? $this->activeFiltersParseValues['reference_filter']['values']
            : Source::pluck('name')->toArray();
    }

    public function render(): ViewView
    {
        $data = $this->questionnaire->dataList()->paginate(config('app.paginate.per_page'));

        return tenantView(
            'livewire.tenant.questionnaires.data', [
                'data' => $data,
            ]
        );
    }

    public function exportCsv()
    {
        $exportData = $this->questionnaire->dataList()->get();
        $fileName = 'Indicators.csv';

        // // Set the headers for CSV download

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Open output stream
        $file = fopen('php://output', 'w');

        $columns = ['Indicators', 'Value', 'Unit'];
        $columns = array_merge($columns, $this->getSourcesProperty());


        $callback = function() use($exportData, $columns,$file) {
            fputcsv($file, $columns);

            foreach ($exportData as $indicator) {
                $row = [];
                $row['Indicators'] = $indicator->indicator->name;
                $row['Value'] = $indicator->value;
                $row['Unit'] = $indicator->indicator->unit_default;

                foreach ($this->getSourcesProperty() as $sourceName) {
                    $source = $indicator->indicator->sources->firstWhere('name', $sourceName);
                    if ($source) {
                        $row[$source->name] = $source->pivot->reference;
                    } else {
                        $row[$sourceName] = null;
                    }
                }

                $sourceDataArray  = [
                    $row['Indicators'],
                    $row['Value'],
                    $row['Unit'],

                ];
                foreach($this->getSourcesProperty() as $sourceColumn)
                {
                    array_push($sourceDataArray,$row[$sourceColumn]);
                }
                // Write the row to the CSV file
                fputcsv($file, $sourceDataArray);
            }

            fclose($file);
        };

        return response()->streamDownload($callback,$fileName,$headers);

    }


    public function exportPdf()
    {
        $fileName = 'indicators.pdf';

        $data = [
            'indicators' => $this->questionnaire->dataList()->get(),
            'sources_columns' => $this->getSourcesProperty()
        ];

        $pdf = PDF::loadView('tenant.questionnaires.print.data', $data)->output();

        return response()->streamDownload(fn () => print($pdf),$fileName);
    }
}
