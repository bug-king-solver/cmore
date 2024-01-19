<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\QuestionOptions\Simple;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DownloadQuestionDataController extends Controller
{
    /**
     * Get the data and export to excel download
     */
    public function index()
    {
        $params = request()->all();
        foreach ($params as &$param) {
            $arr = parseStringToArray($param);
            if ($arr == null && preg_match('/,/i', $param)) {
                $arr = explode(',', $param);
            }
            $param = $arr;
        }

        $allOptions = Simple::all()->pluck('label', 'id');
        $data = Question::withoutGlobalScopes()
            ->select(
                'questions.id',
                'answers.questionnaire_id',
                'questions.description->en as Question Text',
                'question_option_simples.label->en as Option Text',
                'question_options.question_option_id as opt_id',
                'questions.answer_type',
                'answers.value as Answer value'
            )->join('question_options', 'question_options.question_id', '=', 'questions.id')
            ->join('question_option_simples', 'question_option_simples.id', '=', 'question_options.question_option_id')
            ->join('answers', 'answers.question_id', '=', 'questions.id')
            ->whereNotNull('answers.value');

        if (isset($params['questionnaire_id'])) {
            if (!is_array($params['questionnaire_id'])) {
                $params['questionnaire_id'] = [$params['questionnaire_id']];
            }

            $data = $data->whereIn('answers.questionnaire_id', $params['questionnaire_id']);
        }

        $data = $data->get()
            ->groupBy('questionnaire_id')->map(function ($item) use ($allOptions) {
                return $item->groupBy('id')->map(function ($item) use ($allOptions) {

                    if ($item[0]['answer_type'] == 'binary') {
                        return [$item[0]];
                    }

                    $count = 0;
                    return $item->filter(function ($item) use ($allOptions, &$count) {

                        $value = parseStringToArray($item['Answer value']);
                        $keys = array_keys($value);

                        if (in_array($item['answer_type'], ['checkbox', 'checkbox-obs', 'checkbox-obs-decimal', 'checkbox-obs-long'])) {
                            if (in_array($item['opt_id'], $keys)) {
                                $item['Answer value'] = $value[$item['opt_id']];
                                return $item;
                            }
                        } else if (in_array($item['answer_type'], ['countries-all', 'countries-multi'])) {
                            $item['Answer value'] = implode(', ', $value);
                            return $item;
                        } else if (isset($value[$keys[$count] ?? null])) {
                            $item['Answer value'] = $value[$keys[$count]];
                            if (isset($value[$item['opt_id']])) {
                                $item['Answer value'] = $value[$item['opt_id']];
                            }
                            $count = $count + 1;
                            return $item;
                        }
                        return null;
                    });
                });
            });

        if ($data->count() == 0) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Question');
        $sheet->setCellValue('B1', 'Question Text');
        $sheet->setCellValue('C1', 'Option Text');
        $sheet->setCellValue('D1', 'answer_type');
        $sheet->setCellValue('E1', 'Answer value');
        $sheet->setCellValue('F1', 'Questionnaire');

        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '#FFEF10'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);

        $count = 2;
        foreach ($data as $all) {
            foreach ($all as $questions) {
                foreach ($questions as $question) {
                    $sheet->setCellValue('A' . $count, $question['id']);
                    $sheet->setCellValue('B' . $count, $question['Question Text']);
                    $sheet->setCellValue('C' . $count, $question['Option Text']);
                    $sheet->setCellValue('D' . $count, $question['answer_type']);
                    $sheet->setCellValue('E' . $count, is_array($question['Answer value']) ? implode(',', $question['Answer value']) : $question['Answer value']);
                    $sheet->setCellValue('F' . $count, $question['questionnaire_id']);
                    $count++;
                }
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "question_data_" . time() . ".xlsx";

        ob_end_clean();
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response()->make($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
