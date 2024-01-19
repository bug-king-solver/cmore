<?php

namespace App\Http\Livewire\Data;

use App\Events\CreatedData;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\Data;
use App\Models\Tenant\ReportingPeriod;
use App\Models\Tenant\Validator;
use App\Models\User;
use App\Rules\AttachmentName;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;
    use BreadcrumbsTrait;

    public $userList;

    public $user;

    public $origin;

    public $upload;

    public Data | int $data;

    public $validator;

    public $auditor;

    public $company;

    public $indicator;

    public $value;

    /** @var array<string, string> $reportingPeriodList */
    public array $reportingPeriodList;

    /** @var int|null $reporting_period_id */
    public int|null $reporting_period_id;


    /**
     * Validaton rules for field
     */
    protected function rules()
    {
        return [
            'user' => 'required|exists:users,id',
            'origin' => 'required',
            'value' => 'required|numeric',
            'reporting_period_id' => ['required', 'exists:reporting_periods,id'],
            'upload.*' => [
                'file',
                'max:10000',
                'mimes:pdf,csv,txt,xls,xlsx,jpg,jpeg,png',
                new AttachmentName(),
            ]
        ];
    }

    /**
     * Validaton message
     */
    public function messages()
    {
        return [
            'value.numeric' => __('This field only accepts numbers and a dot as decimal separator'),
        ];
    }

    /**
     * Mount field values
     */
    public function mount($company, $indicator, ?Data $data)
    {
        $this->company = $company;
        $this->indicator = $indicator;
        $this->data = $data;

        $this->reportingPeriodList = ReportingPeriod::monitoringFormList();

        $this->validator = Validator::where('indicator_id', $this->indicator)
            ->where('company_id', $this->company)->first();

        $this->auditor = Auditor::where('indicator_id', $this->indicator)
            ->where('company_id', $this->company)->first();

        $this->authorize(!$this->data->exists ? 'data.create' : "data.update.{$this->data->id}");

        $this->userList = array_pluck(User::list()->get(), 'name', 'id');


        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Monitoring'));
        $this->addBreadcrumb(__('Indicators'), route('tenant.data.index'));
        if ($this->data->exists) {

            $this->addBreadcrumb($data->indicator->name);
            $this->user = $this->data->user_id;
            $this->company = $this->data->company_id;
            $this->indicator = $this->data->indicator_id;
            $this->value = $this->data->value;
            $this->origin = $this->data->origin;
        }
    }

    /**
     * Rander form
     */
    public function render(): View
    {
        return view('livewire.tenant.data.form');
    }

    /**
     * Add / updat Data
     */
    public function save()
    {
        $this->authorize(!$this->data->exists ? 'data.create' : "data.update.{$this->data->id}");

        $data = $this->validate();

        $data['user_id'] = $data['user'];
        $data['company_id'] = $this->company;
        $data['indicator_id'] = $this->indicator;

        if ($this->validator != null && $this->validator->status == 0) {
            // If validator status is 0 means no need to validate this log by validator.
            $data['validator_status'] = 1;
        }

        if ($this->auditor != null && $this->auditor->status == 0) {
            // If auditor status is 0 means no need to validate this log by auditor.
            $data['auditor_status'] = 1;
        }

        if (!$this->data->exists) {
            $this->data = Data::create($data);

            if ($this->validator != null) {
                foreach ($this->validator->users as $row) {
                    event(new CreatedData($row, $this->data, auth()->user()));
                }
            }

            if ($this->auditor != null) {
                foreach ($this->auditor->users as $row) {
                    event(new CreatedData($row, $this->data, auth()->user()));
                }
            }
        } else {
            $this->data->update($data);
        }

        // TODO : throw error (.) DOT can not create directory

        // foreach ($this->upload as $file) {
        //     $libraryName = config('media-library.collection.attachments');

        //     $uploadedMedia = auth()->user()->addMedia($file->getRealPath())
        //         ->usingName($file->getClientOriginalName())
        //         ->withCustomProperties(
        //             [
        //                 'created_by' => auth()->user()->id,
        //             ]
        //         )
        //         ->toMediaCollection($libraryName, 'attachments');

        //     Data::attach($uploadedMedia->id);
        // }

        return redirect(route('tenant.data.indicators.show', ['indicator' => $this->indicator]));
    }
}
