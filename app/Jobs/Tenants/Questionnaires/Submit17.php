<?php

namespace App\Jobs\Tenants\Questionnaires;

use App\Models\Tenant\BusinessSectorType;
use App\Models\Tenant\Data;
use App\Models\Tenant\Questionnaire;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// Info » https://cmorept1.sharepoint.com/:x:/s/C-MoreTech/EVnWHvwCDJdGnoyF1QFqscsB0KaXj7-GaXLaaW8pUVuqRg?e=iQa9fr

/**
 * E-mail: Luís <Luis@cmore.pt> Sent: 6 de dezembro de 2023 09:39
 *
 * Ter mais de 250 trabalhadores (2-1 e 2-2)
 * Ter um balanço superior a 20M€ (3-1)
 * Ter um volume de negócios superior a 40M€ (4-1)
 *
 * Criação do questionário de taxonomia
 *
 *    Apenas cria completo ou simples, caso seja "empresa não financeira"
 *
 *       Cria do tipo Completo
 *          Se a validação de, pelo menos, duas das condições for verdadeira
 *       Cria do tipo Simples
 *          Se não for para criar o completo
 *
 *
 * Criação do questionário geral
 *
 *    Cria do tipo Completo
 *       Se a validação de, pelo menos, duas das condições for verdadeira
 *    Cria do tipo Simples
 *       Se não for para criar o completo
 *
 *
 * Criação do questionário de riscos físicos
 *
 *    Cria consoante a lista de NACEs enviada previamente
 *
 *
 * Calculadora de emissões
 *  
 *    Cria sempre
 */

class Submit17 implements ShouldQueue
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
        // $company->dyn_is_issuer_of_securities
        // $company->cus_categories
        // $company->headquarters_eu
        // $company->business_sector->code
        $company = $this->questionnaire->company;

        // All values of the questionnaire (only 4 questions)
        $data = Data::where('questionnaire_id', $this->questionnaire->id)
            ->get()
            ->pluck('value', 'indicator_id');

        // Indicator id 513
        $employees = $data->get('513');

        // Indicator id 168
        $revenue = $data->get('168');

        // Indicator id 6584
        $balance = $data->get('6584');

        // Questionnaires
        // CO2 «» Emission Calculatora » Generate for everyone

        // 10 » Taxonomia Simples » minimum_safeguards: simple
        // 10 » Taxonomia » minimum_safeguardss: complete
        // 19 » ESG Complete/CSRD
        // 18 » ESG Simple
        // 12 » Physical Risks
        // 15 » CO2

        // Main conditions | Sum one point for each one that is true.
        // Beucase we need to know if is true to at least 2 of them
        // With that, we just need to sum
        $conditions[] = $employees > 250
            ? 1
            : 0;
        $conditions[] = $balance > 20000000
            ? 1
            : 0;
        $conditions[] = $revenue > 40000000
            ? 1
            : 0;

        $trueConditions = array_sum($conditions);

        $questionnairesToCreate = [];

        // Taxonomy
        if ($company->cus_categories === 'non-financial-companies') {
            $questionnairesToCreate[] = $trueConditions >= 2
                ? 10
                : [
                    'id' => 10,
                    'data' => [
                        'minimum_safeguards' => [
                            'type' => 'simple'
                        ],
                    ]
                ];
        }

        // ESG
        $questionnairesToCreate[] = $trueConditions >= 2
            ? 19
            : 18;

        // CO2 Calculator
        $questionnairesToCreate[] = 15;

        // Physical Risks
        // If company has one of these activities: A, B, C, D, E, F, G, H, I, J, K, L
        // Create a Physical Risks Questionnaire
        $sector = BusinessSectorType::find(3);

        if ($sector) {
            $allActivities = $sector->businessSectors
            ->whereBetween('id', [248, 801])
            ->pluck('id', 'id')
            ->toArray();
        }


        if (array_intersect_key($company->businessSectorsAllArray(), $allActivities)) {
            $questionnairesToCreate[] = 12;
        }

        // Create all the questionnaires
        foreach ($questionnairesToCreate as $questionnaireToCreate) {
            $id = is_array($questionnaireToCreate)
                ? $questionnaireToCreate['id']
                : $questionnaireToCreate;

            $createdQuestionnaire = Questionnaire::create([
                'questionnaire_type_id' => $id,
                'created_by_user_id' => $this->questionnaire->created_by_user_id,
                'parent_id' => $this->questionnaire->id,
                'company_id' => $this->questionnaire->company_id,
                'countries' => [$this->questionnaire->company->country],
                'from' => $this->questionnaire->from, // TODO::Fix this
                'to' => $this->questionnaire->to, // TODO::Fix this
                'data' => $questionnaireToCreate['data'] ?? null,
            ]);

            $createdQuestionnaire->assignUsers([], $this->questionnaire->createdBy);
        }
    }
}
