<?php

namespace App\Listeners;

use App\Events\CreatedCompany;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunCustomerCreationDefaultActions implements ShouldQueue
{
    /**
     * If the company is a supplier, create the default customer questionnaires
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreatedCompany $event)
    {
        if ($event->company->is_not_client || ! $createQuestionnaires = tenant()->default_customer_questionnaires) {
            return;
        }

        foreach ($createQuestionnaires as $questionnaire) {
            $event->company->questionnaires()->create([
                'questionnaire_type_id' => $questionnaire['questionnaire_type_id'] ?? 2,
                'created_by_user_id' => $event->user->id,
                'countries' => [$event->company->country],
                'from' => '2021-01-01',
                'to' => '2021-01-01',
            ]);
        }
    }
}
