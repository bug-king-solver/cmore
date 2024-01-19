<?php

namespace App\Jobs\Tenants\Questionnaires;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Questionnaire;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Submit23 implements ShouldQueue
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
        $questionnairesToCreate = [12]; // TODO::Need to add biodiversity questinnaire type once it created

        $answer = Answer::where([
            'questionnaire_id' => $this->questionnaire->id,
            'question_id' => 35703
        ])->first();

        if ($answer && $answer->value == 'no') {
            $questionnairesToCreate[] = 15;
        }

        // Create all the questionnaires
        foreach ($questionnairesToCreate as $questionnaireToCreate) {
            $createdQuestionnaire = Questionnaire::create([
                'questionnaire_type_id' => $questionnaireToCreate,
                'created_by_user_id' => $this->questionnaire->created_by_user_id,
                'parent_id' => $this->questionnaire->id,
                'company_id' => $this->questionnaire->company_id,
                'countries' => [$this->questionnaire->company->country],
                'from' => $this->questionnaire->from->format('Y-m-d'),
                'to' => $this->questionnaire->to->format('Y-m-d'),
                'data' => null,
                'reporting_period_id' => $this->questionnaire->reporting_period_id
            ]);

            $createdQuestionnaire->assignUsers([], $this->questionnaire->createdBy);
        }
    }
}
