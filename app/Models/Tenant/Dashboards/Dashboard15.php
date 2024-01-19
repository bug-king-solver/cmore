<?php

namespace App\Models\Tenant\Dashboards;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Data;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard15
{
    use DashboardCalcs;

    protected $shouldDivideBy1000;
    protected $route_params;
    protected $charts;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->shouldDivideBy1000 = true;

        // get route params
        $this->route_params = request()->query();

        $this->charts = [
            [
                'name' => __('Scope 1'),
                'categories' => [
                    [
                        'name' => __('Butane'),
                        'indicators' => [6098, 6208],
                        'math' => '6098 + 6208',
                    ],
                    [
                        'name' => __('Compressed Natural Gas (CNG)'),
                        'indicators' => [6099, 6209],
                        'math' => '6099 + 6209',
                    ],
                    [
                        'name' => __('Liquefied Natural Gas (LNG)'),
                        'indicators' => [6100, 6210],
                        'math' => '6100 + 6210',
                    ],
                    [
                        'name' => __('GPL'),
                        'indicators' => [6105, 6211],
                        'math' => '6105 + 6211',
                    ],
                    [
                        'name' => __('Natural Gas '),
                        'indicators' => [6106, 6212],
                        'math' => '6106 + 6212',
                    ],
                    [
                        'name' => __('Propane'),
                        'indicators' => [6107, 6213],
                        'math' => '6107 + 6213',
                    ],
                    [
                        'name' => __('Aviation fuel (jet fuel)'),
                        'indicators' => [6108, 6214],
                        'math' => '6108 + 6214',
                    ],
                    [
                        'name' => __('Kerosene'),
                        'indicators' => [6109, 6215],
                        'math' => '6109 + 6215',
                    ],
                    [
                        'name' => __('Diesel (average biofuel blend)'),
                        'indicators' => [6110, 6216],
                        'math' => '6110 + 6216',
                    ],
                    [
                        'name' => __('Diesel (100% mineral diesel)'),
                        'indicators' => [6111, 6217],
                        'math' => '6111 + 6217',
                    ],
                    [
                        'name' => __('Petrol (average biofuel blend)'),
                        'indicators' => [6112, 6218],
                        'math' => '6112 + 6218',
                    ],
                    [
                        'name' => __('Petrol (100% mineral petrol)'),
                        'indicators' => [6113, 6219],
                        'math' => '6113 + 6219',
                    ],
                    [
                        'name' => __('Coal (industrial)'),
                        'indicators' => [6114],
                        'math' => '6114',

                    ],
                    [
                        'name' => __('Coal (electricity generation)'),
                        'indicators' => [6115],
                        'math' => '6115',
                    ],
                    [
                        'name' => __('Coal (domestic)'),
                        'indicators' => [6116],
                        'math' => '6116',
                    ],
                    [
                        'name' => __('Veículo elétrico'),
                        'indicators' => [6220],
                        'math' => '6220',
                    ],
                    [
                        'name' => __('Naphta'),
                        'indicators' => [7573, 7575],
                        'math' => '7573 + 7575',
                    ],
                    [
                        'name' => __('Fluorinated gases'),
                        'indicators' => [6102, 6103, 6104, 6121, 6122, 6123, 6124, 6125, 6126, 6127, 6128, 6129, 6130, 6131, 6132, 6133, 6134, 6135, 6136, 6137, 6138, 6139, 6140, 6141, 6142, 6143, 6144, 6145, 6146, 6147, 6148, 6149, 6150, 6151, 6152, 6153, 6154, 6155, 6156, 6157, 6158, 6159, 6160, 6161, 6162, 6163, 6164, 6165, 6166, 6167, 6168, 6169, 6170, 6171, 6172, 6173, 6174, 6175, 6176, 6177, 6178, 6179, 6180, 6181, 6182, 6183, 6184, 6185, 6186, 6187, 6188, 6189, 6190, 6191, 6192, 6193, 6194, 6195, 6196, 6197, 6198, 6199, 6200, 6201, 6202, 6203, 6204, 6205, 6206, 6207],
                        'math' => '6102 + 6103 + 6104 + 6121 + 6122 + 6123 + 6124 + 6125 + 6126 + 6127 + 6128 + 6129 + 6130 + 6131 + 6132 + 6133 + 6134 + 6135 + 6136 + 6137 + 6138 + 6139 + 6140 + 6141 + 6142 + 6143 + 6144 + 6145 + 6146 + 6147 + 6148 + 6149 + 6150 + 6151 + 6152 + 6153 + 6154 + 6155 + 6156 + 6157 + 6158 + 6159 + 6160 + 6161 + 6162 + 6163 + 6164 + 6165 + 6166 + 6167 + 6168 + 6169 + 6170 + 6171 + 6172 + 6173 + 6174 + 6175 + 6176 + 6177 + 6178 + 6179 + 6180 + 6181 + 6182 + 6183 + 6184 + 6185 + 6186 + 6187 + 6188 + 6189 + 6190 + 6191 + 6192 + 6193 + 6194 + 6195 + 6196 + 6197 + 6198 + 6199 + 6200 + 6201 + 6202 + 6203 + 6204 + 6205 + 6206 + 6207'
                    ]
                ],
            ],
            [
                'name' => __('Scope 2'),
                'categories' => [
                    [
                        'name' => __('Eletricidade (CO2eq)'),
                        'indicators' => [6117],
                        'math' => '6117',
                    ],
                    [
                        'name' => __('Vapor (CO2eq)'),
                        'indicators' => [6118],
                        'math' => '6118',
                    ],
                    [
                        'name' => __('Aquecimento (industrial) (CO2eq)'),
                        'indicators' => [6119],
                        'math' => '6119',
                    ],
                    [
                        'name' => __('Arrefecimento (industrial) (CO2eq) '),
                        'indicators' => [6120],
                        'math' => '6120',
                    ],
                ]
            ],
            [
                'name' => __('Scope 3'),
                'categories' => [
                    [
                        'name' => __('Purchased goods and services'),
                        'indicators' => [6223, 6224, 6225, 6226, 6227, 6228, 6229, 6230, 6231, 6232, 6233, 6234, 6235, 6236, 6237, 6238, 6239, 6240, 6241, 6242, 6243, 6244, 6245, 6246, 6247, 6248, 6249, 6250, 6251, 6252, 6253, 6254, 6255, 6256, 6257, 6258],
                        'math' => '6223 + 6224 + 6225 + 6226 + 6227 + 6228 + 6229 + 6230 + 6231 + 6232 + 6233 + 6234 + 6235 + 6236 + 6237 + 6238 + 6239 + 6240 + 6241 + 6242 + 6243 + 6244 + 6245 + 6246 + 6247 + 6248 + 6249 + 6250 + 6251 + 6252 + 6253 + 6254 + 6255 + 6256 + 6257 + 6258'
                    ],
                    [
                        'name' => __('Capital goods'),
                        'indicators' => [6259, 6260, 6261, 6262, 6263, 6264, 6265, 6266, 6267, 6268, 6269, 6270, 6271, 6272, 6273, 6274, 6275, 6276, 6277, 6278, 6279, 6280, 6281, 6282, 6283, 6284, 6285, 6286, 6287, 6288, 6289, 6290, 6291, 6292, 6293, 6294],
                        'math' => '6259 + 6260 + 6261 + 6262 + 6263 + 6264 + 6265 + 6266 + 6267 + 6268 + 6269 + 6270 + 6271 + 6272 + 6273 + 6274 + 6275 + 6276 + 6277 + 6278 + 6279 + 6280 + 6281 + 6282 + 6283 + 6284 + 6285 + 6286 + 6287 + 6288 + 6289 + 6290 + 6291 + 6292 + 6293 + 6294'
                    ],
                    [
                        'name' => __('Fuel- and energy-related activities '),
                        'indicators' => "all",
                        'custom_column_value' => 'ghost_emission',
                        'math' => null,
                    ],
                    [
                        'name' => __('Upstream transportation and distribution'),
                        'indicators' => [6295, 6296, 6297, 6298],
                        'math' => '6295 + 6296 + 6297 + 6298'
                    ],
                    [
                        'name' => __('Waste generated in operations'),
                        'indicators' => [6414, 6415, 6416, 6417, 6418, 6419, 6420, 6421, 6422, 6423, 6424, 6425, 6426, 6427, 6428, 6429, 6430, 6431, 6432, 6433, 6434, 6435, 6436, 6437, 6438, 6439, 6440, 6441, 6442, 6443, 6444, 6445, 6446, 6447, 6448, 6449, 6450, 6451, 6452, 6453, 6454, 6455, 6456, 6457, 6458, 6459, 6460, 6461, 6462, 6463, 6464, 6465, 6466, 6467, 6468, 6469, 6470, 6471, 6472, 6473, 6474, 6475, 6476, 6477, 6478, 6479, 6480, 6481, 6482, 6483, 6484, 6485, 6486, 6487, 6221, 6222],
                        'math' => '6414 + 6415 + 6416 + 6417 + 6418 + 6419 + 6420 + 6421 + 6422 + 6423 + 6424 + 6425 + 6426 + 6427 + 6428 + 6429 + 6430 + 6431 + 6432 + 6433 + 6434 + 6435 + 6436 + 6437 + 6438 + 6439 + 6440 + 6441 + 6442 + 6443 + 6444 + 6445 + 6446 + 6447 + 6448 + 6449 + 6450 + 6451 + 6452 + 6453 + 6454 + 6455 + 6456 + 6457 + 6458 + 6459 + 6460 + 6461 + 6462 + 6463 + 6464 + 6465 + 6466 + 6467 + 6468 + 6469 + 6470 + 6471 + 6472 + 6473 + 6474 + 6475 + 6476 + 6477 + 6478 + 6479 + 6480 + 6481 + 6482 + 6483 + 6484 + 6485 + 6486 + 6487 + 6221 + 6222'
                    ],
                    [
                        'name' => __('Business travel'),
                        'indicators' => [6300, 6299, 6301, 6302],
                        'math' => '6300 + 6299 + 6301 + 6302'
                    ],
                    [
                        'name' => __('Employee commuting'),
                        'indicators' => [6303, 6304, 6305, 6306, 6307, 6308, 6309, 6310, 6311, 6312, 6313, 6314, 6315, 6316, 6317, 7577],
                        'math' => '6303 + 6304 + 6305 + 6306 + 6307 + 6308 + 6309 + 6310 + 6311 + 6312 + 6313 + 6314 + 6315 + 6316 + 6317 + 7577'
                    ],
                    [
                        'name' => __('Upstream leased assets'),
                        'indicators' => [6488, 6489],
                        'math' => '6488 + 6489'
                    ],
                    [
                        'name' => __('Downstream transportation and distribution'),
                        'indicators' => [6320, 6321, 6322, 6323],
                        'math' => '6320 + 6321 + 6322 + 6323'
                    ],
                    [
                        'name' => __('Processing of sold products'),
                        'indicators' => [6324, 6325, 6326, 6327, 6328, 6329, 6330, 6331, 6332, 6333, 6334, 6335, 6336, 6337, 6338, 6339, 6340, 6341, 6342, 6343, 6344, 6345, 6346, 6347, 6348, 6349, 6350, 6351, 6352, 6353, 6354, 6355, 6356, 6357, 6358, 6359],
                        'math' => '6324 + 6325 + 6326 + 6327 + 6328 + 6329 + 6330 + 6331 + 6332 + 6333 + 6334 + 6335 + 6336 + 6337 + 6338 + 6339 + 6340 + 6341 + 6342 + 6343 + 6344 + 6345 + 6346 + 6347 + 6348 + 6349 + 6350 + 6351 + 6352 + 6353 + 6354 + 6355 + 6356 + 6357 + 6358 + 6359'
                    ],
                    [
                        'name' => __('Use of sold products'),
                        'indicators' => [6360, 6361, 6362, 6363, 6364, 6365, 6366, 6367, 6368, 6369, 6370, 6371, 6372, 6373, 6374, 7579],
                        'math' => '6360 + 6361 + 6362 + 6363 + 6364 + 6365 + 6366 + 6367 + 6368 + 6369 + 6370 + 6371 + 6372 + 6373 + 6374 + 7579'
                    ],
                    [
                        'name' => __('End-of-life treatment of sold products'),
                        'indicators' => [6375, 6376, 6377, 6378, 6379, 6380, 6381, 6382, 6383, 6384, 6385, 6386, 6387, 6388, 6389, 6390, 6391, 6392, 6393, 6394, 6395, 6396, 6397, 6398, 6399, 6400, 6401, 6402, 6403, 6404, 6405, 6406, 6407, 6408, 6409],
                        'math' => '6375 + 6376 + 6377 + 6378 + 6379 + 6380 + 6381 + 6382 + 6383 + 6384 + 6385 + 6386 + 6387 + 6388 + 6389 + 6390 + 6391 + 6392 + 6393 + 6394 + 6395 + 6396 + 6397 + 6398 + 6399 + 6400 + 6401 + 6402 + 6403 + 6404 + 6405 + 6406 + 6407 + 6408 + 6409'
                    ],
                    [
                        'name' => __('Downstream leased assets'),
                        'indicators' => [6318, 6319],
                        'math' => '6318 + 6319'
                    ],
                    [
                        'name' => __('Franchises'),
                        'indicators' => [6410, 6411],
                        'math' => '6410 + 6411'
                    ],
                    [
                        'name' => __('Investments'),
                        'indicators' => [6412, 6413],
                        'math' => '6412 + 6413'
                    ],
                ]
            ]
        ];
    }

    /**
     * Render view
     */
    public function view($questionnaireId)
    {
        $this->questionnaire = Questionnaire::find($questionnaireId);

        $this->setIndicatorsData($this->questionnaire);
        $this->setIndicators();

        $charts = $this->parseChartsStructureCategories($this->charts);
        $charts = collect($charts);

        // only the total to single array
        $totals = $charts->map(function ($item) {
            return $item['total'];
        });

        $categoriesNameScope3 = collect($charts[2]['categories'])->map(function ($category) {
            if ($category['total'] != null) {
                return [
                    'name' => $category['name'],
                    'total' => $category['total']
                ];
            }
        })->toArray();

        $filteredCategories = array_filter($categoriesNameScope3, function ($a) {
            return $a !== null;
        });

        $isEmpty = empty($filteredCategories);

        if (isset($this->route_params['demo']) && $this->route_params['demo'] == "true") {
            return tenantView('tenant.dashboards.demos.15');
        }

        return tenantView('tenant.dashboards.15', compact(
            'charts',
            'totals',
            'categoriesNameScope3',
            'isEmpty'
        ));
    }
}