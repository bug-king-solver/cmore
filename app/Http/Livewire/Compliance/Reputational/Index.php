<?php

namespace App\Http\Livewire\Compliance\Reputational;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsDaily;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    protected $listeners = [
        'analysisChanged' => 'analysisAddSuccess',
    ];

    public $showSuccess = false;

    public $analysisInfoList = [];

    public $analysisInfo = [];

    public $selectedAnalysis;

    public $selectedRange;

    public $selectedRangeArr;

    public function mount(?AnalysisInfo $analysisInfo)
    {
        $this->analysisInfoList = AnalysisInfo::orderBy('id', 'desc')->get();
        $this->analysisInfo = $analysisInfo->exists ? $analysisInfo : AnalysisInfo::orderBy('id', 'desc')->first();
        $this->selectedAnalysis = $this->analysisInfo ? $this->analysisInfo->id : '';
        $this->selectedRangeArr = [
            Carbon::now()->subDays(30)->toDateString(),
            Carbon::now()->toDateString()
        ];
        $this->selectedRange = implode(' to ', $this->selectedRangeArr);
        $analysisSentiments = AnalysisEmotionsDaily::where('ainfo_id', $this->selectedAnalysis);
        $analysisKeywordFrequency = AnalysisKeywordsFrequencyDaily::where('ainfo_id', $this->selectedAnalysis);
        $analysisEmotions = AnalysisSentimentsDaily::where('ainfo_id', $this->selectedAnalysis);

        if (! $analysisSentiments->exists() && ! $analysisKeywordFrequency->exists() && ! $analysisEmotions->exists()) {
            $this->showSuccess = true;
        }
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.reputational.index', [
                'showSuccess' => $this->showSuccess,
                'analysisInfoForSelect' => parseDataForSelect($this->analysisInfoList, 'id', 'name'),
            ]
        );
    }

    public function analysisAddSuccess($saveData)
    {
        return redirect(route('tenant.reputation.show', ['analysisInfo'=>$saveData['analysisInfo']]));
    }

    public function updatedSelectedAnalysis($selectedAnalysis)
    {
        $this->selectedAnalysis = $selectedAnalysis;
    }

    public function redirecrtToSelectedAnalysis()
    {
        return redirect(route('tenant.reputation.show', ['analysisInfo'=>$this->selectedAnalysis]));
    }
}
