<?php

namespace App\Http\Livewire\Compliance\Reputational;

use App\Events\ReputationalAnalysisReady;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public AnalysisInfo $analysisInfo;

    protected $listeners = [
        'analysisUpdated' => '$refresh',
    ];

    public function mount(AnalysisInfo $analysisInfo)
    {
        $this->analysisInfo = $analysisInfo;
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.reputational.index',
            [
                'showSuccess' => true,
            ]
        );
    }
}
