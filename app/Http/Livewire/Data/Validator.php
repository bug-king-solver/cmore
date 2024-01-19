<?php

namespace App\Http\Livewire\Data;

use App\Models\Enums\Users\Type;
use App\Models\Tenant\Auditor as logAuditor;
use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Validator as logValidator;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Validator extends Component
{
    use AuthorizesRequests;

    public $userList;

    public $auditorList;

    public $typeList;

    public $frequencyList;

    public $validator;

    public $auditor;

    public $type;

    public $frequency;

    public $user;

    public $audit_user;

    public Company $company;

    public Indicator $indicator;

    public $validaterequire = false;

    public $auditor_require = false;

    /**
     * Validaton rules for field
     */
    protected function rules()
    {
        return [
            'user' => 'required|exists:users,id',
            'type' => 'required',
            'frequency' => 'required',
            'audit_user' => 'required|exists:users,id',
        ];
    }

    /**
     * Mount field values
     */
    public function mount(Indicator $indicator, Company $company)
    {
        $this->company = $company;
        $this->indicator = $indicator;

        $this->validator = logValidator::where('indicator_id', $this->indicator->id)
            ->where('company_id', $this->company->id)->first();

        $this->auditor = logAuditor::where('indicator_id', $this->indicator->id)
            ->where('company_id', $this->company->id)->first();

        $this->userList = parseDataForSelect(User::list()->where('type', Type::INTERNAL)->get(), 'id', 'name');

        $this->auditorList = parseDataForSelect(User::list()->where('type', Type::EXTERNAL)->get(), 'id', 'name');

        $this->typeList = ['consolidated' => 'Consolidated'];
        $this->frequencyList = ['quarterly' => 'Quarterly'];

        if ($this->validator) {
            $this->validaterequire = ($this->validator->status == '0' ? false : true);
            $this->type = $this->validator->type;
            $this->frequency = $this->validator->frequency;
            $this->user = $this->validator->users
                ? $this->validator->users->pluck('id', null)->toArray()
                : [];
        }

        if ($this->auditor) {
            $this->auditor_require = ($this->auditor->status == '0' ? false : true);
            $this->type = $this->auditor->type;
            $this->frequency = $this->auditor->frequency;
            $this->audit_user = $this->auditor->users
                ? $this->auditor->users->pluck('id', null)->toArray()
                : [];
        }
    }

    /**
     * Rander form
     */
    public function render(): View
    {
        return view('livewire.tenant.data.validator');
    }

    /**
     * Add / updat Data
     */
    public function save()
    {
        if ($this->validaterequire == true) {
            $dataValidate = $this->validate([
                'type' => 'required',
                'frequency' => 'required',
                'user' => 'required|exists:users,id',
            ]);
        }
        if ($this->auditor_require == true) {
            $dataAuditor = $this->validate([
                'audit_user' => 'required|exists:users,id',
            ]);
        }

        $data = array_merge($dataValidate ?? [], $dataAuditor ?? []);

        if ($data == null) {
            return redirect(route('tenant.data.indicators.show', ['indicator' => $this->indicator->id]));
        }

        if ($this->validaterequire == true) {
            $updateorcreate = logValidator::updateOrCreate(
                ['indicator_id' => $this->indicator->id, 'company_id' => $this->company->id],
                ['type' => $data['type'], 'frequency' => $data['frequency'], 'status' => $this->validaterequire]
            );
            $this->validator = $updateorcreate;
        } else {
            if ($this->validator) {
                $this->validator = logValidator::updateOrCreate(
                    ['indicator_id' => $this->indicator->id, 'company_id' => $this->company->id],
                    ['status' => 0]
                );
            }
        }

        if ($this->auditor_require == true) {
            $updateorcreate = logAuditor::updateOrCreate(
                ['indicator_id' => $this->indicator->id, 'company_id' => $this->company->id],
                ['type' => $data['type'], 'frequency' => $data['frequency'], 'status' => $this->auditor_require]
            );

            $this->auditor = $updateorcreate;
        } else {
            if ($this->auditor) {
                $this->auditor = logAuditor::updateOrCreate(
                    ['indicator_id' => $this->indicator->id, 'company_id' => $this->company->id],
                    ['status' => 0]
                );
            }
        }

        if ($this->auditor_require == true && $this->auditor != null) {
            $audit_assigner = auth()->user();
            $this->auditor->assignUsers($this->audit_user ?? [], $audit_assigner);
        }

        $assigner = auth()->user();
        $this->validator->assignUsers($this->user, $assigner);

        return redirect(route('tenant.data.indicators.show', ['indicator' => $this->indicator->id]));
    }
}
