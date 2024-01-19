<?php

namespace App\Http\Livewire\Tags\Modals;

use App\Http\Livewire\Traits\HasCustomColumns;
use App\Models\Tenant\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;
    use HasCustomColumns;
    use WithPagination;
    use WithFileUploads;

    protected $feature = 'tags';

    public Tag | int $tag;

    public $name;

    public $color;

    protected function rules()
    {
        return $this->mergeCustomRules([
            'name' => ['required', 'string', 'max:255', 'unique:tags'],
            'color' => ['required', 'string', 'max:255'],
        ]);
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function mount(?Tag $tag)
    {
        $this->tag = $tag;

        $this->authorize(! $this->tag->exists ? 'tags.create' : "tags.update.{$this->tag->id}");

        if ($this->tag->exists) {
            $this->name = $this->tag->name;
            $this->color = $this->tag->color;
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.tags.form');
    }

    public function save()
    {
        $this->authorize(! $this->tag->exists ? 'tags.create' : "tags.update.{$this->tag->id}");
        $data = $this->validate();

        if (! $this->tag->exists) {
            $this->tag = Tag::create($data);
        } else {
            $this->tag->update($data);
        }

        $this->emit('tagsSaved');
        $this->closeModal();
    }
}
