<?php

namespace App\Http\Livewire;

use App\Models\Tenant\Company;
use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Tenant\Role;
use App\Models\Tenant\Tag;
use App\Models\Tenant\Target;
use App\Models\Tenant\Task;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportData extends Component
{
    /**
     * Export data to excel
     * @param string $tableName - the name of the table to export
     * @return Response|StreamedResponse
     */
    public function exportToCSV(string $tableName): Response|StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/xlsx; charset=utf-8",
            "Content-Disposition" => "attachment; filename={$tableName}.xlsx",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $model = $this->getModel($tableName);

        $columns = Schema::getColumnListing((new $model())->getTable());
        $data = DB::table($tableName)
            ->select($columns);

        // get the position of column id
        $idCol = array_search('id', $columns);
        // remove the id column from the array
        unset($columns[$idCol]);
        //add the id column to the beginning of the array
        array_unshift($columns, 'id');

        if (in_array('deleted_at', $columns)) {
            $data->whereNull('deleted_at');
        }

        $data = $data->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($columns as $excelCol => $col) {
            $sheet->setCellValueByColumnAndRow(($excelCol + 1), 1, $col);
        }


        foreach ($data as $rowNumber => $row) {
            foreach ($columns as $excelCol => $col) {
                $sheet->setCellValueByColumnAndRow(($excelCol + 1), ($rowNumber + 2), $row->$col);
            }
        }

        $writer = new Xlsx($spreadsheet);
        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, $headers);
    }

    /**
     * @param string $tableName
     */
    private function getModel(string $tableName): ?string
    {
        $models = [
            'companies' => Company::class,
            'assets' => BankAssets::class,
            'targets' => Target::class,
            'tasks' => Task::class,
            'tags' => Tag::class,
            'users' => User::class,
            'roles' => Role::class,
        ];

        return $models[$tableName] ?? null;
    }

    /**
     * Render the component
     * @return \Illuminate\View\View|Factory
     */
    public function render(): \Illuminate\View\View|Factory
    {
        return view('livewire.tenant.export-data');
    }
}
