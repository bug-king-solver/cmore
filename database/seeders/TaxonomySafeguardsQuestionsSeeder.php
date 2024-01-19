<?php

namespace Database\Seeders;

use App\Models\Traits\Filesystem\FileManagerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class TaxonomyQuestionsSeeder
 */
class TaxonomySafeguardsQuestionsSeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * php artisan db:seed TaxonomySafeguardsQuestionsSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        exec('rm -rf database/data/taxonomy/safeguards/*');

        $map = [
            'A' => 'question_id',
            'B' => 'question_text',
            'C' => 'help',
            'D' => 'links',
            'E' => 'answer',
            'F' => 'action_text',
            'G' => 'action',
        ];

        $file = base_path() . '/database/data/taxonomy/safeguards.xlsx';
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);
        $count = 0;

        // Sizes of questionnaire to Safeguard
        $sizes = [
            0 => ['complete'],
            1 => ['simple'],
        ];

        $questions = [];

        foreach ($spreadsheet->getWorksheetIterator() as $worksheetNUmber => $worksheet) {
            $all = [];
            if ($worksheetNUmber > 1) {
                break;
            }

            foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                $data = [];

                if ($rowNumber === 1) {
                    continue;
                }

                foreach ($row->getCellIterator() as $i => $cell) {
                    if (!isset($map[$i])) { // Remove the last validation after add data
                        continue;
                    }

                    $cellvalue = $cell->getCalculatedValue();

                    if (in_array($i, ['A', 'B', 'C'])) {
                        if ($cellvalue == null) {
                            $cellvalue = $all[$count - 1][$map[$i]] ?? null;
                        }
                    }

                    $data[$map[$i]] = $cellvalue;
                    $data['_row'] = $rowNumber;

                    if ($i === 'D') {
                        $data['link_url'] = $cell->getHyperlink()->getUrl() ?? null;
                    } elseif ($i === 'G' || $i === 'A') {
                        $data[$map[$i]] = "$cellvalue";
                    } elseif ($i === 'C') {
                        $data[$map[$i]] = $cell->getCalculatedValue();
                    } else {
                        $data[$map[$i]] = $this->parseText($cellvalue);
                    }
                }

                $all[$count] = $data;
                $count++;
            }

            $questions[] = [
                'size' => $sizes[$worksheetNUmber],
                'questions' => $this->parseDataQuestions($all),
            ];
        }

        $this->saveFileLocally(base_path() . '/database/data/taxonomy/safeguards/', 'questions', $questions);
    }

    /**
     * Parses the given data to group answers, links and actions by question.
     *
     * @param  array $datas The data to parse.
     * @return array The parsed data.
     */
    public function parseDataQuestions($datas)
    {
        $newData = [];
        $count = 0;
        foreach ($datas as $i => $data) {
            if (!isset($newData[$count])) {
                $newData[$count] = [
                    'id' => $datas[$i]['question_id'] ?? null,
                    'text' => [
                        "en" => str_replace('"', '', $datas[$i]['question_text'] ?? null),
                        "es" => str_replace('"', '', $datas[$i]['question_text'] ?? null),
                        "fr" => str_replace('"', '', $datas[$i]['question_text'] ?? null),
                        "pt-BR" => str_replace('"', '', $datas[$i]['question_text'] ?? null),
                        "pt-PT" => str_replace('"', '', $datas[$i]['question_text'] ?? null),
                    ],
                    'help' => [
                        "en" => str_replace('"', '', $datas[$i]['help'] ?? null),
                        "es" => str_replace('"', '', $datas[$i]['help'] ?? null),
                        "fr" => str_replace('"', '', $datas[$i]['help'] ?? null),
                        "pt-BR" => str_replace('"', '', $datas[$i]['help'] ?? null),
                        "pt-PT" => str_replace('"', '', $datas[$i]['help'] ?? null),
                    ],
                    'answered' => false,
                    'answered_value' => null,
                    'enabled' => $count == 0 ? true : false,
                    'links' => [],
                    'options' => []
                ];
            }

            $newData[$count]['links'][] = [
                'text' => [
                    "en" => $datas[$i]['links'] ?? null,
                    "es" => $datas[$i]['links'] ?? null,
                    "fr" => $datas[$i]['links'] ?? null,
                    "pt-BR" => $datas[$i]['links'] ?? null,
                    "pt-PT" => $datas[$i]['links'] ?? null,
                ],
                'url' => $datas[$i]['link_url'] ?? null,
            ];

            if ($datas[$i]['answer'] !== "" && $datas[$i]['answer'] !== null) {
                $newData[$count]['options'][] = [
                    'text' => $datas[$i]['answer'] ?? null,
                    'selected' => false,
                    'action_text' => $datas[$i]['action_text'] ?? null,
                    'action' => $datas[$i]['action'] ?? null,
                ];
            }

            if (!isset($datas[($i + 1)])) {
                break;
            }

            if ($data['question_text'] !== $datas[$i + 1]['question_text']) {
                $count++;
            }
        }
        return $newData;
    }

    /**
     * Parses the given text to remove quotes and replace new lines with <br> tags.
     */
    public function parseText($text)
    {
        $text = str_replace('"', '', $text);
        $text = str_replace("\n", '<br>', $text);
        return mb_convert_encoding(addslashes($text), 'UTF-8');
    }
}
