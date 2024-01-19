<?php

namespace App\Events;

use App\Models\Tenant\Company;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class CreatedCompany
{
    use SerializesModels;

    public Authenticatable $user;

    public Company $company;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  Company company
     * @return void
     */
    public function __construct($user, $company)
    {
        $this->user = $user;
        $this->company = $company;
    }
}
