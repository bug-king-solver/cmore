<?php

namespace Database\Seeders;

use App\Models\Traits\Filesystem\FileManagerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class TaxonomyQuestionsSeeder
 */
class TaxonomyQuestionsSeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        exec('rm -rf database/data/taxonomy/questionnaires/*');

        $map = [
            'A' => 'sector',
            'C' => 'activity',
            'D' => 'activity_code',
            'E' => 'activity_description',
            'F' => 'category',
            'G' => 'objective',
            'H' => 'question_id',
            'I' => 'question_text',
            'J' => 'help',
            'K' => 'links',
            'L' => 'answer',
            'M' => 'action_text',
            'N' => 'action',
            'O' => 'transition_enabling',
        ];

        $file = base_path() . '/database/data/taxonomy/taxonomy.xlsx';
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);

        foreach ($spreadsheet->getWorksheetIterator() as $i => $worksheet) {
            $all = [];
            $count = 0;

            // Ignore "Apendices" e "ÍNDICE"
            if ($i < 2) {
                continue;
            }

            $highestRow = $worksheet->getHighestRow();
            foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                $data = [];

                if ($rowNumber === 1) {
                    continue;
                }

                foreach ($row->getCellIterator() as $i => $cell) {
                    if (!isset($map[$i])) { // Remove the last validation after add data
                        continue;
                    }

                    $cellvalue = $cell->getCalculatedValue() ?? $all[$count - 1][$map[$i]] ?? null;
                    $data[$map[$i]] = $cellvalue;
                    $data['_row'] = $rowNumber;

                    if ($i === 'A') {
                        $data[$map[$i]]  = $cellvalue;
                    } elseif ($i === 'D' || $i === 'H' || $i === 'N') {
                        $data[$map[$i]] = "$cellvalue";
                    } elseif ($i === 'K') {
                        $data['link_url'] = $cell->getHyperlink()->getUrl() ?? null;
                    } elseif ($i === 'J' || $i === 'O') {
                        $data[$map[$i]] = $cell->getCalculatedValue();
                    }
                }

                $all[$count] = $data;


                /**
                 * Check if the current row activity_code is different from the previous one.
                 * If yes, means that the previous activity is finished and we need to prepare data to be imported
                 */
                if (isset($all[$count - 1]) && isset($all[$count])) {
                    if ($all[$count]['activity_code'] != $all[$count - 1]['activity_code']) {
                        $currentData = $all[$count];
                        unset($all[$count]);

                        $this->prepareDataToBeImported($all);
                        $count = 0;
                        $all = [];
                        $all[$count] = $currentData;
                        $count++;
                        continue;
                    }
                }

                /**
                 * Verify if is the last row. If yes , prepare data to be imported
                 */
                if ($rowNumber === $highestRow) {
                    $this->prepareDataToBeImported($all);
                    continue;
                }

                $count++;
            }
        }
    }

    /**
     * prepare data to be imported
     * @param $loadedActivities
     */
    public function prepareDataToBeImported($loadedActivities)
    {
        // group by question_id column
        $groupedActivities = array_reduce($loadedActivities, function ($carry, $item) {
            $category = $item['category'];
            $categoryId = 'die';
            if (strtolower(trim($category)) === 'contribui substancialmente para um dos objetivos') {
                $categoryId = 'cs';
            } elseif (strtolower(trim($category)) === 'contribuir substancialmente para um dos objetivos') {
                $categoryId = 'cs';
            } elseif (strtolower(trim($category)) === 'contribui subtancialmente para um dos objetivos') {
                $categoryId = 'cs';
            } elseif (strtolower(trim($category)) === 'não prejudicar significativamente') {
                $categoryId = 'dnsh';
            } elseif (strtolower(trim($category)) === 'não prejudica substancialmente') {
                $categoryId = 'dnsh';
            } elseif (strtolower(trim($category)) === 'não prejudica significativamente nenhum dos objetivos') {
                $categoryId = 'dnsh';
            }
            $carry[$categoryId][] = $item;
            return $carry;
        }, []);

        // group by category
        $groupedObjectives = [];
        foreach ($groupedActivities as $categoryId => $activities) {
            $groupedObjectives[$categoryId] = array_reduce($activities, function ($carry, $item) {
                $carry[$item['objective']][] = $item;
                return $carry;
            }, []);
        }


        foreach ($groupedObjectives as $categoryId => $categoryData) {
            $count = 1;
            foreach ($categoryData as $objectiveId => $objectiveData) {
                $dir = base_path() . '/database/data/taxonomy/questionnaires/';

                $objectiveName = Str::slug(str_replace(['(', ')', ' '], '_', $objectiveId));
                $sectorName = Str::slug($objectiveData[0]['sector']);
                $code = rtrim($objectiveData[0]['activity_code'], ".") ?? null;

                $errorMessage = null;
                if ($categoryId == 'die' && $objectiveData[0]['objective'] != "") {
                    $errorMessage = 'Category ID not found';
                }

                if ($errorMessage !== null) {
                    $dir .= 'errors/' . Str::slug(str_replace(['(', ')', ' '], '_', $sectorName));
                    $dir .= '/' . Str::slug($errorMessage) . "/{$categoryId}/";
                    $this->saveFileLocally(
                        $dir,
                        $objectiveName,
                        [
                            'possibleError' => $errorMessage,
                            'categoryId' => $categoryId,
                            'code' => $code,
                            'objectiveData' => $objectiveData,
                        ]
                    );

                    continue;
                }

                $dir = base_path() . '/database/data/taxonomy/questionnaires/';
                $dir .= "{$sectorName}/{$code}/{$categoryId}/";

                $data = $this->parseDataQuestions($objectiveData);

                $this->saveFileLocally($dir, "$count-$objectiveName", $data);
                $count++;
            }
        }
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
                    'sector' => $data['sector'] ?? null,
                    'activity' => $data['activity'] ?? null,
                    'activity_code' => $data['activity_code'] ?? null,
                    'activity_description' => $data['activity_description'] ?? null,
                    'category' => [
                        "en" => $data['category'] ?? null,
                        "es" => $data['category'] ?? null,
                        "fr" => $data['category'] ?? null,
                        "pt-BR" => $data['category'] ?? null,
                        "pt-PT" => $data['category'] ?? null,
                    ],
                    'objective' => $data['objective'] ?? null,
                    'id' => $datas[$i]['question_id'] ?? null,
                    'text' => [
                        "en" => $this->parseText($datas[$i]['question_text']),
                        "es" => $this->parseText($datas[$i]['question_text']),
                        "fr" => $this->parseText($datas[$i]['question_text']),
                        "pt-BR" => $this->parseText($datas[$i]['question_text']),
                        "pt-PT" => $this->parseText($datas[$i]['question_text']),
                    ],
                    'help' => [
                        "en" => $this->parseText($datas[$i]['help']),
                        "es" => $this->parseText($datas[$i]['help']),
                        "fr" => $this->parseText($datas[$i]['help']),
                        "pt-BR" => $this->parseText($datas[$i]['help']),
                        "pt-PT" => $this->parseText($datas[$i]['help']),
                    ],
                    'answered' => 0,
                    'answered_value' => null,
                    'enabled' => $count == 0 ? 1 : 0,
                    'links' => [],
                    'options' => [],
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

            $newData[$count]['options'][] = [
                'text' => $datas[$i]['answer'] ?? null,
                'selected' => 0,
                'action_text' => $datas[$i]['action_text'] ?? null,
                'action' => $datas[$i]['action'] ?? null,
                'transition_enabling' => $datas[$i]['transition_enabling'] ?? null,
            ];

            $answers = collect($newData[$count]['options']);
            $links = collect($newData[$count]['links']);

            $answers = $this->fixAnswers($answers);
            $links = $this->fixLinks($links);

            $newData[$count]['options'] = $answers;
            $newData[$count]['links'] = $links;

            if (!isset($datas[($i + 1)])) {
                break;
            }

            if ($data['question_id'] !== $datas[$i + 1]['question_id']) {
                $count++;
            }
        }
        return $newData;
    }

    // remove answer where text , action and action_text are repeated
    public function fixAnswers($answers)
    {
        $answers = $answers->unique(function ($item) {
            return $item['text'] . $item['action'] . $item['action_text'];
        });

        return $answers->values()->all();
    }

    // remove links text and url are repeated
    public function fixLinks($links)
    {
        $links = $links->unique(function ($item) {
            return $item['text']['en'] . $item['url'];
        });

        // remover links where url is null
        $links = $links->filter(function ($item) {
            return $item['url'] != '';
        });
        return $links->values()->all();
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
