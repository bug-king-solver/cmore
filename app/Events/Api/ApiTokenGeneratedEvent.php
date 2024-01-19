<?php

namespace App\Events\Api;

use App\Models\Tenant\Api\ApiTokens;
use Illuminate\Queue\SerializesModels;

class ApiTokenGeneratedEvent
{
    use SerializesModels;

    public $token;

    public ApiTokens $apiToken;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ApiTokens $apiToken, string $token)
    {
        $this->apiToken = $apiToken;
        $this->token = $token;
    }
}
