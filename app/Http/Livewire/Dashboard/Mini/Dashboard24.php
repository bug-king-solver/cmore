<?php

namespace App\Http\Livewire\Dashboard\Mini;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard24 extends Component
{
    use DashboardCalcs;

    public $questionnaire;
    protected $typeId = 24;

    /**
     * Mount the component
     * @param $questionnaires
     */
    public function mount($questionnaires)
    {
        $this->questionnaireIdLists = $questionnaires;
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        $this->questionnaire = $this->questionnaireList->where('id', $this->questionnaireId)->first();

        return view('livewire.tenant.dashboard.mini.st_screen', [
            'readiness' => $this->parseDataForReadiness(),
        ]);
    }

    /**
     * Parse data for readiness graph
     */
    public function parseDataForReadiness()
    {
        $data = [];
        $currentLevel = 0;
        // level 1
            // level 1 on 1
            $result = $this->fetchData($this->questionnaireId, [1663])->first();
            $level1 = $result['value'] ?? false;

            $result = $this->fetchData($this->questionnaireId, [2100])->first();
            $level2 = $result['value'] ?? false;

            $result = $this->fetchData($this->questionnaireId, [1597])->first();
            $level3 = $result['value'] ?? false;

            $result = $this->fetchData($this->questionnaireId, [1746])->first();
            $level4 = $result['value'] ?? false;

            $result = $this->fetchData($this->questionnaireId, [5129])->first();
            $level5 = $result['value'] ?? false;

            // level 1 on 2
            $level6 = $this->fetchData($this->questionnaireId, [5610])->first()['value'] ?? false;

            // level 1 on 3
            $level7 = $this->fetchData($this->questionnaireId, [1723])->first()['value'] ?? false;

            // level 1 on 4
            $level8 = $this->fetchData($this->questionnaireId, [2037])->first()['value'] ?? false;
            $level9 = $this->fetchData($this->questionnaireId, [1676])->first()['value'] ?? false;

            $result = $level1 && $level2 && $level3 && $level4 && $level5 ?? false;
            $result1 = ($level6 == 'yes' ? true : false);
            $result2 = ($level7 == 'yes' ? true : false);
            $result3 = $level8 && $level9 ?? false;

            if ($result == true && $result1 == true && $result2 == true && $result3 == true) {
                $currentLevel = 1;
            }

        // Level 2
            // Level 2 0n 1
            $level10 = $this->fetchData($this->questionnaireId, [4477])->first()['value'] ?? false;
            $level11 = $this->fetchData($this->questionnaireId, [5169])->first()['value'] ?? false;
            $level12 = $this->fetchData($this->questionnaireId, [1677])->first()['value'] ?? false;

            // Level 2 0n 2
            $level13 =  $this->fetchData($this->questionnaireId, [2107])->first()['value'] ?? false;

            // Level 2 0n 3
            $level14 =  $this->fetchData($this->questionnaireId, [2108])->first()['value'] ?? false;

            // Level 2 0n 4
            $level15 =  $this->fetchData($this->questionnaireId, [2128])->first()['value'] ?? false;

            // Level 2 0n 5
            $level16 =  $this->fetchData($this->questionnaireId, [4969])->first()['value'] ?? false;
            $level17 =  $this->fetchData($this->questionnaireId, [2065])->first()['value'] ?? false;

            $result4 = $level10 && $level11 && $level12 ?? false;
            $result5 = ($level13 == 'yes' ? true : false);
            $result6 = ($level14 == 'yes' ? true : false);
            $result7 = ($level15 == 'yes' ? true : false);
            $result8 = $level16 && $level17 ?? false;

            if ($result4 == true && $result5 == true && $result6 == true && $result7 == true && $result8 == true && $currentLevel == 1) {
                $currentLevel = 2;
            }

        // level 3
            // Level 3 on 1
            $level18 =  $this->fetchData($this->questionnaireId, [2101])->first()['value'] ?? false;
            $level19 =  $this->fetchData($this->questionnaireId, [2102])->first()['value'] ?? false;
            $level20 =  $this->fetchData($this->questionnaireId, [2103])->first()['value'] ?? false;

            // Level 3 on 2
            $level21 =  $this->fetchData($this->questionnaireId, [2116])->first()['value'] ?? false;

            // Level 3 on 3
            $level22 =  $this->fetchData($this->questionnaireId, [1764])->first()['value'] ?? false;
            $level23 =  $this->fetchData($this->questionnaireId, [2131])->first()['value'] ?? false;
            $level24 =  $this->fetchData($this->questionnaireId, [1757])->first()['value'] ?? false;
            $level25 =  $this->fetchData($this->questionnaireId, [1758])->first()['value'] ?? false;
            $level26 =  $this->fetchData($this->questionnaireId, [1759])->first()['value'] ?? false;
            $level27 =  $this->fetchData($this->questionnaireId, [1750])->first()['value'] ?? false;
            $level28 =  $this->fetchData($this->questionnaireId, [1920])->first()['value'] ?? false;
            $level29 =  $this->fetchData($this->questionnaireId, [5628])->first()['value'] ?? false;
            $level30 =  $this->fetchData($this->questionnaireId, [3543])->first()['value'] ?? false;
            $level31 =  $this->fetchData($this->questionnaireId, [5629])->first()['value'] ?? false;
            $level32 =  $this->fetchData($this->questionnaireId, [2137])->first()['value'] ?? false;
            $level33 =  $this->fetchData($this->questionnaireId, [4397])->first()['value'] ?? false;

            // Level 3 on 4
            $level34 = $this->fetchData($this->questionnaireId, [3417])->first()['value'] ?? false;
            // Level 3 on 5
            $level35 = $this->fetchData($this->questionnaireId, [445])->first()['value'] ?? false;

            $result9 = $level18 && $level19 && $level20 ?? false;
            $result10 = ($level21 == 'yes' ? true : false);
            $result11 = $level22 && $level23 && $level24 && $level25 && $level26 && $level27 && $level28 && $level29 && $level30 && $level31 && $level32 && $level33 ?? false;
            $result12 = ($level34 == '1' ? true : false);
            $result13 = ($level35 >= '33.3' ? true : false);

            if ($result9 == true && $result10 == true && $result11 == true && $result12 == true && $result13 == true && $currentLevel == 2) {
                $currentLevel = 3;
            }


        // level 4
            // Level 4 on 1
            $level36 =  $this->fetchData($this->questionnaireId, [2104])->first()['value'] ?? false;

            // Level 4 on 2
            $level37 =  $this->fetchData($this->questionnaireId, [1760])->first()['value'] ?? false;

            // Level 4 on 3
            $level38 =  $this->fetchData($this->questionnaireId, [1865])->first()['value'] ?? false;

            // Level 4 on 4
            $level39 =  $this->fetchData($this->questionnaireId, [1592])->first()['value'] ?? false;

            // Level 4 on 5
            $level40 =  $this->fetchData($this->questionnaireId, [2109])->first()['value'] ?? false;

            $result14 = ($level36 == 'yes' ? true : false);
            $result15 = ($level37 == 'yes' ? true : false);
            $result16 = ($level38 == 'yes' ? true : false);
            $result17 = ($level39 == 'yes' ? true : false);
            $result18 = ($level40 == 'yes' ? true : false);

            if ($result14 == true && $result15 == true && $result16 == true && $result17 == true && $result18 == true && $currentLevel == 3) {
                $currentLevel = 4;
            }


        if ($currentLevel == 4) {
            $currentLevel = 5;
        }

        $data = [
            'current_level' => $currentLevel,
            'level1' => [
                [ 'complete' => $result, 'tooltip' => __('Master of Policies')],
                [ 'complete' => $result1, 'tooltip' => __('Highest Governance Body constitution')],
                [ 'complete' => $result2, 'tooltip' => __('Select your Suppliers')],
                [ 'complete' => $result3, 'tooltip' => __('Committed to corruption and conflict of interests prevention')]
            ],
            'level2' => [
                [ 'complete' => $result4, 'tooltip' => __('Committed to the best practices')],
                [ 'complete' => $result5, 'tooltip' => __('Committed to the ethic and conduct')],
                [ 'complete' => $result6, 'tooltip' => __('Committed to the policies implementation')],
                [ 'complete' => $result7, 'tooltip' => __('Committed to Due Diligence')],
                [ 'complete' => $result8, 'tooltip' => __('Disclosure of the policies')],
            ],
            'level3' => [
                [ 'complete' => $result9, 'tooltip' => __('Complaint Mechanisms')],
                [ 'complete' => $result10, 'tooltip' => __('Master of Reports')],
                [ 'complete' => $result11, 'tooltip' => __('Environmentally Conscious')],
                [ 'complete' => $result12, 'tooltip' => __('Customer Oriented')],
                [ 'complete' => $result13, 'tooltip' => __('Gender Equality')],
            ],
            'level4' => [
                [ 'complete' => $result14, 'tooltip' => __('Attentive to SDGs')],
                [ 'complete' => $result15, 'tooltip' => __('Committed to your carbon footprint')],
                [ 'complete' => $result16, 'tooltip' => __('Committed to your energy efficiency ')],
                [ 'complete' => $result17, 'tooltip' => __('Adherence to global principles ')],
                [ 'complete' => $result18, 'tooltip' => __('Social Responsibility')],
            ]
        ];
        return $data;
    }
}
