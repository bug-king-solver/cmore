<?php

namespace App\Models\Tenant\Concerns\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface Productable
{
    /**
     * Get the information from the catalog
     */
    public function getProduct();

    /**
     * Check if the model if payable.
     * 
     * Based on it, we will show the price on the form creation and add a transaction.
     */
    public function isPayable();
}
