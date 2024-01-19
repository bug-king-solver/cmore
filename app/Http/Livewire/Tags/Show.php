<?php

namespace App\Http\Livewire\Tags;

use App\Models\Tenant\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public int|Tag $tag;

    public function mount(Tag $tag)
    {
        $this->authorize("tags.view.{$this->tag->id}");
        $this->tag = $tag;
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.tags.show',
            [
                'tag' => $this->tag,
            ]
        );
    }
}
