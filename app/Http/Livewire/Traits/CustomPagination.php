<?php

namespace App\Http\Livewire\Traits;

use Livewire\WithPagination;

trait CustomPagination
{
    use WithPagination;

    public $itemsPerPage;
    public $selectedItemsPerPage; // Selected by user in the UI

    /**
     * Mount the component.
     */
    public function mountCustomPagination(): void
    {
        $this->initPaginate(config('app.paginate.per_page'));
    }

    /**
     * Initialize the pagination.
     */
    public function initPaginate(int $value): void
    {
        $this->itemsPerPage = $value;
        $this->selectedItemsPerPage = $value;
    }

    /**
     * Get the pagination view.
     */
    public function paginationView(): string
    {
        return 'vendor.livewire.tailwind';
    }
}
