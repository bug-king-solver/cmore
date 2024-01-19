<?php

namespace App\Http\Livewire\GarBtar;

use App\Models\Tenant\Company;
use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class Import extends ModalComponent
{

    use WithFileUploads;

    use AuthorizesRequests;

    public bool $confirm;

    public $file;

    public function rules()
    {
        return [
            'confirm' => 'accepted',
            'file' => 'file|required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'confirm.accepted' => __('You need confirm to replace the current data'),
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.tenant.garbtar.import');
    }

    public function save()
    {
        $data = $this->validate();
        $tmpPath = $data['file']->getRealPath();
        BankAssets::importFile($tmpPath);
        $this->closeModal();
        $this->emit('companyRefresh');
    }
}
