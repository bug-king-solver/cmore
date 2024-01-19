<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy\Modals;

use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Activity extends Component
{
    use AuthorizesRequests;

    public int|Questionnaire $questionnaire;

    public $sectorList;

    public $activityListForm;

    public $sector;

    public $activity;

    public $name;

    public $activityDescription;

    public $eligibility;

    public array $activityVolume = [];

    public $confirmDescription = false;

    public $showModal = false;

    public $isFormFilled = false;

    protected $listeners = [
        'refresh' => '$refresh',
        'createAndContinue' => 'createAndContinue',
        'createAndClose' => 'createAndClose',

    ];

    /**
     * Get the validation rules that apply to the component.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => ['required'],
            'sector' => ['required'],
            'activity' => ['required'],
            'eligibility' => ['required'],
            'activityVolume' => ['required', 'array'],
            'activityVolume.volume.value' => 'required|integer|min:0',
            'activityVolume.capex.value' => 'required|integer|min:0',
            'activityVolume.opex.value' => 'required|integer|min:0',
            'activityVolume.volume.percentage' => 'required|numeric|min:0',
            'activityVolume.capex.percentage' => 'required|numeric|min:0',
            'activityVolume.opex.percentage' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get the validation messages that apply to the component.
     *
     * @return array
     */
    protected function getMessages()
    {
        return [
            'name.required' => __('The name field is required.'),
            'sector.required' => __('The company field is required.'),
            'activity.exists' => __('The activity field is required.'),
            'eligibility.required' => __('The eligibility field is required.'),
            'activityVolume.required' => __('The activity volume field is required.'),
            'activityVolume.volume.value.required' => __('The total field is required.'),
            'activityVolume.volume.value.integer' => __('The total field must be integer.'),
            'activityVolume.volume.value.gt' => __('The total field must be greater than 0.'),
            'activityVolume.capex.value.required' => __('The capex field is required.'),
            'activityVolume.capex.value.integer' => __('The capex field must be integer.'),
            'activityVolume.capex.value.gt' => __('The capex field must be greater than 0.'),
            'activityVolume.opex.value.required' => __('The opex field is required.'),
            'activityVolume.opex.value.integer' => __('The opex field must be integer.'),
            'activityVolume.opex.value.gt' => __('The opex field must be greater than 0.'),
        ];
    }

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->sectorList = BusinessActivities::where('parent_id', null)->get();
        $this->sectorList = parseDataForSelect($this->sectorList, 'id', 'name');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render(): View
    {
        if ($this->name && $this->sector && $this->activity && $this->eligibility && $this->activityVolume && $this->confirmDescription) {
            $this->isFormFilled = true;
        } else {
            $this->isFormFilled = false;
        }

        return view('livewire.tenant.taxonomy.activity');
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
     * Save the activity.
     *
     * @return void
     */
    public function save($continue = true)
    {
        $data = $this->validate(
            $this->getRules(),
            $this->getMessages()
        );

        $values = TaxonomyActivities::activiyResumeDefault();
        $data['activityVolume'] = array_merge($values, $data['activityVolume']);

        $this->questionnaire->taxonomy->activities()->create([
            'name' => $data['name'],
            'business_activities_id' => $data['activity'],
            'summary' => $data['activityVolume'],
            'contribute' => [],
            'dnsh' => [],
        ]);


        if (!$continue) {
            $this->dispatchBrowserEvent('taxonomy-created'); // close the accordion
        }
        $this->clear();
        $this->resetExcept('questionnaire');
        $this->emit('resetInputField');
        $this->emit('taxonomyUpdated');
    }

    /**
     * Clear the form fields.
     */
    public function clear()
    {
        $this->name = null;
        $this->sector = null;
        $this->activity = null;
        $this->eligibility = null;
        $this->activityVolume = [];
    }

    /**
     * Handle updating of sector field.
     *
     * @param mixed $value
     * @return void
     */
    public function updatingSector($value)
    {
        $this->activityListForm = BusinessActivities::where('parent_id', $value)->get();
        $this->activityListForm = parseDataForSelect($this->activityListForm, 'id', 'name');
    }

    /**
     * Handle updating of activity field.
     *
     * @param mixed $value
     * @return void
     */
    public function updatingActivity($value)
    {
        $activity = BusinessActivities::where('id', $value)->first();
        $this->activityDescription = $activity->description ?? '';
    }

    /**
     * Change the activity resume volume.
     *
     * @param  mixed  $value
     * @return void
     */
    public function updatedActivityVolume($value, $nested)
    {
        if ($value == "") {
            $value = 0;
        }
        $total = 0;
        $nested = str_replace(".value", "", $nested);

        $taxonomyTotal = $this->questionnaire->taxonomy->summary['total'][$nested]['value'];
        $this->activityVolume[$nested]['percentage'] = calculatePercentage($value, $taxonomyTotal, 2);

        foreach ($this->questionnaire->taxonomy->activities as $activity) {
            $total += (float)$activity->summary[$nested];
        }

        $totalWithActivity = $total + (float)$value;

        if ($totalWithActivity > $taxonomyTotal) {
            $this->activityVolume[$nested]['value'] = $taxonomyTotal - $total;
            $this->activityVolume[$nested]['percentage'] = calculatePercentage(($this->activityVolume[$nested]['value'] * 100), $taxonomyTotal);
            $message = [
                'title' => __('Warning'),
                'message' => __('The sum of the activities is greater than the total of the business resume'),
            ];
            $this->emit('openModal', 'modals.notification', ['data' => $message]);
            return;
        }
    }

    public function showModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
}
