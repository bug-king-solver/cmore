<?php

namespace App\Http\Livewire\Compliance\DocumentAnalysis;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Result $result;
    public $domainsWithSnippets = [];
    public $domainsWithoutSnippets = [];

    protected function getListeners()
    {
        return [
            'complianceChanged' => '$refresh',
        ];
    }

    public function mount(Result $result)
    {
        $this->result = $result;
        $this->domainsWithSnippets = Domain::whereHas('snippets')
        ->where('document_analysis_type_id', $this->result->document_analysis_type_id)->get();
        $this->domainsWithoutSnippets = Domain::doesntHave('snippets')
        ->where('document_analysis_type_id', $this->result->document_analysis_type_id)->get();
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.document_analysis.show'
        );
    }
}
