<?php

namespace App\Http\Livewire\Users;

use App\Events\EnabledUser;
use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    protected $listeners = [
        'assignChanged' => '$refresh',
    ];

    public $model;

    /**
     * Mount the component.
     */
    public function mount()
    {
        parent::initFilters($model = User::class);
        $this->model = new User();

        $this->addBreadcrumb(__('Users'));
    }

    public function getUsersProperty()
    {
        return $this->search(User::list(auth()->user()->id))
            ->paginate($this->selectedItemsPerPage)
            ->withQueryString();
    }

    public function render(): View
    {
        return view('livewire.tenant.users.index');
    }

    protected function enableDisableValidate($status)
    {
        return Validator::make(
            ['enabled' => $status],
            ['enabled' => 'required|boolean'],
        )->validate();
    }

    public function enabledDisable(User $user, $status)
    {
        $data = $this->enableDisableValidate($status);

        $user->update($data);

        if ($data['enabled']) {
            event(new EnabledUser($user));
        }

        return response()->noContent();
    }
}
