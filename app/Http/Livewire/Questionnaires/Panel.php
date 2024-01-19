<?php

namespace App\Http\Livewire\Questionnaires;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Questionnaire;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Panel extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    protected $listeners = [
        'questionnairesChanged' => '$refresh',
    ];

    public $chartData = [
        'datasets' => [
            [
                'label' => 'Submitted-Internal x External',
                'data' => [10, 0],
                'backgroundColor' => [
                    '#fb923c',
                    '#374151',
                ],
            ],
        ],
    ];

    public $chartDataAvgTime = [];

    public $onGoingChart = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'datasets' => [[
            'label' => 'Count by Month',
            'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'backgroundColor' => '#d1d5db',
            'borderColor' => '#334155',
            'fill' => true,
        ]],
    ];

    public $onSubmittedChart = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'datasets' => [[
            'label' => 'Count by Month',
            'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'backgroundColor' => '#d1d5db',
            'borderColor' => '#334155',
            'fill' => true,
        ]],
    ];

    public $onCreatedChart = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'datasets' => [[
            'label' => 'Count by Month',
            'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'backgroundColor' => '#d1d5db',
            'borderColor' => '#334155',
            'fill' => true,
        ]],
    ];

    public function mount()
    {
        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Questionnaires'));
        $this->addBreadcrumb(__('Panel'));

        $this->chartDataAvgTime = [
            'labels' => [
                __('up to a day'),
                __('less than a week'),
                __('up to 4 weeks'),
                __('more than 4 week'),
            ],
            'datasets' => [
                [
                    'label' => __('Avg time to complete'),
                    'data' => [0, 0, 0, 0],
                    'backgroundColor' => [
                        '#E86321',
                        '#153A5B',
                        '#21A6E8',
                        '#058894',
                    ],
                ],
            ],
        ];
    }

    /**
     * Render the component.
     */
    public function render()
    {
        $businessSectors = BusinessSector::whereHas('questionnaires', function ($query) {
                $query->OnlyOwnData();
        })
            ->withCount([
                'questionnaires',
                'questionnaires as questionnaires_count' => function ($query) {
                    $query->OnlyOwnData();
                }])
            ->orderByDesc('questionnaires_count')
            ->take(5)
            ->get();

        $ongoingStats = $this->calculateOngoingStats();
        $submittedStats = $this->calculateOnSubmittedStats();
        $averageTimeToComplete = $this->calculateAverageTimeToComplete();

        $createdStats = $this->calculateCreatedStats();

        $businessSectorsQuestionnairesCount = ($totalQuestionnairesCount = $businessSectors->sum('questionnaires_count'));

        return view(
            'livewire.tenant.questionnaires.panel',
            [
                'ongoing' => $ongoingStats,
                'submitted' => $submittedStats,
                'internal_submitted_percentage' => $submittedStats['internal_submitted_percentage'],
                'external_submitted_percentage' => $submittedStats['external_submitted_percentage'],
                'avg_time_to_complete' => [
                    'up_today' => $averageTimeToComplete['percentUpToDay'],
                    'less_than_week' => $averageTimeToComplete['less_then_week'],
                    'upto_4_weeks' => $averageTimeToComplete['up_to_4_weeks'],
                    'more_than_4_weeks' => $averageTimeToComplete['more_than_4_week'],
                ],
                'business_sectors_questionnaires' => [
                    'business_sectors' => $businessSectors,
                    'business_sectors_questionnaires_count' => $businessSectorsQuestionnairesCount,
                ],
                'created_stats' => $createdStats,
            ]
        );
    }

    /**
     * Calculate calculateAverageTimeToComplete stats.
     */
    private function calculateAverageTimeToComplete()
    {
        $averageTimeToComplete = [];

        $questionnaires = Questionnaire::OnlyOwnData()->where('submitted_at', '<', Carbon::now()->subDays(1));

        $totalTime = 0;
        $count = 0;

        $questionnaires->get()->each(function ($questionnaire) use (&$totalTime, &$count) {
            if ($questionnaire->time_to_complete) {
                $totalTime += $questionnaire->time_to_complete;
                $count++;
            }
        });

        $cloneBasedQuestionnaires = clone $questionnaires;

        $numQuestionnairesUpToDay = with(clone $cloneBasedQuestionnaires)->where('time_to_complete', '<=', 24 * 60 * 60)->get();
        $numQuestionnairesLessThanWeek = with(clone $cloneBasedQuestionnaires)->where('time_to_complete', '>', 24 * 60 * 60)->where('time_to_complete', '<=', 7 * 24 * 60 * 60)->get();
        $numQuestionnairesUpTo4Weeks = with(clone $cloneBasedQuestionnaires)->where('time_to_complete', '>', 7 * 24 * 60 * 60)->where('time_to_complete', '<=', 28 * 24 * 60 * 60)->get();
        $numQuestionnairesMoreThan4Weeks = with(clone $cloneBasedQuestionnaires)->where('time_to_complete', '>', 28 * 24 * 60 * 60)->get();
        $averageTimeToComplete['percentUpToDay'] = calculatePercentage(count($numQuestionnairesUpToDay), $count);
        $averageTimeToComplete['less_then_week'] = calculatePercentage(count($numQuestionnairesLessThanWeek), $count);
        $averageTimeToComplete['up_to_4_weeks'] = calculatePercentage(count($numQuestionnairesUpTo4Weeks), $count);
        $averageTimeToComplete['more_than_4_week'] = calculatePercentage(count($numQuestionnairesMoreThan4Weeks), $count);

        $this->chartDataAvgTime['datasets'][0]['data'] = [$averageTimeToComplete['percentUpToDay'], $averageTimeToComplete['less_then_week'], $averageTimeToComplete['up_to_4_weeks'], $averageTimeToComplete['more_than_4_week']];
        return $averageTimeToComplete;
    }

    /**
     * Calculate ongoing stats.
     */
    private function calculateOngoingStats()
    {
        $baseOngoing = Questionnaire::ongoing();
        $cloneBasedOngoing = clone $baseOngoing;

        $countOnGoing = clone $baseOngoing->get();

        $countsByMonth = Questionnaire::OnlyOwnData()
            ->select(DB::raw('MONTH(created_at) AS month, COUNT(*) AS count'))
            ->whereNull('submitted_at')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $onGoingMonthsArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $onGoingMonthsArray[] = $countsByMonth[$i] ?? 0;
        }
        $this->onGoingChart['datasets'][0]['data'] = $onGoingMonthsArray;
        $countOnGoingLastMonth = clone $baseOngoing->whereMonth('created_at', Carbon::now()->subMonth()->month)->get();
        $countOnGoingCurrentMonth = clone $baseOngoing->whereMonth('created_at', Carbon::now()->month)->get();

        $internalCompaniesOngoing = clone $cloneBasedOngoing->whereHas('company', function ($q) {
            $q->where('type', 'internal');
        })->get();

        $internalCompaniesOngoingLastMonth = clone $cloneBasedOngoing->whereHas('company', function ($q) {
            $q->where('type', 'internal');
        })->whereMonth('created_at', Carbon::now()->subMonth()->month)->get();

        $internalCompaniesOngoingCurrentMonth = clone $cloneBasedOngoing->whereHas('company', function ($q) {
            $q->where('type', 'internal');
        })->whereMonth('created_at', Carbon::now()->month)->get();

        $externalCompaniesOngoing = clone $cloneBasedOngoing->whereHas('company', function ($q) {
            $q->where('type', 'external');
        })->get();

        $externalCompaniesOngoingLastMonth = clone $cloneBasedOngoing->whereHas('company', function ($q) {
            $q->where('type', 'external');
        })->whereMonth('created_at', Carbon::now()->subMonth()->month)->get();

        $externalCompaniesOngoingCurrentMonth = clone $cloneBasedOngoing->whereHas('company', function ($q) {
            $q->where('type', 'external');
        })->whereMonth('created_at', Carbon::now()->month)->get();

        $differenceOnGoingCount = count($countOnGoingLastMonth) !== 0
            ? ((count($countOnGoingCurrentMonth) - count($countOnGoingLastMonth)) / count($countOnGoingLastMonth)) * 100
            : 0;
        $differenceOnGoingInternal = count($internalCompaniesOngoingLastMonth) !== 0
            ? ((count($internalCompaniesOngoingCurrentMonth) - count($internalCompaniesOngoingLastMonth)) / count($internalCompaniesOngoingLastMonth)) * 100
            : 0;
        $differenceOnGoingExternal = count($externalCompaniesOngoingLastMonth) !== 0
            ? ((count($externalCompaniesOngoingCurrentMonth) - count($externalCompaniesOngoingLastMonth)) / count($externalCompaniesOngoingLastMonth)) * 100
            : 0;

        return  [
            'count' => count($countOnGoing),
            'on_going_0_50_progress' => $countOnGoing->whereBetween('progress', [0, 50])->count(),
            'on_going_51_100_progress' => $countOnGoing->whereBetween('progress', [51, 100])->count(),
            'difference' => round($differenceOnGoingCount),
            'count_internal' => count($internalCompaniesOngoing),
            'difference_internal' => round($differenceOnGoingInternal),
            'count_external' => count($externalCompaniesOngoing),
            'difference_external' => round($differenceOnGoingExternal),
        ];
    }

    /**
     * Calculate on submitted stats.
     */
    private function calculateOnSubmittedStats()
    {
        $baseSubmitted = Questionnaire::submitted();

        $cloneBasedSubmit = clone $baseSubmitted;

        $countOnSubmit = clone $baseSubmitted->get();

        $countsByMonthSubmitted = Questionnaire::OnlyOwnData()
            ->select(DB::raw('MONTH(submitted_at) AS month, COUNT(*) AS count'))
            ->whereNotNull('submitted_at')
            ->groupBy(DB::raw('MONTH(submitted_at)'))
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $submittedMonthsArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $submittedMonthsArray[] = $countsByMonthSubmitted[$i] ?? 0;
        }
        $this->onSubmittedChart['datasets'][0]['data'] = $submittedMonthsArray;

        $countOnSubmitLastMonth = with(clone $baseSubmitted)->whereMonth('submitted_at', Carbon::now()->subMonth()->month)->get();
        $countOnSubmitCurrentMonth = with(clone $baseSubmitted)->whereMonth('submitted_at', Carbon::now()->month)->get();

        $internalCompaniesOnSubmit = clone $cloneBasedSubmit->whereHas('company', function ($q) {
            return $q->where('type', 'internal');
        })->get();

        $internalCompaniesOnSubmitLastMonth = clone $cloneBasedSubmit->whereHas('company', function ($q) {
            $q->where('type', 'internal');
        })->whereMonth('submitted_at', Carbon::now()->subMonth()->month)->get();

        $internalCompaniesOnSubmitCurrentMonth = clone $cloneBasedSubmit->whereHas('company', function ($q) {
            $q->where('type', 'internal');
        })->whereMonth('submitted_at', Carbon::now()->month)->get();

        $externalCompaniesSubmit = clone $cloneBasedSubmit->whereHas('company', function ($q) {
            $q->where('type', 'external');
        })->get();

        $externalCompaniesOnSubmitLastMonth = clone $cloneBasedSubmit->whereHas('company', function ($q) {
            $q->where('type', 'external');
        })->whereMonth('submitted_at', Carbon::now()->subMonth()->month)->get();

        $externalCompaniesOnSubmitCurrentMonth = clone $cloneBasedSubmit->whereHas('company', function ($q) {
            $q->where('type', 'external');
        })->whereMonth('submitted_at', Carbon::now()->month)->get();


        $differenceOnSubmitCount = count($countOnSubmitLastMonth) !== 0
            ? ((count($countOnSubmitCurrentMonth) - count($countOnSubmitLastMonth)) / count($countOnSubmitLastMonth)) * 100
            : 0;

        $differenceOnSubmitInternal = count($internalCompaniesOnSubmitLastMonth) !== 0
            ? ((count($internalCompaniesOnSubmitCurrentMonth) - count($internalCompaniesOnSubmitLastMonth)) / count($internalCompaniesOnSubmitLastMonth)) * 100
            : 0;
        $differenceOnSubmitExternal = count($externalCompaniesOnSubmitLastMonth) !== 0
            ? ((count($externalCompaniesOnSubmitCurrentMonth) - count($externalCompaniesOnSubmitLastMonth)) / count($externalCompaniesOnSubmitLastMonth)) * 100
            : 0;

        $submittedStats = [
            'count' => count($countOnSubmit),
            'difference' => round($differenceOnSubmitCount),
            'count_internal' => count($internalCompaniesOnSubmit),
            'difference_internal' => round($differenceOnSubmitInternal),
            'count_external' => count($externalCompaniesSubmit),
            'difference_external' => round($differenceOnSubmitExternal),
            'internal_submitted_percentage' => calculatePercentage(count($internalCompaniesOnSubmit), count($countOnSubmit)),
            'external_submitted_percentage' => calculatePercentage(count($externalCompaniesSubmit), count($countOnSubmit)),
        ];

        $this->chartData['datasets'][0]['data'] = [
            $submittedStats['internal_submitted_percentage'], $submittedStats['external_submitted_percentage']
        ];

        return $submittedStats;
    }

    /**
     * Calculate created stats.
     */
    private function calculateCreatedStats()
    {
        $countAll = Questionnaire::OnlyOwnData()->count();
        $countsByMonth = Questionnaire::OnlyOwnData()
            ->select(DB::raw('MONTH(created_at) AS month, COUNT(*) AS count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $createdMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $createdMonths[] = $countsByMonth[$i] ?? 0;
        }

        //calculate the percentage of last motnh using submitted_at
        $countOnSubmitLastMonth = Questionnaire::OnlyOwnData()->whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $countOnSubmitCurrentMonth = Questionnaire::OnlyOwnData()->whereMonth('created_at', Carbon::now()->month)->count();

        $differenceOnSubmitCount = $countOnSubmitLastMonth !== 0
            ? (($countOnSubmitCurrentMonth - $countOnSubmitLastMonth) / $countOnSubmitLastMonth) * 100
            : 0;



        $this->onCreatedChart['datasets'][0]['data'] = $createdMonths;

        return  [
            'count' => $countAll,
            'created_months' => $createdMonths,
            'difference' => round($differenceOnSubmitCount),
        ];
    }
}
