<?php

namespace App\Jobs;

use App\Http\Middleware\LoggingContextMiddleware;
use App\Models\Tenant;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SeedTenantDatabaseAllJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Tenant */
    protected $tenant;

    /** @var bool */
    protected $all;

    /** @var string */
    protected string $seeder;

    /** @var \DateTimeInterface */
    protected $startTime;


    /**
     * Indicate if the job should be marked as failed on timeout.
     * @var bool
     */
    public bool $failOnTimeout = true;

    /**
     * The number of seconds the job can run before timing out.
     * @var int
     */
    public int $timeout = 3000;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 5;

    /**
     * The number of times the job may be attempted.
     * @var int
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant, $seeder, $all = false)
    {
        $this->onQueue('tenant_seeders');
        $this->tenant = $tenant;
        $this->seeder = $seeder;
        $this->all = $all;
        $this->startTime = now();
        $this->delay = now()->addSeconds(3);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->tenant->run(function () {
            $seeder = new  $this->seeder();
            $seeder->run($this->all);
        });
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $th): void
    {
        $this->tenant->run(function () use ($th) {
            $message = 'Failed job ' . $this->seeder;
            $message .= ' | All:' . ($this->all ? 'all' : 'update');
            $message .= ' after ' . now()->diffInSeconds($this->startTime) . ' seconds';
            $message .= ' | ' . $th->getMessage();
            activity()
                ->event('seeding')
                ->log($message);
        });
    }
}
