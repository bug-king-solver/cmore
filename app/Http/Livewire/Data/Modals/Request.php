<?php

namespace App\Http\Livewire\Data\Modals;

use App\Models\Tenant\Validator;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\Data;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Request extends ModalComponent
{
    use AuthorizesRequests;

    public Data | int $data;

    public $request_info;

    /**
     * Validaton rules
     */
    protected function rules()
    {
        return [
            'request_info' => ['required', 'string'],
        ];
    }

    /**
     * Mount data
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

        $this->authorize("data.request.{$this->data->id}");
    }

    /**
     * Modal width
     */
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    /**
     * Rander modal
     */
    public function render()
    {
        return view('livewire.tenant.data.request');
    }

    /**
     * save data
     */
    public function save()
    {
        $data = $this->validate();

        if ($this->viewer == 'validator') {
            $this->data->validator_requested = $this->request_info;
            $this->data->save();
        }
        if ($this->viewer == 'auditor') {
            $this->data->auditor_requested = $this->request_info;
            $this->data->save();
        }
        $this->emit('dataChanged');
        $this->closeModal();
    }
}
