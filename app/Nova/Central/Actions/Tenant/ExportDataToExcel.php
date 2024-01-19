<?php

namespace App\Nova\Central\Actions\Tenant;

use App\Models\Tenant\Data;
use App\Models\Tenant\Questionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportDataToExcel extends Action
{
    use InteractsWithQueue;
    use Queueable;

    protected $action;

    /**
     * Action constructor.
     */
    public function __construct()
    {
        $this->name = "Export data to excel";
    }

    /**
     * Get the uri  of the action.
     */
    public function uriKey()
    {
        return "export-company-data";
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $path = storage_path('app/public/');

        /** @var Tenant $tenant */
        foreach ($models as $tenant) {
            $downloadFile = null;
            $tenant->run(function () use ($path, &$downloadFile) {
                $downloadFile = $this->exportDataToExcel($path);
            });

            return Action::download(asset("storage/" . $downloadFile), $downloadFile);
        }
    }


    public function exportDataToExcel($path)
    {
        $indicatorsIds = Data::distinct('indicator_id')
            ->whereHas('indicator')
            ->whereHas('questionnaire')
            ->select("indicator_id")
            ->pluck('indicator_id', null);

        $questionnaires = Questionnaire::whereHas('reportedData')
            ->whereHas('company')
            ->with('company')
            ->with(['reportedData' => function ($reportedData) use ($indicatorsIds) {
                return $reportedData->whereIn('indicator_id', $indicatorsIds);
            }])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A' . 0, "Questionnaire");
        $sheet->setCellValue('B' . 0, "Company");
        $sheet->setCellValue('C' . 0, "Reported");

        $hasHeader = false;
        foreach ($questionnaires as $row => $questionnaire) {
            $sheet->setCellValue('A' . ($row + 1), $questionnaire->id);
            $sheet->setCellValue('B' . ($row + 1), $questionnaire['company']['name']);
            $sheet->setCellValue('C' . ($row + 1), $questionnaire->reported_at);

            $column = 4;
            foreach ($indicatorsIds as $indicatorKey => $indicatorId) {
                if (!$hasHeader) {
                    $sheet->setCellValueByColumnAndRow(($column + $indicatorKey), 2, $indicatorId);
                }

                $dataValue = array_search(
                    $indicatorId,
                    array_column($questionnaire['reportedData']->toArray(), 'indicator_id')
                ) ?? '';

                // index column
                // index row
                // value
                $sheet->setCellValueByColumnAndRow(
                    ($column + $indicatorKey),
                    ($row + 1),
                    $dataValue
                );
            }
            $hasHeader = true;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "/export_" . Str::slug(tenant()->name) .  ".xlsx";
        $filePath = $path . $fileName;
        //delete the previous file if exists
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $writer->save($filePath);
        return $fileName;
    }

    public function exportDataToExcelOlder($path)
    {
        $datas = Data::with('indicator', 'company')
            ->whereHas("indicator", function ($indicator) {
                $indicator->withTrashed();
            })
            ->orderBy('reported_at')
            ->orderBy('company_id')
            ->get()
            ->groupBy("indicator.name");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $indicators = $datas->unique("indicator_id")->values();

        $sheet->setCellValue('A1', 'Company');
        $sheet->setCellValue('B1', 'Reported at');
        $sheet->setCellValue('C1', 'Value');

        foreach ($indicators as $indicator => $indicator) {
        }

        $column = 3;
        foreach ($datas as $indicator => $data) {
            $sheet->setCellValueByColumnAndRow($column, 1, $indicator);

            foreach ($data as $index => $d) {
                $sheet->setCellValue('A' . ($index + 2), $d->company->name);
                $sheet->setCellValue('B' . ($index + 2), $d->reported_at);
                $sheet->setCellValueByColumnAndRow($column, ($index + 2), $d->value);
            }

            $column++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "/export_" . Str::slug(tenant()->name) .  ".xlsx";
        $filePath = $path . $fileName;
        //delete the previous file if exists
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $writer->save($filePath);
        return $fileName;
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request = null)
    {
        return [];
    }
}
