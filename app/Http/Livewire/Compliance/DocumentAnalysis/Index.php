<?php

namespace App\Http\Livewire\Compliance\DocumentAnalysis;

use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $listeners = [
        'resultChanged' => '$refresh',
        'resultAltered' => '$refresh',
    ];

    public function render(): View
    {
        $results = Result::list(auth()->user())->paginate(config('app.paginate.per_page'));

        return view(
            'livewire.tenant.compliance.document_analysis.index',
            [
                'results' => $results,
            ]
        );
    }
}
