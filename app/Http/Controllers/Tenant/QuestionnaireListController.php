<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Questionnaire;
use Illuminate\Support\Facades\Cookie;

class QuestionnaireListController extends Controller
{
    public function index()
    {
        $period = Cookie::get('period');

        $results = array_map(
            function ($questionnaire) {
                return ['id' => $questionnaire['id'], 'name' => $questionnaire['company']['name']];
            },
            auth()->user()->submittedQuestionnaires()
                ->whereYear('from', $period ?? 2022)
                ->with('company')
                ->get()
                ->toArray()
        );

        $questionnaireList = parseDataForSelect($results, 'id', 'name');

        return response()->json($questionnaireList);
    }
}
