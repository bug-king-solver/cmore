<?php

namespace App\Http\Livewire\Tasks;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\Task;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    public $filter = 'todo';
    public $todoCount;
    public $doneCount;
    public $showCountTodo = 8;
    public $showCountDone = 8;
    public $moreTasksAvailableTodo;
    public $moreTasksAvailableDone;

    protected $listeners = [
        'taskSaved' => 'refreshList',
    ];

    public function mount()
    {
        $this->name = '';
        parent::initFilters($model = Task::class);
        $this->model = new Task();
        $this->calculateTaskCounts();

        $this->addBreadcrumb(__('Tasks'));
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function calculateTaskCounts()
    {
        $this->todoCount = $this->model->where('completed', false)->count();
        $this->doneCount = $this->model->where('completed', true)->count();
    }

    public function loadMoreTodo()
    {
        $this->showCountTodo += 8;
    }

    public function loadMoreDone()
    {
        $this->showCountDone += 8;
    }

    public function refreshList()
    {
        $this->calculateTaskCounts();
        $this->setFilter($this->filter);
    }

    public function render(): View
    {
        $this->moreTasksAvailableTodo = $this->showCountTodo < $this->todoCount;
        $this->moreTasksAvailableDone = $this->showCountDone < $this->doneCount;

        $query = $this->search($this->model->list())->with('users');

        if ($this->filter === 'todo') {
            $query->where('completed', false)->limit($this->showCountTodo);
        } elseif ($this->filter === 'done') {
            $query->where('completed', true)->limit($this->showCountDone);
        }

        $tasks = $query->get();

        return view('livewire.tenant.tasks.index', [
            'tasks' => $tasks,
        ]);
    }
}
