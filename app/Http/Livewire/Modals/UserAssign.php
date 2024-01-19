<?php

namespace App\Http\Livewire\Modals;

use App\Models\Tenant\Answer;
use Illuminate\Database\Eloquent\Model;
use LivewireUI\Modal\ModalComponent;

class UserAssign extends ModalComponent
{
    public Model $model;

    public $usersList;

    public $users = [];

    public null | string  $assignment_type = null;

    public string $modelType;

    public function mount(int $modelId, string $modelType, $assignment_type = null): void
    {
        $class = 'App\Models\Tenant\\' . ucfirst($modelType);
        $this->model = $class::find($modelId);
        $this->modelType = $class;
        $this->assignment_type = $assignment_type;
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->usersList = parseDataForSelect(
            $this->model->availableUsers(),
            'id',
            'name'
        );

        return view('livewire.tenant.modals.user-assign');
    }

    public function save(): void
    {
        $assigner = auth()->user();

        $usersIds = $this->model->availableUsers()->pluck('id')->intersect($this->users)->toArray();

        $this->model->assignUsers($usersIds ?? [], $assigner, $this->assignment_type);

        if ($this->modelType == Answer::class) {
            $this->updateUsers(count($usersIds), $this->assignment_type);
        } else {
            $this->updateUsers(count($usersIds));
        }

        $this->updateUsers(count($usersIds));

        $this->closeModal();
    }

    /**
     * Update the number of users assigned to the model
     */
    protected function updateUsers($number, $assignment_type = null): void
    {
        if (! $this->model) {
            $this->emit('usersChanged');
        } elseif ($this->modelType == Answer::class) {
            $this->emit(sprintf('usersChanged%s', $this->model->id), $number, $assignment_type);
        } else {
            $this->emit(sprintf('usersChanged%s', $this->model->id), $number);
        }
    }
}
