<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy\Modals;

use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ImportActivity extends Component
{
    use AuthorizesRequests;

    public int|Questionnaire $questionnaire;
    public Taxonomy $taxonomy;

    public array $importedActivity = [];
    public $activityList = [];
    public $objectiveFormList = [];
    public $isFormFilled = false;
    public $percentageAligned = 0;

    protected $listeners = [
        'refresh' => '$refresh',
        'createAndContinue' => 'createAndContinue',
        'createAndClose' => 'createAndClose',
    ];


    protected $rules = [
        'importedActivity' => 'required|array',
        'importedActivity.name' => 'required|string',
        'importedActivity.activity' => 'required|integer',
        'importedActivity.volume' => 'required|array',
        'importedActivity.objective' => 'required|array',
        'importedActivity.volume.volume.value' => 'required|integer|min:0',
        'importedActivity.volume.capex.value' => 'required|integer|min:0',
        'importedActivity.volume.opex.value' => 'required|integer|min:0',
        'importedActivity.volume.volume.percentage' => 'required|numeric|min:0',
        'importedActivity.volume.capex.percentage' => 'required|numeric|min:0',
        'importedActivity.volume.opex.percentage' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'importedActivity.name.required' => 'O nome da atividade é obrigatório',
        'importedActivity.activity.required' => 'A atividade é obrigatória',
        'importedActivity.volume.volume.required' => 'O volume é obrigatório',
        'importedActivity.volume.capex.required' => 'O capex é obrigatório',
        'importedActivity.volume.opex.required' => 'O opex é obrigatório',
    ];

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->taxonomy = $this->questionnaire->taxonomy;
        $this->activityList = BusinessActivities::where('parent_id', '!=', null)->get();
        $this->activityList = parseDataForSelect($this->activityList, 'id', 'name');
        $baseArray = [
            'value' => 0,
            'percentage' => 0,
        ];

        $this->importedActivity = [
            'name' => '',
            'activity' => '',
            'objective' => [],
            'volume' => [
                'volume' => $baseArray,
                'capex' => $baseArray,
                'opex' => $baseArray,
            ]
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render(): View
    {
        return view('livewire.tenant.taxonomy.import-activity');
    }

    /**
     * When change the value of the array "ImportedActivity", apply this rules.
     * @param $value
     * @param $arrayKey
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updatedImportedActivity($value, $arrayKey)
    {
        $objectiveArray = explode(".", $arrayKey);
        $data = $this->importedActivity;

        // After change the select with the activity, we need to get the questions of the activity selected.
        if ($arrayKey == 'activity' && $value != null) {
            $businessActivity = BusinessActivities::find($value);
            $questions = TaxonomyActivities::getQuestions($businessActivity->code);
            $list = [];
            $data['objective'] = [];  //reset the values of the objective array

            foreach ($questions as $key => $category) {
                foreach ($category as $i => $objective) {
                    $list[$key][translateJson($objective['name'])] = [
                        'name' => $objective['name'],
                        'hasPercentage' => $key == 'cs' ? true : false,
                    ];
                }
            }

            $this->objectiveFormList = array_unique($list, SORT_REGULAR); // remove the duplicated values
            $this->objectiveFormList = array_merge($this->objectiveFormList['dnsh'] ?? [], $this->objectiveFormList['cs'] ?? []);
            // sort the array by the objective name
            $this->objectiveFormList = array_sort($this->objectiveFormList, function ($value) {
                return !$value['hasPercentage'];
            });

            // Create the default values for the objective array.
            // So ,we can use the Array position to map the objective with the percentage and if is aligned or not.
            foreach ($this->objectiveFormList as $key => $value) {
                $this->importedActivity['objective'][$key]['percentage'] = 0;
                $this->importedActivity['objective'][$key]['aligned'] = false;
                $this->importedActivity['objective'][$key]['dnsh'] = false;
            }
        }

        // calc the values of the volume array
        // check if the value is greater than the total of the taxonomy
        if (count($objectiveArray) == 3) {
            if ($objectiveArray[0] === 'volume') {

                $taxonomyTotal = $this->taxonomy->summary['total'][$objectiveArray[1]]['value'] ?? 0;
                $total = (float)$value;
                foreach ($this->taxonomy->activities as $activity) {
                    $total += (float)($activity->summary[$objectiveArray[1]]['value'] ?? 0);
                }
                $this->importedActivity['volume'][$objectiveArray[1]]['percentage'] = calculatePercentage($value, $taxonomyTotal, 2);

                if ($total > $taxonomyTotal) {
                    $message = [
                        'title' => __('Warning'),
                        'message' => __('The value cannot be greater than the total of the taxonomy.'),
                    ];
                    $this->emit('openModal', 'modals.notification', ['data' => $message]);
                    $this->importedActivity['volume'][$objectiveArray[1]]['value'] = 0;
                    $this->importedActivity['volume'][$objectiveArray[1]]['percentage'] = 0;
                    return;
                }
            } else if ($objectiveArray[2] !== 'dnsh') {
                if ($objectiveArray[2] == 'aligned' && $value == false) {
                    $this->importedActivity['objective'][$objectiveArray[1]]['dnsh'] = false;
                } else if ($objectiveArray[2] == 'aligned' && $value == true)  {
                    $this->importedActivity['objective'][$objectiveArray[1]]['dnsh'] = null;
                }
                $this->percentageAligned = 0;
                $percentageTotal = 0;
                foreach ($this->importedActivity['objective'] as $key => $objective) {
                    $percentageTotal += $objective['percentage'] ?? 0;
                    if ($objective['aligned'] == true && $objective['percentage'] != null) {
                        $this->percentageAligned += $objective['percentage'];
                    }
                }

                if ($percentageTotal > 100) {
                    $this->percentageAligned = 100;
                    $message = [
                        'title' => __('Warning'),
                        'message' => __('The sum of the objectives is greater than 100%'),
                    ];
                    $this->emit('openModal', 'modals.notification', ['data' => $message]);
                    $this->importedActivity['objective'][$objectiveArray[1]]['percentage'] = 0;
                    return;
                }
            }
        }


        if (!empty($data['name']) && !empty($data['activity'])) {
            $this->isFormFilled = true;
        } else {
            $this->isFormFilled = false;
        }
    }

    /**
     * Fire the event to create and continue with the form open
     */
    public function createAndContinue()
    {
        $this->save();
    }

    /**
     * Fire the event to create and close the form
     */
    public function createAndClose()
    {
        $this->save($continue = false);
    }

    /**
     * Change the activity substancial contribute.
     */
    public function save($continue = true)
    {
        try {
            $data = $this->validate();
            $total = 0;
            $total  = array_sum(array_column($data['importedActivity']['objective'], 'percentage'));

            if ($total > 100) {
                $message = [
                    'title' => __('Warning'),
                    'message' => __('The sum of the objectives is greater than 100%'),
                ];
                $this->emit('openModal', 'modals.notification', ['data' => $message]);
                return;
            }

            if ($total < 0) {
                $message = [
                    'title' => __('Warning'),
                    'message' => __('The sum of the objectives is less than 0%'),
                ];
                $this->emit('openModal', 'modals.notification', ['data' => $message]);
                return;
            }

            $values = TaxonomyActivities::activiyResumeDefault();
            $data['importedActivity']['volume'] = array_merge($values, $data['importedActivity']['volume']);

            $activity = $this->taxonomy->activities()->create([
                'name' => $data['importedActivity']['name'],
                'business_activities_id' => $data['importedActivity']['activity'],
                'summary' => $data['importedActivity']['volume'],
            ]);

            $contribute = $activity->contribute;
            $dnsh = $activity->dnsh;

            $contribute['imported'] = 1;
            $dnsh['imported'] = 1;

            foreach ($contribute['data'] as $arrayPosition => &$objective) {
                $dataObjective = $data['importedActivity']['objective'][translateJson($objective['name'])] ?? null;
                if (!$dataObjective) {
                    continue;
                }

                $objective['percentage'] = $dataObjective['percentage'] ?? 0;
                $hasVerified = $dataObjective['aligned'] ?? 0;
                $objective['verified'] = $hasVerified
                    ? 1
                    : 0;
                $objective['imported'] = 1;
            }

            foreach ($dnsh['data'] as &$objective) {
                $dataObjective = $data['importedActivity']['objective'][translateJson($objective['name'])] ?? null;

                if (!$dataObjective) {
                    continue;
                }

                if ($dataObjective['percentage'] > 0 && $dataObjective['aligned']) {
                    $objective['verified'] = 1;
                } else {
                    $hasVerified = $dataObjective['dnsh'] ?? 0;
                    $objective['verified'] = $hasVerified
                        ? 1
                        : 0;
                }
                $objective['imported'] = 1;
            }

            $activity->update([
                'contribute' => $contribute,
                'dnsh' => $dnsh,
            ]);

            $activity->update([
                'contribute' => $activity->completeContribute($update = false),
                'dnsh' => $activity->completeNps($update = false),
            ]);

            if (!$continue) {
                $this->dispatchBrowserEvent('taxonomy-imported'); // close the accordion
            }

            $this->resetExcept('questionnaire', 'taxonomy');
            $this->emit('resetInputField');
            $this->emit('taxonomyUpdated');
        } catch (Exception | QueryException $ex) {
            throw $ex;
        }
    }
}
