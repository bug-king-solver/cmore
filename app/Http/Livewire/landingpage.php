<?php

namespace App\Http\Livewire;

use App\Models\Tenant\Media;
use App\Models\Tenant\MediaType;
use App\Rules\AttachmentName;
use App\Rules\FileExtension;
use Livewire\Component;
use Livewire\WithFileUploads;

class Landingpage extends Component
{
    use WithFileUploads;

    public $section_title;

    public $main_title;

    public $description;

    public $manifesto;

    public $upload;

    public $images;

    protected $listeners = [
        'attachmentsChanged' => '$refresh',
    ];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'section_title' => ['required', 'string', 'max:255'],
            'main_title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string']
        ];
    }

    /**
     * Mount data before load
     */
    public function mount()
    {
        $this->section_title = tenant()->section_title;
        $this->main_title = tenant()->main_title;
        $this->description = tenant()->description;
        $this->manifesto = tenant()->manifesto;
    }

    /**
     * Rander view
     * @return view
     */
    public function render()
    {
        $this->images = Media::where(
            'collection_name',
            config('media-library.collection.attachments')
        )->whereJsonContains(
            'custom_properties->is_home',
            true
        )->get();

        return view('livewire.tenant.landingpage',[
            'images' => $this->images
        ]);
    }

    /**
     * Update data
     */
    public function update()
    {
        $this->validate();

        tenant()->update([
            'manifesto' => $this->manifesto,
            'section_title' => $this->section_title,
            'main_title' => $this->main_title,
            'description' => $this->description
        ]);

        return redirect()->route('tenant.settings.application');
    }

    /**
     * Update upload
     */
    public function updatedUpload()
    {
        $this->validate([
            'upload.*' => [
                'file',
                'max:10000',
                'mimes:pdf,csv,txt,xls,xlsx,jpg,jpeg,png',
                new FileExtension,
                new AttachmentName(),
            ],
        ]);

        foreach ($this->upload as $file) {
            $libraryName = config('media-library.collection.attachments');

            auth()->user()->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->withCustomProperties(
                    [
                        'folder' => null,
                        'is_home' => true,
                        'created_by' => auth()->user()->id,
                    ]
                )
                ->toMediaCollection($libraryName, 'attachments');
        }

        $this->emit('attachmentsChanged');
    }
}
