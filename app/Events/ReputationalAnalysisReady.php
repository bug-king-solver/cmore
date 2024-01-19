<?php

namespace App\Events;

use App\Models\Tenant\Compliance\Reputational\Analysis;
use Illuminate\Queue\SerializesModels;

class ReputationalAnalysisReady
{
    use SerializesModels;

    public Analysis $analysis;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Analysis $analysis)
    {
        $this->analysis = $analysis;
    }
}
