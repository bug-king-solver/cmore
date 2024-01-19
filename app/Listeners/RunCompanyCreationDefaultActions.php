<?php

namespace App\Listeners;

use App\Events\CreatedCompany;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunCompanyCreationDefaultActions implements ShouldQueue
{
    /**
     * If a company is created, create the default customer questionnaires
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreatedCompany $event)
    {
        if (! $createQuestionnaires = tenant()->default_company_questionnaires) {
            return;
        }

        foreach ($createQuestionnaires as $questionnaire) {
            $createdQuestionnaire = $event->company->questionnaires()->create([
                'questionnaire_type_id' => $questionnaire['questionnaire_type_id'],
                'created_by_user_id' => $event->user->id,
                'countries' => [$event->company->country],
                'from' => '2022-01-01',
                'to' => '2022-12-31',
            ]);

            $createdQuestionnaire->assignUsers([], $createdQuestionnaire->createdBy);
        }
    }
}
