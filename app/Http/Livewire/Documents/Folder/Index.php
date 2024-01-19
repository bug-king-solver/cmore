<?php

namespace App\Http\Livewire\Documents\Folder;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Attachment;
use App\Models\Tenant\DocumentFolder;
use App\Models\Tenant\Media;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\MediaLibrary\Support\MediaStream;

class Index extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    protected $listeners = [
        'documentFolderChanged' => '$refresh',
    ];

    public $documentFolder;

    public $perPageDocuments = 10;

    public $allowToCreateSubFolder = true;

    public function mount(?string $slug)
    {
        $folders = explode('/', $slug);
        $folder = array_pop($folders);
        $currentFolder = DocumentFolder::where('slug', $folder)->first();
        if ($currentFolder && $currentFolder->exists()) {
            $this->documentFolder = $currentFolder;
        } else {
            abort(404);
        }

        if ($currentFolder->parents->count() >= config('media-library.folder.max_hierarchy')) {
            $this->allowToCreateSubFolder = false;
        }

        $this->addBreadcrumb(__('Library'), route('tenant.library.index'));
        $this->addBreadcrumb(
            tenant('company'),
            route('tenant.library.show', ['documentHeading' => 'internal', 'documentType' => 'internal'])
        );

        foreach ($this->documentFolder->parents as $folder) {
            $this->addBreadcrumb($folder->name, route('tenant.library.folder.show', ['slug' => $folder->fullSlug]));
        }
        
        $this->addBreadcrumb($this->documentFolder->name, route('tenant.library.folder.show', ['slug' => $this->documentFolder->fullSlug]));
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
        $documents = Media::where('collection_name', config('media-library.collection.internal'))
            ->whereJsonContains('custom_properties->folder', $this->documentFolder->id)
            ->paginate($this->perPageDocuments);
        (new self())->resetValues();
        return view(
            'livewire.tenant.documents.folder.show',
            [
                'documentFolder' => $this->documentFolder,
                'documents' => $documents,
            ]
        );
    }

    public function download(DocumentFolder $documentFolder)
    {
        $downloads = Media::where('collection_name', config('media-library.collection.internal'))
        ->whereJsonContains('custom_properties->folder', $documentFolder->id)->get();

        return MediaStream::create($documentFolder->name . '.zip')->addMedia($downloads);
    }

    public function loadMoreDocuments()
    {
        $this->perPageDocuments += config('app.paginate.per_page');
    }
}
