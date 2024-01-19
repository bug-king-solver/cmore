<?php

namespace App\Http\Livewire\Compliance\DocumentAnalysis\Modals;

use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use App\Models\Tenant\MediaType;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Report extends ModalComponent
{
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function mount(?Result $result)
    {

    }

    public function render(): View
    {
        $mediaTypesList = MediaType::all()->pluck('title', 'id');

        return view(
            'livewire.tenant.compliance.document_analysis.modal.report',
        );
    }

    public function save()
    {
        $this->closeModal();
    }
}
