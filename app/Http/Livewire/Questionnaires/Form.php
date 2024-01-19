<?php

namespace App\Http\Livewire\Questionnaires;

use App\Http\Livewire\Compliance\DocumentAnalysis\Modals\Report;
use App\Http\Livewire\Traits\HasProductItem;
use App\Models\Tenant\Company;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\ReportingPeriod;
use App\Models\Tenant\Tag;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use AuthorizesRequests;
    use HasProductItem;

    public Questionnaire | int $questionnaire;

    public $companiesList;

    public $countriesList;

    public $company;

    public $type;

    public $countries;

    public $typesList;

    public $userablesList;

    public $userablesId = [];

    public $taggableList;

    public $taggableIds = [];

    public $ownerUserList = [];

    public $createdByUserId;

    public $isOwner = false;

    public array $reportingPeriodList;

    public int|null $reporting_period_id;


    protected function rules()
    {
        return [
            'createdByUserId' => ['nullable', 'exists:users,id'],
            'company' => ['required', 'exists:companies,id'],
            'type' => ['required', 'exists:questionnaire_types,id'],
            'countries' => ['required'],
            'reporting_period_id' => ['required', 'exists:reporting_periods,id'],
        ];
    }

    /**
     * mount function
     */
    public function mount(?Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->resource = $questionnaire;

        $this->authorize(
            !$this->questionnaire->exists
                ? 'questionnaires.create'
                : "questionnaires.update.{$this->questionnaire->id}"
        );

        $companies = Company::list();
        $this->companiesList = parseDataForSelect($companies->get(), 'id', 'name');

        $types = QuestionnaireType::list()->get();
        $this->typesList = array_pluck($types, 'name', 'id');
        $this->countriesList = getCountriesForSelect();
        $this->userablesList = [];

        $this->taggableList = getTagsForSelect();

        $this->ownerUserList = [auth()->user()->pluck('id', 'name')];

        $this->reportingPeriodList = ReportingPeriod::questionnaireFormList();

        if ($this->questionnaire->exists) {
            $this->company = $this->questionnaire->company_id;
            $this->type = $this->questionnaire->questionnaire_type_id;
            $this->countries = $this->questionnaire->countries;

            $companyAssignedUsers = parseDataForSelect(
                Company::find($this->company)->users,
                'id',
                'name'
            );

            $this->userablesList = parseDataForSelect(
                Company::find($this->company)->users,
                'id',
                'name'
            );

            $this->ownerUserList = parseDataForSelect(
                Company::find($this->company)->users->push(['id' => auth()->user()->id, 'name' => auth()->user()->name]),
                'id',
                'name'
            );

            $this->userablesId = $this->questionnaire->users
                ? $this->questionnaire->users->pluck('id', null)->toArray()
                : [];

            $this->taggableIds = $this->questionnaire->tags
                ? $this->questionnaire->tags->pluck('id', null)->toArray()
                : [];

            $this->createdByUserId = $this->questionnaire->created_by_user_id;

            if (auth()->user()->id === $this->createdByUserId) {
                $this->isOwner = true;
            }
        }
    }

    /**
     * livewire render property hook
     */
    public function render(): View
    {
        if ($this->type) {
            $questionnaireType = QuestionnaireType::findOrFail($this->type);
            $this->product = $questionnaireType->getProduct();
        }

        return view('livewire.tenant.questionnaires.form');
    }

    /**
     * livewire save property hook
     */
    public function save()
    {
        $this->authorize(
            !$this->questionnaire->exists
                ? 'questionnaires.create'
                : "questionnaires.update.{$this->questionnaire->id}"
        );

        $data = $this->validate();

        $assigner = auth()->user();

        if ($data['createdByUserId']) {
            $data['created_by_user_id'] = $data['createdByUserId'];
        }

        if (!$this->questionnaire->exists) {
            $data['created_by_user_id'] = auth()->user()->id;
            $data['company_id'] = $data['company'];
            $data['questionnaire_type_id'] = $data['type'];

            $this->questionnaire = Questionnaire::create($data);

            $redirect = $this->questionnaire->questionnaireWelcome();
        } else {
            $this->questionnaire->update($data);
        }

        $this->questionnaire->assignUsers($this->userablesId, $assigner);
        $this->questionnaire->assignTags($this->taggableIds, $assigner);

        if (isset($redirect)) {
            return redirect($redirect);
        } else {
            return redirect(route('tenant.questionnaires.panel'));
        }
    }

    /**
     * livewire update company property hook
     */
    public function updatedCompany()
    {
        if (!empty($this->company)) {
            $this->userablesList = parseDataForSelect(
                Company::find($this->company)->users,
                'id',
                'name'
            );
        }
    }
}
