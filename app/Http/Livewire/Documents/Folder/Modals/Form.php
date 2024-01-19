<?php

namespace App\Http\Livewire\Documents\Folder\Modals;

use App\Enums\Documents\FolderVisibility;
use App\Models\Tenant\DocumentFolder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;

    public DocumentFolder | int $documentFolder;

    public $name;

    public $parent_id;

    public $parent;

    public $visibility;

    public $visibilityOptions;

    protected function rules()
    {
        return [
            'name' => 'required',
            'visibility' => 'required',
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function mount(?DocumentFolder $documentFolder = null, ?int $parent = null)
    {
        $this->documentFolder = $documentFolder;
        $this->parent = $parent ?? 0;

        $this->visibilityOptions = FolderVisibility::values();

        $this->authorize(
            !$this->documentFolder->exists ?
                'library.create' :
                "library.update.{$this->documentFolder->id}"
        );

        if ($this->documentFolder->exists) {
            $this->name = $this->documentFolder->name;
            $this->parent_id = $this->documentFolder->parent_id;
            $this->visibility = $this->documentFolder->visibility;
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.documents.folder.form');
    }

    public function save()
    {
        $this->authorize(
            !$this->documentFolder->exists ?
                'library.create' :
                "library.update.{$this->documentFolder->id}"
        );
        $documentFolder = $this->validate();
        $documentFolder['slug'] = str_slug($documentFolder['name']);
        $documentFolder['visibility'] = $documentFolder['visibility'];

        if (!$this->documentFolder->exists || $this->parent > 0) {
            if ($this->parent > 0) {
                $documentFolder['parent_id'] = $this->parent;
            }
            DocumentFolder::create($documentFolder);
        } else {
            $this->documentFolder->update($documentFolder);
        }
        $this->closeModal();
        $this->emit('documentFolderChanged');
    }
}
