<?php

namespace App\Http\Livewire\Traits;

trait HasProductItem
{
    public $product;
    public $resource;

    /**
     * Mount the trait
     * @return void
     */
    public function mountHasProductItem()
    {
        $this->product = $this->resource->getProduct() ?? null;
    }

    public function hydrateHasProductItem(): void
    {
    }
}
