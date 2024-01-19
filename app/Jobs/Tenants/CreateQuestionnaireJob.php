<?php

namespace App\Jobs\Tenants;

use App\Models\Tenant;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CreateQuestionnaireJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Questionnaire */
    protected Questionnaire $questionnaire;

    protected $startTime;
    /**
     * Indicate if the job should be marked as failed on timeout.
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * The number of seconds the job can run before timing out.
     * @var int
     */
    public $timeout = 900;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 5;

    /**
     * The number of times the job may be attempted.
     * @var int
     */
    public $tries = 3;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Questionnaire $questionnaire)
    {
        $this->onQueue('questionnaires');

        $this->questionnaire = $questionnaire;
        $this->delay = now()->addSeconds(5);
        $this->startTime = now();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        tenant()->run(function () {
            $message = 'Start create questionnaire: ' . $this->questionnaire->type->name ?? null;
            activity()
                ->event('create.questionnaire')
                ->log($message);
        });

        tenant()->run(function () {
            $questionnaire = $this->questionnaire;

            $questions = Questionnaire::copyQuestions($questionnaire);

            $questionsCategoriesIds = $questions->pluck('category_id')->unique()->values()->toArray();

            $categories = Questionnaire::copyCategories(
                $questionnaire->questionnaire_type_id,
                $questionnaire->company->business_sector_id
            )->filter(
                // Remove sub-categories with no questions
                fn ($category) => !$category->parent_id || in_array($category->id, $questionsCategoriesIds, false)
            );

            $mainCategories = $categories->pluck('parent_id')->unique()->values()->toArray();

            $categories = $categories->filter(
                fn ($category) => $category->parent_id
                    || in_array($category->id, $mainCategories, false)
                    || in_array($category->id, $questionsCategoriesIds, false)
            );

            $answers = $questions->map(function ($question) use ($questionnaire) {
                return [
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $question['id'],
                ];
            });

            Answer::insert($answers->toArray());
            $questionnaire->categories = $categories;
            $questionnaire->questions = $questions;
            $questionnaire->is_ready = true;
            $questionnaire->ignore_has_user_validation = true;
            $questionnaire->save();
        });

        tenant()->run(function () {
            $message = 'Finish create questionnaire: ' . $this->questionnaire->type->name ?? null;
            $message .= ' | after ' . now()->diffInSeconds($this->startTime) . ' seconds';
            activity()
                ->event('create.questionnaire')
                ->log($message);
        });
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $th): void
    {
        tenant()->run(function () use ($th) {
            $message = 'Failed create questionnaire ' . $this->questionnaire->type->name ?? null;
            $message .= ' after ' . now()->diffInSeconds($this->startTime) . ' seconds';
            $message .= ' | ' . $th->getMessage();

            activity()
                ->event('create.questionnaire')
                ->log($message);
        });
    }
}
