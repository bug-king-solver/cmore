<?php

namespace App\Http\Livewire\Documents;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Enums\DocumentTypeEnum;
use App\Models\Tenant\Attachment;
use App\Models\Tenant\DocumentFolder;
use App\Models\Tenant\Media;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    protected $listeners = [
        'attachmentsChanged' => '$refresh',
        'documentFolderChanged' => '$refresh',
    ];

    protected string $view = 'show';

    public string $disk = 'library';

    public string $documentHeading;

    public string $documentType;

    public ?string $documentFolder;

    protected $folders;

    protected $documents;

    public $perPageFolders = 10;

    public $perPageDocuments = 10;

    public function mount($documentHeading, $documentType = 'internal')
    {
        $this->documentHeading = $documentHeading;
        $this->documentType = $documentType;
        $this->documentFolder = request()->query('folder') ?? false;

        $this->addBreadcrumb(__('Library'), route('tenant.library.index'));
        $this->addBreadcrumb(tenant('company'));
    }

    public function render(): View
    {
        $documentTypeEnum = $this->documentType;
        $this->folders = DocumentFolder::where('parent_id', '0')->when(
            auth()->user()->type != 'internal',
            function ($query) {
                $query->where('visibility', 'external');
            })
            ->paginate($this->perPageFolders);

        switch ($this->documentType) {
            case 'gri':
            case 'sasb':
            case 'articles':
            case 'books':
            case 'compact':
            case 'pri':
            case 'unglobal':
            case 'unsdg':
                $files = DocumentTypeEnum::$documentTypeEnum()->value . '/' . auth()->user()->locale;
                $documents = Storage::disk('library')->files($files);
                break;

            case 'attachments':
                $documents = auth()->user()->media()->where(
                    'collection_name',
                    config('media-library.collection.attachments')
                )->whereJsonContains(
                    'custom_properties->folder',
                    null
                )->paginate($this->perPageDocuments);

                $this->disk = $this->documentFolder ? 'local' : $this->disk;
                $this->view = $this->documentFolder ? 'show' : 'show-attachment';
                break;

            case 'internal':
                $documents = Media::where(
                    'collection_name',
                    config('media-library.collection.internal')
                )->whereJsonContains(
                    'custom_properties->folder',
                    null
                )->paginate($this->perPageDocuments);
                $this->disk = $this->documentFolder ? 'local' : $this->disk;
                $this->view = $this->documentFolder ? 'show' : 'show-internal';
                break;

            default:
                abort(404);
                break;
        }

        if (! $this->documentFolder) {
            if ($this->documentHeading === 'extra') {
                $title = str_replace('_', ' ', ucfirst(strtolower(DocumentTypeEnum::$documentTypeEnum()->label)));
            } else {
                $title = str_replace('_', ' ', DocumentTypeEnum::$documentTypeEnum()->label);
            }
        } else {
            $title = ucwords(implode(' / ', explode('/', $this->documentFolder)));
            $this->documentHeading = explode(' / ', $title)[0];
        }

        return tenantView(
            'livewire.tenant.documents.' . $this->view,
            [
                'title' => $title,
                'disk' => $this->disk,
                'documents' => $documents,
                'folders' => $this->folders,
            ]
        )
            ->layoutData(
                [
                    'mainBgColor' => 'bg-esg4',
                ]
            );
    }

    public function loadMoreFolders()
    {
        $this->perPageFolders += config('app.paginate.per_page');
    }

    public function loadMoreDocuments()
    {
        $this->perPageDocuments += config('app.paginate.per_page');
    }
}
