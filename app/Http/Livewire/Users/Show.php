<?php

namespace App\Http\Livewire\Users;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Company;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
class Show extends FilterBarComponent
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;
    use WithPagination;

    public int|User $user;

    public $tab = 'assigned';

    public $type = 1;

    public $typeList;

    public $model;

    public function mount(int $user)
    {
        $this->user = User::where('id', $user)->with('tags')->firstOrFail();
        $this->authorize("users.view.{$this->user->id}");

        $this->typeList = [
            '1' => __('Company'),
            '2' => __('Questionnaire'),
            '3' => __('Target'),
        ];

        $this->filters();

        $this->addBreadcrumb(__('Users'), route('tenant.users.index'));
        $this->addBreadcrumb($this->user->name);
    }

    public function render(): View
    {
        $companys = (($this->type == 1)
            ? $this->search($this->user->companies())
            : $this->user->companies())
            ->with('business_sector')
            ->paginate(4, ['*'], 'company');

        $questionnaires =  (($this->type == 2)
            ? $this->search($this->user->questionnaires())
            : $this->user->questionnaires())
            ->with('company')
            ->with('type')
            ->paginate(4, ['*'], 'questionnaire');

        $targets = (($this->type == 3)
            ? $this->search($this->user->targets())
            : $this->user->targets())
            ->with('tasks')
            ->with('indicator')
            ->with('users')
            ->paginate(4, ['*'], 'target');

        $date = Carbon::now()->subDays(7);
        $activities = Activity::causedBy($this->user)
            ->orderBy('created_at', 'DESC')
            ->Where('created_at', '>=', $date)
            ->paginate(10, ['*'], 'activitie');

        $countries = [];
        $companys->map(function ($company) use (&$countries) {
            if ($company->country) {
                $countries[] = $company->country;
            }

            if ($company->vat_country) {
                $countries[] = $company->vat_country;
            }
        });

        if ($countries) {
            $countries = array_unique($countries);
            $countries = getCountriesWhereIn($countries);
        }

        return view(
            'livewire.tenant.users.show',
            [
                'user' => $this->user,
                'companies' => $companys,
                'countries' => $countries,
                'questionnaires' => $questionnaires,
                'targets' => $targets,
                'activities' => $activities,
            ]
        );
    }

    public function tab($value)
    {
        $this->tab = $value;
    }

    public function updatedType()
    {
        $this->filters();
    }

    public function filters() {
        $this->availableFilters = []; // reset

        switch($this->type) {
            case(1):
                $this->model = new Company();
                break;
            case(2):
                $this->model = new Questionnaire();
                break;
            case(3):
                $this->model = new Target();
                break;
            default:
                $this->type = 1;
                $this->model = new Company();
        }
        parent::initFilters($this->model);
    }
}
