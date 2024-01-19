<?php

namespace Database\Seeders\Tenant\Compliance\DocumentAnalysis;

use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use App\Models\Tenant\Compliance\DocumentAnalysis\Snippet;
use App\Models\Tenant\MediaType;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DomainsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** disable foreing key check */
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Snippet::truncate();
        Domain::truncate();
        Result::truncate();
        MediaType::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = base_path() . '/database/data/compliance/documents_domain.xlsx';

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->setActiveSheetIndex(0);
        $mediaTypes = $this->iterateXlsxData($worksheet);
        unset($mediaTypes[1]);
        unset($mediaTypes[2]);

        $mediaTypes = array_values($mediaTypes);
        $mediaTypes = collect($mediaTypes)->map(function ($item) {
            return [
                'title' =>  $item[1],
            ];
        })->toArray();

        $worksheet = $spreadsheet->setActiveSheetIndex(1);
        $domains = $this->iterateXlsxData($worksheet);

        $domains = collect($domains)->map(function ($item) {
            return [
                'document_analysis_type_id' => $item[1] ?? null,
                'enabled' => 1,
                'title' => ['en' => $item[2] ?? ''],
                'description' => ['en' => $item[3] ?? ''],
                'terms_base' => ['en' => $item[4] ?? ''],
                'terms_prefixes' => ['en' => $item[5] ?? ''],
                'terms_suffixes' => ['en' => $item[6] ?? ''],
                'terms_both' => ['en' => $item[7] ?? ''],
            ];
        });

        foreach ($mediaTypes as $mediaType) {
            $media = MediaType::create($mediaType);
            $mediaDomains = $domains->where('document_analysis_type_id', $media->id)->toArray();
            foreach ($mediaDomains as $mediaDomain) {
                Domain::create($mediaDomain);
            }
        }
    }

    public function iterateXlsxData($worksheet)
    {
        $dados = [];
        foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
            foreach ($row->getCellIterator() as $colNumber => $cell) {
                $value = $cell->getValue();
                if ($value) {
                    $dados[$rowNumber][] = $cell->getValue();
                }
            }
        }

        return $dados;
    }
}
