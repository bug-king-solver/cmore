<?php

namespace App\Http\Livewire\Traits;

trait TasksTrait
{
    public null|int $entityId = null;

    public string $entity = '';

    public $entityFromComponent = false;

    public $entitiesModelList;

    public array $entitiesList = [];

    public array $taskCheckList = [];

    public $taskableId = [];


    public function bootTasksTrait()
    {
        $this->listeners += [
            'taskModalOpened' => 'taskModalOpened',
            'saveTask' => 'saveTask',
            'addNewChecklistOptions' => 'addNewChecklistOptions',
            'removeChecklistOptions' => 'removeChecklistOptions',
        ];
    }

    public function saveTask()
    {
        $this->emitTo('tasks.modals.form', 'save');
    }

    public function taskModalOpened(string $value, $id = null)
    {
        $this->entity = $value;
        $this->entityId = $id;
        $this->setEntityFromTable($value, $id);
    }

    public function setEntityFromTable(string $entity, int|null $entityId)
    {
        if ($entity) {
            $this->entity = $entity;
            $this->entityFromComponent = true;
            $this->updatedEntity($this->entity);
        }

        if ($entity && $entityId) {
            $arr = collect($this->entitiesList)->filter(function ($value) use ($entityId) {
                return $value['id'] == $entityId;
            })->values()->first();

            $this->taskableId = $arr['id'] ?? '';
        }

        $this->emit('openModal', 'tasks.modals.form', [
            'entity' => $this->entity,
            'entityId' => $this->entityId,
            'entitiesList' => $this->entitiesList,
            'taskableId' => $this->taskableId,
            'entityFromComponent' => $this->entityFromComponent,
        ]);
    }

    /**
     * Update the entity.
     * @param $value
     */
    public function updatedEntity($value)
    {
        $this->entitiesList = [];
        if ($value != null) {
            $resource = makeResourcAble($value);
            if (class_exists($resource)) {
                $model = new $resource();
                $this->entitiesList = parseDataForSelect(
                    $model::list()->orderBy('id')->get(),
                    'id',
                    'title'
                );
            }
        }
    }

    public function addNewChecklistOptions()
    {
        $checklists = $this->taskCheckList;
        end($checklists);
        $key = key($checklists);
        $this->taskCheckList[$key + 1] = '';
    }

    public function removeChecklistOptions($key)
    {
        unset($this->taskCheckList[$key]);
    }

    public function calcProgress($checklist)
    {
        if ($this->task->completed) {
            return 100;
        }

        $total = $checklist->count();
        $completed = $checklist->where('completed', true)->count();

        return $total > 0 ? round($completed / $total * 100) : 0;
    }

    public function fireResourcesEvents()
    {
        $this->emit('targetsChanged');
    }
}
