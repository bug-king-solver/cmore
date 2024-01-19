<?php

namespace App\Http\Livewire\Tasks;

use App\Events\Tasks\CreatedTaskEvent;
use App\Events\Tasks\UpdatedTaskEvent;
use App\Http\Livewire\Traits\HasCustomColumns;
use App\Http\Livewire\Traits\TasksTrait;
use App\Models\Tenant\Task;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Form extends Component
{
    use AuthorizesRequests;
    use HasCustomColumns;
    use WithPagination;
    use TasksTrait;

    protected $feature = 'tasks';

    public Task | int $task;

    public $name;

    public $description;

    public $weight;

    public $due_date;

    public $userablesList = [];

    public $userablesId = [];

    public $taggableList;

    public $taggableIds = [];

    protected function rules()
    {
        $date = $this->task->exists ? $this->task->created_at->addDays(1)->format('Y-m-d') : 'tomorrow';
        return $this->mergeCustomRules([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'weight' => ['required', 'integer', 'max:255', 'gt:0'],
            'due_date' => ['required', 'date', 'max:255', 'after_or_equal:' . $date],
            'entity' => ['required'],
            'taskableId' => ['required'],
        ]);
    }

    protected function getMessages()
    {
        return [
            'name.required' => __('The name field is required.'),
            'description.required' => __('The description field is required.'),
            'weight.required' => __('The weight field is required.'),
            'due_date.required' => __('The due date field is required.'),
            'entity.required' => __('The associable field is required.'),
            'taskableId.required' => __('The taskable field is required.'),
        ];
    }

    public function mount(?Task $task)
    {
        $this->entity = request()->entity ?? '';
        $this->entityId = request()->entityId ?? null;

        $this->task = $task;

        // $this->authorize(!$this->task->exists  ? 'task.create' : "task.update.{$this->task->id}");

        $users = User::list(auth()->user()->id)->get();

        $this->userablesList = parseDataForSelect($users, 'id', 'name', 'avatar');
        $this->taggableList = getTagsForSelect();

        $this->entitiesModelList = [
            [
                'id' => 'targets',
                'title' => __('Targets'),
            ],
            [
                'id' => 'questionnaires',
                'title' => __('Questionnaires'),
            ],
            [
                'id' => 'companies',
                'title' => __('Companies'),
            ],
        ];

        if ($this->task->exists) {
            $this->name = $this->task->name;
            $this->description = $this->task->description;
            $this->weight = $this->task->weight;
            $this->due_date = $this->task->due_date->format('Y-m-d');

            $this->userablesId = $this->task->users
                ? $this->task->users->pluck('id', null)->toArray()
                : [];
            $this->taggableIds = $this->task->tags
                ? $this->task->tags->pluck('id', null)->toArray()
                : [];

            $this->taskCheckList = $this->task->checklist
                ? $this->task->checklist->pluck('name', 'id')->toArray()
                : [];

            /** add an Empty row */
            $this->addNewChecklistOptions();

            if ($this->task->targets()->exists()) {
                $this->entity = 'targets';
                $this->taskableId = $this->task->targets->pluck('id', null)->toArray();
                $this->taskableValue = $this->task->targets->first()->title;
            } elseif ($this->task->questionnaires()->exists()) {
                $this->entity = 'questionnaires';
                $this->taskableId = $this->task->questionnaires->pluck('id', null)->toArray();
                $this->taskableValue = $this->task->questionnaires->first()->id;
            } elseif ($this->task->companies()->exists()) {
                $this->entity = 'companies';
                $this->taskableId = $this->task->companies->pluck('id', null)->toArray();
                $this->taskableValue = $this->task->companies->first()->name;
            }

            if ($this->entity != '') {
                $this->updatedEntity($this->entity);
            }
        } else {
            if ($this->entity != '') {
                $this->taskableId = $this->entityId;
                $this->updatedEntity($this->entity);
            }

            $this->addNewChecklistOptions();
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.tasks.form');
    }

    public function save()
    {
        // $this->authorize(!$this->task->exists  ? 'tasks.create' : "tasks.update.{$this->task->id}");
        $data = $this->validate(
            $this->getRules(),
            $this->getMessages(),
        );

        $assigner = auth()->user();

        if (!$this->task->exists) {
            $data['created_by_user_id'] = auth()->user()->id;

            $this->task = Task::create($data);

            $resource = makeResourcAble($this->entity);
            $model = new $resource();
            $model::find(
                is_array($this->taskableId) ? reset($this->taskableId) : $this->taskableId
            )
                ->tasks()
                ->save($this->task);

            if ($this->task) {
                foreach ($this->taskCheckList as $key => $value) {
                    if ($value != '') {
                        $this->task->checklist()->create([
                            'name' => $value,
                            'completed' => false,
                        ]);
                    }
                }
            }

            if ($this->entityFromComponent) {
                if (\preg_match('/target/', $this->entity)) {
                    $this->emitTo('targets.show', 'targetsChanged');
                } elseif (\preg_match('/questionnaire/', $this->entity)) {
                    /**
                     * Todo:
                     * 1. emit to the questionnaire component
                     */
                    // $this->emitTo('questionnaires.show', 'questionnairesChanged');
                }
            }
        } else {
            $this->task->update($data);

            if ($this->task->taskables != null) {
                $this->task->taskables->delete();
            }

            $resource = makeResourcAble($this->entity);
            $model = new $resource();
            $model::find(
                is_array($this->taskableId) ? reset($this->taskableId) : $this->taskableId
            )
                ->tasks()
                ->save($this->task);

            $currentChecklists = $this->task->checklist()->get()->pluck(null, 'id')->toArray();
            $checklistDeleted = array_diff_key($currentChecklists, $this->taskCheckList);

            foreach ($checklistDeleted as $key => $value) {
                $check = $this->task->checklist()->find($value['id'] ?? null);
                if ($check) {
                    $check->delete();
                }
            }

            foreach ($this->taskCheckList as $key => $value) {
                if ($value != '') {
                    $this->task->checklist()->updateOrCreate(
                        [
                            'id' => $key,
                        ],
                        [
                            'name' => $value,
                        ]
                    );
                }
            }
        }

        $this->task->assignUsers($this->userablesId, $assigner);
        $this->task->assignTags($this->taggableIds, $assigner);

        $this->emit('taskSaved');

        $this->fireResourcesEvents();

        return redirect(route('tenant.users.tasks.index'));
    }
}
