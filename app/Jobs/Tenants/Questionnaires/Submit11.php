<?php

namespace App\Jobs\Tenants\Questionnaires;

use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Tenant\InternalTag;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Submit11 implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Questionnaire */
    protected Questionnaire $questionnaire;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Questionnaire $questionnaire)
    {
        $this->onQueue('questionnaires');
        $this->questionnaire = $questionnaire;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->questionnaire->wait_result_job = true;
        $this->questionnaire->result_at = null;
        $this->questionnaire->save();

        $chartData = [
            'subcategories' => [
                'principled-business',
                'strengthening-society',
                'leadership-commitment',
                'reporting-progress',
                'local-action',
            ]
        ];

        $data = [];

        $tags = $chartData['subcategories'];
        $this->questionnaire->allQuestionsForMaturity = $this->questionnaire->questions();
        $this->questionnaire->weightableQuestionsForFilter = $this->questionnaire->weightableQuestions();

        $questionnaire = $this->questionnaire;
        $questionnaire->dashboardData = [];
        $questionnaire->save();

        foreach ($tags as $internalTags) {
            $filterTags = [];
            $filterTags[] = $internalTags;

            $data['simple'][0][$internalTags] = $this->questionnaire->calculatePontuation(
                function ($q) use ($filterTags) {
                    $questionHasAllTags = $q->internalTags->unique('slug')->toArray();
                    $mustCount = array_intersect($filterTags, array_column($questionHasAllTags, 'slug'));
                    return count($mustCount) == count($filterTags);
                }
            );
        }

        $questionnaire->dashboardData = $data;
        $questionnaire->result_at = now();
        unset($questionnaire->wait_result_job);
        $questionnaire->save();
    }

    /**
     * The job failed to process.
     * @param Throwable $exception
     */
    public function failed(Throwable $exception): void
    {
        $this->questionnaire->review();
    }
}
