<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Data;
use App\Models\Tenant\Questionnaire;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class Export extends Component
{
    public Questionnaire | int $questionnaire;

    public function pdf(Questionnaire $questionnaire)
    {
        $pdf = Pdf::loadView('tenant.questionnaires.export', [
            'questionnaire' => $questionnaire,
            'questions' => $questionnaire->questions()
        ]);

        return $pdf->stream('questionnaire.pdf');
    }

    /**
     * Export the questionnaire to CSV
     * @param Questionnaire $questionnaire
     */
    public function csv(Questionnaire $questionnaire)
    {
        $data = Data::where('questionnaire_id', $questionnaire->id)
        ->with('indicator.category')
        ->get();

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=Questionnaire.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Name', 'Category', 'Value', 'Unit'];

        return response()->stream(function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $row) {
                $name = $row->indicator?->name ?? ''; 
                $category = $row->indicator?->category?->name ?? '';
                $value = $row->value ?? '';
                $unit = $row->indicator?->unit_qty ?? 'N/A';

                fputcsv($file, [$name, $category, $value, $unit]);
            }

            fclose($file);
        }, 200, $headers);
    }
}
