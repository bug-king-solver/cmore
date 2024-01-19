<?php

namespace App\Http\Livewire\Documents;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Enums\DocumentTypeEnum;
use App\Models\Tenant\Attachment;
use App\Models\Tenant\DocumentFolder;
use App\Models\Tenant\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    public $documentTypes;

    protected $listeners = [
        'documentFolderChanged' => '$refresh',
    ];

    public function mount()
    {
        $this->addBreadcrumb(__('Library'), route('tenant.library.index'));
    }

    /**
     * Clear the session values
     */
    public function resetValues()
    {
        session()->forget('success');
    }

    public function render(): View
    {
        $this->documentTypes = [];
        $internal = ['internal', 'attachments'];
        $framework = ['unglobal', 'gri', 'pri', 'sasb', 'unsdg'];
        $extra = ['articles', 'books'];

        foreach (DocumentTypeEnum::toArray() as $row) {
            if (in_array(strtolower($row), $internal, false)) {
                $label = strtolower($row) == 'internal' ? strtoupper(tenant('company')) : ucfirst(strtolower($row));
                $this->documentTypes['internal'][strtolower($row)] = $label;
            } elseif (in_array(strtolower($row), $framework, false)) {
                $this->documentTypes['framework'][strtolower($row)] = $row;
            } elseif (in_array(strtolower($row), $extra, false)) {
                $this->documentTypes['extra'][strtolower($row)] = new HtmlString(__(ucfirst(strtolower($row))));
            }
        }

        (new self())->resetValues();

        return tenantView(
            'livewire.tenant.documents.index'
        )->layoutData(
            [
                'mainBgColor' => 'bg-esg4',
            ]
        );
    }
}
