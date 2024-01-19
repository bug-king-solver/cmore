<?php

use App\Http\Controllers\Tenant\Api\CompaniesController;
use App\Http\Controllers\Tenant\Api\Compliance\DocumentAnalysisController;
use App\Http\Controllers\Tenant\Api\Compliance\DomainsController;
use App\Http\Controllers\Tenant\Api\Compliance\Reputational\AnalysisInfoController;
use App\Http\Controllers\Tenant\Api\Compliance\Reputational\AnalysisInfoRawController;
use App\Http\Controllers\Tenant\Api\Compliance\Reputational\EmotionsController;
use App\Http\Controllers\Tenant\Api\Compliance\Reputational\KeywordFrequencyController;
use App\Http\Controllers\Tenant\Api\Compliance\Reputational\SentimentsController;
use App\Http\Controllers\Tenant\Api\DataController;
use App\Http\Controllers\Tenant\Api\QuestionnaireController;
use App\Http\Controllers\Tenant\Api\ResourceSearchController;
use App\Http\Controllers\Tenant\Api\RolesController;
use App\Http\Controllers\Tenant\Api\TagsController;
use App\Http\Controllers\Tenant\Api\TasksController;
use App\Http\Controllers\Tenant\Api\UsersController;
use App\Http\Middleware\PreventAccessFromNotAuthorizedIp;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

Route::group([
    'domain' => config('app.url'), 'middleware' => [
        InitializeTenancyByRequestData::class,
        PreventAccessFromNotAuthorizedIp::class,
    ]
], function () {
    /**
     * The abilities middleware may be assigned to a route to verify that the incoming request's
     * token **has all** of the listed abilities:
     * The ability middleware may be assigned to a route to verify that the incoming request's
     * token **has at least one** of the listed abilities:
     */
    Route::group(['middleware' => ['auth:sanctum', 'ability:write,read']], function () {
        /**
         * Todo change 'v1' to fetch git api-tags version and use it as prefix
         */
        Route::group(['prefix' => 'v1'], function () {
            Route::get('search/{type}', [ResourceSearchController::class, 'searchResource']);

            Route::get('logged', [UsersController::class, 'loggedUser']);

            Route::apiResource('users', UsersController::class)->parameters(['users' => 'id']);

            Route::apiResource('companies', CompaniesController::class)->parameters(['users' => 'id']);
            Route::apiResource('questionnaires', QuestionnaireController::class)->parameters(['users' => 'id']);
            Route::apiResource('data', DataController::class)->parameters(['users' => 'id']);

            Route::get('companies/vat_number/{vatNumber}/', [CompaniesController::class, 'showVatNumber'])
            ->name('companies.show.var_number');

            Route::get('questionnaires/{id}/data', [QuestionnaireController::class, 'data'])
            ->name('questionnaires.data');

            Route::get('data/vat_number/{vatNumber}/', [DataController::class, 'dataIndicators'])
            ->name('data.indicators');

            Route::get('data/with/details', [DataController::class, 'dataWithDetails'])
            ->name('data.dataWithDetails');

            Route::apiResource('roles', RolesController::class)->parameters(['users' => 'id']);
            Route::apiResource('tags', TagsController::class)->parameters(['users' => 'id']);
            Route::apiResource('tasks', TasksController::class)->parameters(['users' => 'id']);

            Route::group(['prefix' => 'compliance'], function () {
                Route::get('document_analysis/', [DocumentAnalysisController::class, 'index']);
                Route::get('document_analysis/{result}/file', [DocumentAnalysisController::class, 'download']);
                Route::put('document_analysis/{result}/start', [DocumentAnalysisController::class, 'start']);
                Route::put('document_analysis/{result}/finish', [DocumentAnalysisController::class, 'finish']);
                Route::get('document_analysis/{result}', [DocumentAnalysisController::class, 'show']);

                Route::apiResource('domains', DomainsController::class, ['only' => ['index', 'show']]);
            });

            Route::group(['prefix' => 'reputational'], function () {
                Route::apiResource('analysis-info', AnalysisInfoController::class);

                Route::apiResource('analysis-info-raw', AnalysisInfoRawController::class);
                Route::get('analysis-info-raw/search/{extracted_at}', [AnalysisInfoRawController::class, 'searchRawData']);

                Route::post('keywords-frequency', [KeywordFrequencyController::class, 'store']);
                Route::post('keywords-frequency-daily', [KeywordFrequencyController::class, 'storeDaily']);
                Route::post('keywords-frequency-weekly', [KeywordFrequencyController::class, 'storeWeekly']);
                Route::post('keywords-frequency-monthly', [KeywordFrequencyController::class, 'storeMonthly']);
                Route::post('keywords-frequency-yearly', [KeywordFrequencyController::class, 'storeYearly']);

                Route::post('sentiments', [SentimentsController::class, 'store']);
                Route::post('sentiments-daily', [SentimentsController::class, 'storeDaily']);
                Route::post('sentiments-weekly', [SentimentsController::class, 'storeWeekly']);
                Route::post('sentiments-monthly', [SentimentsController::class, 'storeMonthly']);
                Route::post('sentiments-yearly', [SentimentsController::class, 'storeYearly']);

                Route::post('emotions', [EmotionsController::class, 'store']);
                Route::post('emotions-daily', [EmotionsController::class, 'storeDaily']);
                Route::post('emotions-weekly', [EmotionsController::class, 'storeWeekly']);
                Route::post('emotions-monthly', [EmotionsController::class, 'storeMonthly']);
                Route::post('emotions-yearly', [EmotionsController::class, 'storeYearly']);
            });
        });
    });
});
