<?php

namespace App\Http\Livewire\Data;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\Data;
use App\Models\Tenant\Validator;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Show extends Component
{
    use BreadcrumbsTrait;
    
    public Data | int $data;

    public $validator;

    public $auditor;

    public $viewer = 'creator';

    protected $listeners = [
        'dataChanged' => '$refresh',
    ];

    /**
     * Mount the component.
     */
    public function mount(Data $data)
    {
        $this->data = $data;

        $this->data = Data::where('id', $this->data->id)
            ->with('indicator')
            ->with('company')
            ->with('user')
            ->first();

        $this->validator = Validator::where('indicator_id', $this->data->indicator_id)
            ->where('company_id', $this->data->company_id)
            ->first();

        $this->auditor = Auditor::where('indicator_id', $this->data->indicator_id)
            ->where('company_id', $this->data->company_id)
            ->first();

        if ($this->validator != null) {
            array_map(function ($user) {
                if ($user['id'] == auth()->user()->id) {
                    $this->viewer = 'validator';
                }
            }, $this->validator->users->toArray());
        }

        if ($this->auditor != null) {
            array_map(function ($user) {
                if ($user['id'] == auth()->user()->id) {
                    $this->viewer = 'auditor';
                }
            }, $this->auditor->users->toArray());
        }
        
        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Monitoring'));
        $this->addBreadcrumb(__('Indicators'), route('tenant.data.index'));
        $this->addBreadcrumb($data->indicator->name);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $activitys = Activity::forSubject($this->data)
            ->paginate(10, ['*'], 'activitie');

        return view('livewire.tenant.data.viewdata', [
            'activitys' => $activitys
        ]);
    }

    /**
     * update validator status
     */
    public function validatorStatusUpdate($status)
    {
        $this->data->validator_status = ($status == 1 ? 0 : 1);
        $this->data->save();

        $this->validator->status = $this->validator->status == 1 ? 0 : 1;
        $this->validator->save();

        $this->emit('dataChanged');
    }

    /**
     * update auditor status
     */
    public function auditorStatusUpdate($status)
    {
        $this->data->auditor_status = ($status == 1 ? 0 : 1);
        $this->data->save();

        $this->auditor->status = $this->auditor->status == 1 ? 0 : 1;
        $this->auditor->save();

        $this->emit('dataChanged');
    }
}
