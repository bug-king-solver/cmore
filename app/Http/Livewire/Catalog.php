<?php

namespace App\Http\Livewire;

use App\Models\Tenant\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Catalog extends Component
{
    use AuthorizesRequests;

    public $categories;

    public function mount()
    {
        $this->categories = Category::hasProducts()->get();
        $this->authorize('catalog.view');
    }

    /**
     * Render view
     */
    public function render()
    {
        return tenantview('livewire.tenant.catalog.index');
    }
}
