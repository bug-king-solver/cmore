<?php

namespace App\Http\Livewire\Api\Tokens;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    private $tokens;

    protected $listeners = [
        'apiTokenChanged' => '$refresh',
        'showNotification' => 'showNotification',
    ];

    public function mount()
    {
        $this->addBreadcrumb(__('API'));
    }

    /**
     * Show notification modal
     */
    public function showNotification($data)
    {
        $this->emit('openModal', 'modals.notification', ['data' => $data]);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $this->tokens = auth()->user()
            ->tokens()
            ->paginate(config('app.paginate.per_page'));

        return view('livewire.tenant.api.tokens.index');
    }
}
