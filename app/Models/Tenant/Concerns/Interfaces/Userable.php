<?php

namespace App\Models\Tenant\Concerns\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface Userable
{
    /**
     * Define the message that will be deliver to who is assgined
     */
    public function assignedUserMessage(Model $assigner);

    /**
     * Create the update user message
     */
    public function updatedUserMessage();
}
