<?php

namespace App\Nova\Central\Actions\Tenant;

use App\Jobs\SeedTenantDatabaseAllJob;
use App\Models\Tenant;
use Database\Seeders\Tenant\BankEcoSystemSeeder;
use Database\Seeders\Tenant\BusinessActivitiesDescriptionSeeder;
use Database\Seeders\Tenant\BusinessActivitiesSeeder;
use Database\Seeders\Tenant\BusinessSectorSeeder;
use Database\Seeders\Tenant\BusinessSectorTypeSeeder;
use Database\Seeders\Tenant\CategoryQuestionnaireTypeSeeder;
use Database\Seeders\Tenant\CategorySeeder;
use Database\Seeders\Tenant\IndicatorSeeder;
use Database\Seeders\Tenant\InitiativeSeeder;
use Database\Seeders\Tenant\InternalTagSeeder;
use Database\Seeders\Tenant\PermissionSeeder;
use Database\Seeders\Tenant\ProductsSeeder;
use Database\Seeders\Tenant\QuestionOptionsSeeder;
use Database\Seeders\Tenant\QuestionOptions\MatrixSeeder;
use Database\Seeders\Tenant\QuestionOptions\SimpleSeeder;
use Database\Seeders\Tenant\QuestionSeeder;
use Database\Seeders\Tenant\QuestionnaireTypeSeeder;
use Database\Seeders\Tenant\SdgSeeder;
use Database\Seeders\Tenant\SourceSeeder;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Throwable;

class SeedTenantDatabaseAll extends Action
{
    use InteractsWithQueue;
    use Queueable;

    protected string $action;
    protected string $seederName;


    /**
     * Action constructor.
     * @param string $action
     * @param string|null $seederName
     */
    public function __construct(string $action, string $seederName = '')
    {
        $this->action = $action;
        $this->name = "Seed: " . strtoupper($action) . " Portal data";
        $this->seederName = $seederName;

        if ($seederName) {
            $this->name = "Seed: " . strtoupper($seederName) . " with " . strtoupper($action) . " data";
        }
    }

    /**
     * Get the uri  of the action.
     */
    public function uriKey()
    {
        return "seed-tenant-database-{$this->action}-{$this->seederName}";
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $all = $this->action === 'all';


        $seeders = [
            InternalTagSeeder::class,
            QuestionnaireTypeSeeder::class,
            CategorySeeder::class,
            ProductsSeeder::class,
            BusinessSectorTypeSeeder::class,
            BusinessSectorSeeder::class,
            BankEcoSystemSeeder::class,
            IndicatorSeeder::class,
            InitiativeSeeder::class,
            SourceSeeder::class,
            SdgSeeder::class,
            QuestionSeeder::class,
            SimpleSeeder::class,
            MatrixSeeder::class,
            QuestionOptionsSeeder::class,
            BusinessActivitiesSeeder::class,
            BusinessActivitiesDescriptionSeeder::class,
        ];

        switch ($this->seederName) {
            case 'business-sectors':
                $seeders = [
                    BusinessSectorTypeSeeder::class,
                    BusinessSectorSeeder::class,
                ];
                break;
            case 'bankecosystem':
                $seeders = [
                    BankEcoSystemSeeder::class,
                ];
                break;
            case 'indicators':
                $seeders = [
                    CategorySeeder::class,
                    IndicatorSeeder::class,
                ];
                break;
            case 'initiatives':
                $seeders = [
                    CategorySeeder::class,
                    InitiativeSeeder::class,
                ];
                break;
            case 'internal-tags':
                $seeders = [
                    InternalTagSeeder::class,
                ];
                break;
            case 'products':
                $seeders = [
                    CategorySeeder::class,
                    ProductsSeeder::class,
                ];
                break;
            case 'sdgs':
                $seeders = [
                    CategorySeeder::class,
                    SdgSeeder::class,
                ];
                break;
            case 'sources':
                $seeders = [
                    SourceSeeder::class,
                ];
                break;
            case 'questionnaires':
                $seeders = [
                    QuestionnaireTypeSeeder::class,
                    CategorySeeder::class,
                    InitiativeSeeder::class,
                    IndicatorSeeder::class,
                    SdgSeeder::class,
                    QuestionSeeder::class,
                    SimpleSeeder::class,
                    MatrixSeeder::class,
                    QuestionOptionsSeeder::class,
                ];
                break;
            case 'taxonomy':
                $seeders = [
                    BusinessActivitiesSeeder::class,
                    BusinessActivitiesDescriptionSeeder::class,
                ];
                break;
            case 'permissions':
                $seeders = [
                    PermissionSeeder::class,
                ];
                break;
        }


        /** @var Tenant $tenant */
        foreach ($models as $tenant) {
            $batch = [];

            foreach ($seeders as $i => $seeder) {
                $batch[$i] = [new SeedTenantDatabaseAllJob($tenant, $seeder, $all)];
            }

            $tenant->run(function () use ($batch) {
                activity()->event("batch.start")->log("Starting batch 💣 \r\n");
            });

            $batchable = Bus::batch($batch)
                ->name("Tenant: {$tenant->id} - Seeder all")
                ->then(function (Batch $batch) use ($tenant) {
                    $tenant->run(function () use ($batch) {
                        $message = "All jobs run {$batch->id}";
                        $message .= " with {$batch->totalJobs} jobs, where {$batch->failedJobs} failed.";
                        activity()->event("batch.completed")->log($message);
                    });
                })
                ->finally(function (Batch $batch) use ($tenant) {
                    $tenant->run(function () use ($batch) {
                        $message = "Finished batch {$batch->id}";
                        $message .= " with {$batch->totalJobs} jobs, where {$batch->failedJobs} failed.";
                        activity()->event("batch.finish")->log($message);
                    });
                })
                ->catch(function (Batch $batch, Throwable $e) use ($tenant) {
                    $tenant->run(function () use ($batch, $e) {
                        $message = "Failed batch {$batch->id} 💥";
                        $message .= " with {$batch->totalJobs} jobs, where {$batch->failedJobs} failed.";
                        activity()->event("batch.error")->log($message);
                        activity()->event("batch.error")->log($e->getMessage());
                    });
                })->onQueue('tenant_seeders');

            $batchable->dispatch();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request = null)
    {
        return [];
    }
}
