<?php

namespace App\Http\Livewire\Targets;

use App\Http\Livewire\Traits\GroupsManagerTrait;
use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Target;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use AuthorizesRequests;
    use GroupsManagerTrait;

    public ?Target $targetOld;

    public Target | int $target;

    public $companiesList;

    public $usersList;

    public $indicatorsList;

    public $company;

    public $user;

    public $indicator;

    public $our_reference;

    public $title;

    public $description;

    public $goal;

    public $due_date;

    public $userablesList = [];

    public $userablesId = [];

    public $taggableList;

    public $taggableIds = [];

    public $start_date;

    public $ownerUserList = [];

    public $createdByUserId;

    public $isOwner = false;

    public $group = 0;

    protected function rules()
    {
        return [
            'company' => ['required', 'exists:companies,id'],
            'user' => ['nullable', 'exists:users,id'],
            'indicator' => ['required', 'exists:indicators,id'],
            'our_reference' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'goal' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'start_date' => ['nullable', 'date'],
            'userablesId' => ['nullable', 'array'],
            'createdByUserId' => ['nullable', 'exists:users,id'],
        ];
    }

    protected function getMessages()
    {
        return [
            'company.required' => __('The company field is required.'),
            'company.exists' => __('The selected company is invalid.'),
            'user.exists' => __('The selected user is invalid.'),
            'indicator.required' => __('The indicator field is required.'),
            'indicator.exists' => __('The selected indicator is invalid.'),
            'title.required' => __('The title field is required.'),
            'title.string' => __('The title field must be a string.'),
            'title.max' => __('The title field must not be greater than 255 characters.'),
            'description.required' => __('The description field is required.'),
            'description.string' => __('The description field must be a string.'),
            'goal.required' => __('The goal field is required.'),
            'goal.string' => __('The goal field must be a string.'),
            'goal.max' => __('The goal field must not be greater than 255 characters.'),
            'due_date.required' => __('The due date field is required.'),
            'due_date.date' => __('The due date field must be a date.'),
            'start_date.date' => __('The start date field must be a date.'),
            'userablesId.array' => __('The user field must be an array.'),
        ];
    }

    public function mount(?Target $target)
    {
        $this->group = request()->query('group') ?? 0;
        $this->targetOld = $target;
        $this->target = $target;

        $this->authorize(
            ! $this->target->exists ? 'targets.create' : "targets.update.{$this->target->id}"
        );

        $companies = Company::list();

        $this->companiesList = parseDataForSelect($companies->get(), 'id', 'name');
        // TODO :: Add local enabled scope
        $this->indicatorsList = Indicator::list()->whereEnabled(true)->limit(50)->get();

        $this->userablesList = [];
        $this->taggableList = getTagsForSelect();

        $users = User::list(
            $this->target->exists ? $this->target->created_by_user_id : auth()->user()->id
        )->get();

        $this->ownerUserList = parseDataForSelect($users, 'id', 'name');

        if ($this->target->exists) {
            $this->company = $this->target->company_id;
            $this->user = $this->target->user_id;
            $this->indicator = $this->target->indicator_id;
            $this->our_reference = $this->target->our_reference;
            $this->title = $this->target->title;
            $this->description = $this->target->description;
            $this->goal = $this->target->goal;
            $this->due_date = $this->target->due_date->format('Y-m-d');
            $this->start_date = $this->target->start_date ? $this->target->start_date->format('Y-m-d') : null;

            $this->userablesList = parseDataForSelect(
                Company::find($this->company)->users,
                'id',
                'name'
            );

            $this->userablesId = $this->target->users ? $this->target->users->pluck('id', null)->toArray() : [];
            $this->taggableIds = $this->target->tags ? $this->target->tags->pluck('id', null)->toArray() : [];
            $this->createdByUserId = $this->target->created_by_user_id;
            if (auth()->user()->id === $this->createdByUserId) {
                $this->isOwner = true;
            }
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.targets.form');
    }

    public function save()
    {
        $this->authorize(! $this->target->exists ? 'targets.create' : "targets.update.{$this->target->id}");

        $data = $this->validate(
            $this->getRules(),
            $this->getMessages()
        );

        $data['company_id'] = $data['company'];
        $data['user_id'] = $data['user'] ?: null;
        $data['indicator_id'] = $data['indicator'];

        $assigner = auth()->user();

        if ($data['createdByUserId']) {
            $data['created_by_user_id'] = $data['createdByUserId'];
        }

        if (! $this->target->exists) {
            $data['created_by_user_id'] = auth()->user()->id;
            $this->target = Target::create($data);
        } else {
            $this->target->update($data);
        }

        $this->target->assignUsers($data['userablesId'], $assigner);
        $this->target->assignTags($this->taggableIds, $assigner);

        
        if ($this->group) {
            $this->target->saveResourceInGroup($this->target, $this->group);
        }

        return redirect(route('tenant.targets.index'));
    }

    public function updatedCompany()
    {
        $this->userablesList = [];
        $this->userablesId = [];
        if (! empty($this->company)) {
            $this->userablesList = parseDataForSelect(
                Company::find($this->company)->users,
                'id',
                'name'
            );
        }
    }
}
