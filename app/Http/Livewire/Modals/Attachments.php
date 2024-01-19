<?php

namespace App\Http\Livewire\Modals;

use App\Models\Tenant\Attachment;
use App\Models\Tenant\DocumentFolder;
use App\Models\Tenant\Media;
use App\Models\Tenant\MediaType;
use App\Rules\AttachmentName;
use App\Rules\FileExtension;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;

class Attachments extends ModalComponent
{
    use WithFileUploads;
    use AuthorizesRequests;

    protected $listeners = [
        'attachmentListChanged' => '$refresh',
        'modalClosed' => 'resetValues',
    ];

    /** @var \App\Models\Tenant\Concerns\HasAttachments */
    public $model = null;

    /** Files to be attach */
    public array $attach = [];

    /** Files to upload */
    public $upload;

    public $mediaComponentNames = ['upload'];

    public $attachToModel;

    public DocumentFolder | int $documentFolder;

    public $mediaTypesList;

    public $mediaFileType;

    public $internalAttachment = false;

    public $selectPage = false;

    public $selectAll = false;

    public $selected = [];

    public $active = true;

    public function mount(
        ?int $modelId = null,
        ?string $modelType = null,
        ?DocumentFolder $documentFolder = null,
        ?bool $internalAttachment = false,
        ?string $questionnaireSubmitted = null,
    ) {
        $this->documentFolder = $documentFolder;
        if (!$modelId) {
            return;
        }
        if ($internalAttachment) {
            $this->$internalAttachment = true;
        }

        $class = 'App\Models\Tenant\\' . ucfirst($modelType);
        $this->model = $class::find($modelId);
        $this->active = is_null($questionnaireSubmitted);
    }

    public function getMediasProperty()
    {
        return !$this->model ? collect() : $this->model->attachments()->get();
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public static function dispatchCloseEvent(): bool
    {
        return true;
    }

    /**
     * Destroy the session values on close modal
     */
    public static function destroyOnClose(): bool
    {
        (new self())->resetValues();
        return true;
    }

    /**
     * Clear the session values
     */
    public function resetValues()
    {
        session()->forget('success');
    }

    /**
     * Render the component
     */
    public function render()
    {
        $this->attachToModel = !$this->model ? auth()->user() : $this->model;
        $attachmentsUrl = route('tenant.attachments', ['ids' => $this->medias->pluck('id')->implode('-')], true);
        $this->upload = null;
        $this->attach = [];
        $this->mediaTypesList = MediaType::all();

        if ($this->selectAll) {
            $this->selected = $this->medias->pluck('id')->map(fn ($id) => (string) $id);
        }

        return view('livewire.tenant.modals.attachments', [
            'attachments' => $this->medias,
            'attachmentsUrl' => $attachmentsUrl,
            'isActive' => $this->active
        ]);
    }

    public function updatedUpload()
    {
        $this->validate([
            'upload.*' => [
                'file',
                'max:10000',
                'mimes:pdf,csv,xls,xlsx,jpg,jpeg,png',
                new FileExtension(),
                new AttachmentName(),
            ],
        ]);

        foreach ($this->upload as $file) {
            $libraryName = $this->documentFolder->id || $this->internalAttachment ?
                config('media-library.collection.internal') :
                config('media-library.collection.attachments');

            $uploadedMedia = auth()->user()->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->withCustomProperties(
                    [
                        'folder' => $this->documentFolder->id,
                        'created_by' => auth()->user()->id,
                    ]
                )
                ->toMediaCollection($libraryName, 'attachments');

            if ($this->model) {
                $this->attachToModel->attach($uploadedMedia->id);
            }
        }

        $this->upateAttachments(count($this->upload));
    }

    public function updateAttach()
    {
        $this->validate([
            'attach' => 'required|exists:media,id',
        ]);

        foreach ($this->attach as $media) {
            $this->attachToModel->attach($media);
        }
        session()->flash('success', __('The file was successfully attached.'));
        $this->upateAttachments(count($this->attach));
    }

    public function destroy($id)
    {
        $this->model->detach($id);
        $this->upateAttachments(-1);
    }

    protected function upateAttachments($number)
    {
        $this->emit('attachmentListChanged');
        $this->emit('documentFolderChanged');
        if (!$this->model) {
            session(['success' => __('The files were successfully uploaded.')]);

            $this->emit('attachmentsChanged');
        } else {
            if ($number < 0) {
                session(['success' => __('File deleted successfully.')]);
            } else {
                session(['success' => __('The files were successfully uploaded.')]);
            }
            $this->emit("attachmentsChanged{$this->model->id}", $number);
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function updatedSelectPage($value)
    {
        $this->selected = $value
            ? $this->medias->pluck('id')->map(fn ($id) => (string) $id)
            : [];
    }

    public function selectAll()
    {
        $this->selectAll = true;
    }

    public function bulkFileTypeChnage(MediaType $mediaType)
    {
        if (count($this->selected) > 0) {
            $mediaItems = Media::whereIn('id', $this->selected)->get();
            foreach ($mediaItems as $mediaItem) {
                $mediaItem->setCustomProperty('file_type', $mediaType->id);
                $mediaItem->save();
            }
        }
        $this->selected = [];
        session()->flash('success', __('File type updated successfully.'));
        $this->emit('attachmentListChanged');
    }

    public function deleteMultipleMedia()
    {
        if (count($this->selected) > 0) {
            $mediaItems = Media::whereIn('id', $this->selected)->get();
            foreach ($mediaItems as $mediaItem) {
                $this->model->detach($mediaItem->id);
            }
            session()->flash('success', __('File deleted successfully.'));
            $this->upateAttachments('-' . count($this->selected));
        }
    }

    public function updateMediaFileType($mediaFileType, $mediaId)
    {
        $mediaItem = Media::find($mediaId);
        if ($mediaFileType) {
            $mediaItem->setCustomProperty('file_type', $mediaFileType);
        } else {
            $mediaItem->forgetCustomProperty('file_type');
        }
        $mediaItem->save();
        session()->flash('success', __('File type updated successfully.'));
        $this->emit('attachmentListChanged');
    }
}
