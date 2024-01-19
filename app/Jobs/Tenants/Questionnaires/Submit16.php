<?php

namespace App\Jobs\Tenants\Questionnaires;

use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Tenant\InternalTag;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

class Submit16 implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Questionnaire */
    protected Questionnaire $questionnaire;

    public $timeout = 0;

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
            'ambiente' => [
                'gestao-dos-criterios-esg' => [
                    'analysisCriteria' => [
                        'c-accoes',
                        'c-metricas-de-desempenho',
                        'c-objetivos-e-metas',
                        'c-planos-e-politicas',
                        'c-recursos-para-implementar-accoes',
                    ],
                    'subCategory' => [
                        'biodiversidade-e-ecossistemas',
                        'alteracoes-climaticas',
                        'poluicao',
                        'utilizacao-de-recursos-e-economia-circular',
                        'agua-e-recursos-marinhos'
                    ]
                ],
                'identificacao-e-avaliacao' => [
                    'analysisCriteria' => [
                        'a-impactos-no-es',
                        'b-impactos-na-empresa',
                        'b-riscos-e-oportunidades',
                    ],
                    'subCategory' => [
                        'biodiversidade-e-ecossistemas',
                        'alteracoes-climaticas',
                        'poluicao',
                        'utilizacao-de-recursos-e-economia-circular',
                        'agua-e-recursos-marinhos'
                    ]
                ],
                'integracao-na-estrategia' => [
                    'analysisCriteria' => [
                        'c-alinhamento-de-objectivos-e-metas-com-o-modelo-de-negocio',
                        'c-integracao-na-estrategia-do-negocio',
                    ],
                    'subCategory' => [
                        'biodiversidade-e-ecossistemas',
                        'alteracoes-climaticas',
                        'poluicao',
                        'utilizacao-de-recursos-e-economia-circular',
                        'agua-e-recursos-marinhos'
                    ]
                ],
                'resiliencia' => [
                    'analysisCriteria' => [
                        'c-analise-de-cenarios',
                        'c-envolvimento-do-mais-alto-orgao-de-governanca',
                        'c-resposta-adaptacao-da-estrategia-aos-impactos-dos-riscos-e-oportunidades',
                    ],
                    'subCategory' => [
                        'biodiversidade-e-ecossistemas',
                        'alteracoes-climaticas',
                        'poluicao',
                        'utilizacao-de-recursos-e-economia-circular',
                        'agua-e-recursos-marinhos'
                    ]
                ]
            ],
            'social' => [
                'gestao-dos-criterios-esg' => [
                    'analysisCriteria' => [
                        'c-accoes',
                        'c-metricas-de-desempenho',
                        'c-objetivos-e-metas',
                        'c-planos-e-politicas',
                        'c-recursos-para-implementar-accoes',
                    ],
                    'subCategory' => [
                        'comunidades-afetadas',
                        'consumidores-e-utilizadores-finais',
                        'trabalhadores-da-organizacao',
                        'trabalhadores-da-cadeia-de-valor',
                    ]
                ],
                'identificacao-e-avaliacao' => [
                    'analysisCriteria' => [
                        'a-impactos-no-es',
                        'b-impactos-na-empresa',
                        'b-riscos-e-oportunidades',
                    ],
                    'subCategory' => [
                        'comunidades-afetadas',
                        'consumidores-e-utilizadores-finais',
                        'trabalhadores-da-organizacao',
                        'trabalhadores-da-cadeia-de-valor',
                    ]
                ],
                'integracao-na-estrategia' => [
                    'analysisCriteria' => [
                        'c-alinhamento-de-objectivos-e-metas-com-o-modelo-de-negocio',
                        'c-integracao-na-estrategia-do-negocio',
                    ],
                    'subCategory' => [
                        'comunidades-afetadas',
                        'consumidores-e-utilizadores-finais',
                        'trabalhadores-da-organizacao',
                        'trabalhadores-da-cadeia-de-valor',
                    ]
                ],
                'resiliencia' => [
                    'analysisCriteria' => [
                        'c-envolvimento-do-mais-alto-orgao-de-governanca',
                    ],
                    'subCategory' => [
                        'comunidades-afetadas',
                        'consumidores-e-utilizadores-finais',
                        'trabalhadores-da-organizacao',
                        'trabalhadores-da-cadeia-de-valor',
                    ]
                ]
            ],
            'governanca' => [
                'gestao-dos-criterios-esg' => [
                    'analysisCriteria' => [
                        'c-accoes',
                        'c-metricas-de-desempenho',
                        'c-objetivos-e-metas',
                        'c-planos-e-politicas',
                        'c-recursos-para-implementar-accoes',
                    ],
                    'subCategory' => [
                        'prevencao-e-detecao-de-corrupcao-e-suborno',
                        'principais-politicas-de-conduta-e-cultura-corporativa',
                        'relacao-com-fornecedores-e-praticas-de-pagamento',
                        'gestao-de-riscos',
                    ]
                ],
                'identificacao-e-avaliacao' => [
                    'analysisCriteria' => [
                        'a-impactos-no-es',
                        'b-impactos-na-empresa',
                        'b-riscos-e-oportunidades',
                    ],
                    'subCategory' => [
                        'prevencao-e-detecao-de-corrupcao-e-suborno',
                        'principais-politicas-de-conduta-e-cultura-corporativa',
                        'relacao-com-fornecedores-e-praticas-de-pagamento',
                        'gestao-de-riscos',
                    ]
                ],
                'integracao-na-estrategia' => [
                    'analysisCriteria' => [
                        'c-alinhamento-de-objectivos-e-metas-com-o-modelo-de-negocio',
                        'c-integracao-na-estrategia-do-negocio',
                    ],
                    'subCategory' => [
                        'prevencao-e-detecao-de-corrupcao-e-suborno',
                        'principais-politicas-de-conduta-e-cultura-corporativa',
                        'relacao-com-fornecedores-e-praticas-de-pagamento',
                        'gestao-de-riscos',
                    ]
                ],
                'resiliencia' => [
                    'analysisCriteria' => [
                        'c-envolvimento-do-mais-alto-orgao-de-governanca',
                    ],
                    'subCategory' => [
                        'prevencao-e-detecao-de-corrupcao-e-suborno',
                        'principais-politicas-de-conduta-e-cultura-corporativa',
                        'gestao-de-riscos',
                    ]
                ]
            ],
        ];

        $this->questionnaire->allQuestionsForMaturity = $this->questionnaire->questionsForMaturity();
        $this->questionnaire->weightableQuestionsForFilter = $this->questionnaire->weightableQuestions();

        $questionnaire = $this->questionnaire;
        $questionnaire->dashboardData = [];
        $questionnaire->result_at = null;
        $questionnaire->save();

        foreach ($chartData as $mainCategory => $mainCategoryData) {
            foreach ($mainCategoryData as $analysisPerspective => $analysisPerspectiveData) {
                $filterTags = [
                    $mainCategory,
                    $analysisPerspective,
                ];

                $dashboardData = $questionnaire->calculatePontuation(
                    function ($q) use ($filterTags) {
                        $questionHasAllTags = $q->internalTags->unique('slug')->toArray();
                        $mustCount = array_intersect($filterTags, array_column($questionHasAllTags, 'slug'));
                        return count($mustCount) == count($filterTags);
                    }
                );

                $newData = Questionnaire::find($questionnaire->id)->dashboardData;
                $newData['simple'][$mainCategory][$analysisPerspective] = $dashboardData;
                $questionnaire->dashboardData = $newData;
                $questionnaire->save();

                foreach ($analysisPerspectiveData['analysisCriteria'] as $subCategory) {
                    foreach ($analysisPerspectiveData['subCategory'] as $analysisCriteria) {
                        $filterTags = [
                            $mainCategory,
                            $analysisPerspective,
                            $subCategory,
                            $analysisCriteria,
                        ];

                        $dashboardData = $questionnaire->calculatePontuation(
                            function ($q) use ($filterTags) {
                                $questionHasAllTags = $q->internalTags->unique('slug')->toArray();
                                $mustCount = array_intersect($filterTags, array_column($questionHasAllTags, 'slug'));
                                return count($mustCount) == count($filterTags);
                            }
                        );

                        // echo "Building data for: $mainCategory - $analysisPerspective - $subCategory - $analysisCriteria\n";

                        $newData = Questionnaire::find($questionnaire->id)->dashboardData;
                        $newData['complete'][$mainCategory][$analysisPerspective][$subCategory][$analysisCriteria] = $dashboardData;
                        $questionnaire->dashboardData = $newData;
                        $questionnaire->save();
                    }
                }
            }
        }

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
