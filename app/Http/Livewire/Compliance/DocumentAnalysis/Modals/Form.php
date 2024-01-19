<?php

namespace App\Http\Livewire\Compliance\DocumentAnalysis\Modals;

use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use App\Models\Tenant\Media;
use App\Models\Tenant\MediaType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;
    use WithFileUploads;

    public Result | int $result;

    public $file;

    public $attach;

    public $file_type;

    protected function rules()
    {
        return [
            'file' => 'required_without:attach',
            'attach' => 'required_without:file',
            'file_type' => 'required',
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function mount(?Result $result)
    {
        $this->result = $result;

        $this->authorize(! $this->result->exists ? 'result.create' : "result.update.{$this->result->id}");
    }

    public function render(): View
    {
        $mediaTypesList = MediaType::all()->pluck('title', 'id');

        return view(
            'livewire.tenant.compliance.document_analysis.modal.form',
            [
                'mediaTypesList' => $mediaTypesList,
            ]
        );
    }

    public function save()
    {
        $this->authorize(! $this->result->exists ? 'result.create' : "result.update.{$this->result->id}");

        $data = $this->validate();

        $insData['document_analysis_type_id'] = $data['file_type'];
        $resultObj = Result::create($insData);
        if ($this->file) {
            $resultObj->addMedia($this->file->getRealPath())
            ->usingName($this->file->getClientOriginalName())
            ->withCustomProperties(
                [
                    'folder' => null,
                    'created_by' => auth()->user()->id,
                    'file_type' => $data['file_type'],
                ]
            )
            ->toMediaCollection('library', 'attachments');
        } elseif ($this->attach) {
            $mediaItems = Media::find($this->attach);
            $copiedMedia = $mediaItems->copy($resultObj, 'library', 'attachments');
            $copiedMedia->setCustomProperty('folder', null);
            $copiedMedia->setCustomProperty('created_by', auth()->user()->id);
            $copiedMedia->setCustomProperty('copied_media_id', $mediaItems->id);
            $copiedMedia->setCustomProperty('file_type', $data['file_type']);
            $copiedMedia->save();
        }

        $this->emit('resultAltered');
        $this->closeModal();
    }
}
