<?php

namespace App\Http\Livewire\Compliance\Legislation;

use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render(): View
    {
        /**
         * FIX this
         */
        $result = Result::find(3);

        return view(
            'livewire.tenant.compliance.legislation.index',
            [
                'result' => $result,
            ]
        );
    }
}
