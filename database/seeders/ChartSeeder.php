<?php

namespace Database\Seeders;

use App\Models\Chart;
use Illuminate\Database\Seeder;

class ChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Chart::truncate();

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "backgroundColor" => ["esg5", "esg22", "esg23"],
                    ],
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                        ],
                        "layout" => [
                            "padding" => 30,
                        ],
            ],
            "plugins" => [],
        ];

        Chart::create([
            'slug' => 'total-ghg-emissions',
            'name' => 'Total de Emissões de gases com efeito de estufa',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[2748,2749,2750]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [],
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 30,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'total-energy-used',
            'name' => 'Total de Energia utilizada',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[2695,2696,2697,2698,3411,2699,2700,2701,2702,2686,2687,2688,2689,2690,2691,2692,2693,2694]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "axis" => "y",
                        "backgroundColor" => ["esg5", "esg22", "esg23", "esg21"]
                    ]
                ]
            ],
            "options" => [
                "indexAxis" => 'y',
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 30,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 40,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'total-water-used',
            'name' => 'Total de utilização de Água',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[3061,290,307,3072]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "pie",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => ["esg5", "esg22", "esg23"]
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 60,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'total-waste-produced',
            'name' => 'Tipo de Resíduos produzidos',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[3269,509,5000]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => ["esg5", "esg22", "esg23", "esg21", "esg20", "esg9"]
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 30,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'total-waste-destinations',
            'name' => 'Destinos/Operações de tratamento de resíduos',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[3292,3293,3294,3295,3296,3297]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => ["esg5"]
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 30,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'gender-pay-gap',
            'name' => 'Disparidade Salarial entre Géneros',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[2597]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => []
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 30,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'diversity-per-gender',
            'name' => 'Diversidade dos colaboradores: Representação de Género',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[64,65,66,67,76,80,81,82,2594]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "doughnut",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => []
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 60,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'diversity-per-age',
            'name' => 'Diversidade dos colaboradores: Representação de Idades',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[64,70,71,72,2593]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "pie",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => []
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 60,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'diversity-per-minority',
            'name' => 'Diversidade dos colaboradores: Representação de Minorias',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[64,76,1973]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => []
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 30,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'diversity-per-nationality',
            'name' => 'Diversidade dos colaboradores: Representação de Nacionalidades',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[64,76,1517]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "doughnut",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => []
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 60,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'supplier-evaluation',
            'name' => 'Avaliação de Fornecedores',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[129,138,142]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $headings = [
            ['slug' => 'h1', 'name' => 'Heading 1', 'class' => 'text-2xl font-bold font-encodesans'],
            ['slug' => 'h2', 'name' => 'Heading 2', 'class' => 'text-xl font-bold font-encodesans'],
            ['slug' => 'h3', 'name' => 'Heading 3', 'class' => 'text-lg font-bold font-encodesans'],
            ['slug' => 'h4', 'name' => 'Heading 4', 'class' => 'text-base font-bold font-encodesans'],
            ['slug' => 'h5', 'name' => 'Heading 5', 'class' => 'text-sm font-bold font-encodesans'],
            ['slug' => 'h6', 'name' => 'Heading 6', 'class' => 'text-xs font-bold font-encodesans'],
        ];

        foreach ($headings as $heading) {
            Chart::create([
                'slug' => $heading['slug'],
                'name' => $heading['name'],
                'type' => 'text',
                'structure' => json_encode([
                    'type' => $heading['slug'],
                    'attributes' => "class='{$heading['class']}'"
                ]),
            ]);
        }

        $structure = [
            "type" => "pie",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => ["esg5", "esg22", "esg23"]
                    ]
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                ],
                "layout" => [
                    "padding" => 60,
                ],
            ],
            "plugins" => []
        ];

        Chart::create([
            'slug' => 'environment-energy-total-waste',
            'name' => 'Consumo total de energia',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[2120,787,2240,2658]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "backgroundColor" => ["esg5", "esg22", "esg23"],
                    ],
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                        ],
                        "layout" => [
                            "padding" => 30,
                        ],
            ],
            "plugins" => [],
        ];

        Chart::create([
            'slug' => 'total_de_emissões_gee',
            'name' => 'Total de emissões GEE',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[20,21,22,5660,5661,5662]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'número-de-casos-por-alegação',
            'name' => 'Número de casos por alegação',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[189,5154]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'número-de-casos-por-alegação-confirmada',
            'name' => 'Número de casos por alegação confirmada',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[190,5157]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        $structure = [
            "type" => "bar",
            "data" => [
                "labels" => [],
                "datasets" => [
                    [
                        "backgroundColor" => ["esg5", "esg22", "esg23"],
                    ],
                ]
            ],
            "options" => [
                "plugins" => [
                    "legend" => [
                        "position" => "right",
                        "labels" => [
                            "usePointStyle" => true,
                            "borderRadius" => 50,
                        ],
                    ]
                        ],
                        "layout" => [
                            "padding" => 30,
                        ],
            ],
            "plugins" => [],
        ];

        Chart::create([
            'slug' => 'trabalhadores-contratados-por-género',
            'name' => 'Trabalhadores contratados por género',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[65,66,67,436,437,438]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'total-de-emissões-gee',
            'name' => 'Total de emissões GEE',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[20,21,22,5660,5661,5662]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'trabalhadores-subcontratados-por-género',
            'name' => 'Trabalhadores subcontratados por género',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[207,208,3541,699,700,701]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'saúde-e-segurança-no-trabalho',
            'name' => 'Saúde e segurança no trabalho',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[396,2124]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'número-de-membros-do-mais-alto-orgão-de-governança-por-género',
            'name' => 'Número de membros do mais alto orgão de governança por género',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[423,424,425]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'percentagem-de-membros-do-mais-alto-orgão-de-governança-por-género',
            'name' => 'Percentagem de membros do mais alto orgão de governança por género',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[445,446,447]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'fornecedores-avaliados',
            'name' => 'Fornecedores avaliados',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[138,142]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);

        Chart::create([
            'slug' => 'fornecedores-causadores-de-impactos-negativos',
            'name' => 'Fornecedores causadores de impactos negativos',
            'type' => 'chart',
            'structure' => json_encode($structure),
            'indicators' => '[139,143]',
            'placeholder' => 'images/charts/doughnut.png',
        ]);
    }
}
