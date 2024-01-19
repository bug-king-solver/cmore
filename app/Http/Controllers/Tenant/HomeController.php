<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Crm\Company;
use App\Models\Tenant\Data;
use App\Models\Tenant\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hasQuestionnaires = auth()->user()->readyQuestionnaires->count();

        $companies = Company::count();
        $totalQuestionnaires = Questionnaire::count();
        $cards = [
            'questionnaires' => [
                'value' => Questionnaire::submitted()->count(),
                'total' => $totalQuestionnaires,
            ],
            'esg_information' => [
                'value' => Data::where('indicator_id', 7700)
                ->distinct("company_id")
                ->count(),
                'total' => $companies,
            ],
            'obligated_taxonomy' => [
                'value' => Data::where('indicator_id', 6778)
                ->distinct("company_id")
                ->count(),
                'total' => $companies,
            ],
        ];

        $questionnaireUrl = $hasQuestionnaires
            ? route('tenant.questionnaires.form')
            : route(
                auth()->user()->companies->count()
                ? 'tenant.questionnaires.panel'
                : 'tenant.companies.index'
            );

        return tenantView('tenant.home', [
            'hasQuestionnaires' => $hasQuestionnaires,
            'questionnaireUrl' => $questionnaireUrl,
            'cards' => $cards,
        ]);
    }

    /**
     * Logout from SSO.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if (tenant()->getSaml2()) {
            auth()->logout();
            return redirect()->away(tenant()->getSaml2()->idp_logout_url);
        }
        return redirect()->route('tenant.home');
    }

    protected function logoutPage(Request $request)
    {
        if (tenant() && tenant()->getSaml2()) {
            auth()->logout();
            return view('tenant.auth.logout');
        }
        return redirect()->route('tenant.home');
    }
}
