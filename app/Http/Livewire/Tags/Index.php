<?php

namespace App\Http\Livewire\Tags;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\Tag;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    protected $listeners = [
        'tagsSaved' => '$refresh',
    ];

    public function mount()
    {
        parent::initFilters($model = Tag::class);
        $this->model = new Tag();

        $this->addBreadcrumb(__('Tags'));
    }

    public function render(): View
    {
        $tags = $this->search($this->model->list())
            ->withTrashed()
            ->paginate($this->selectedItemsPerPage)
            ->withQueryString();

        return view(
            'livewire.tenant.tags.index',
            [
                'tags' => $tags,
            ]
        );
    }
}
