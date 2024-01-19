<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\Traits\DynamicReportsTrait;
use App\Models\Tenant\Dashboard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;
    use DynamicReportsTrait;

    public Dashboard $dashboard;
    public $name;
    public $description;
    public $layout = [];
    public $row = [];
    public $search;

    /**
     * Get the listeners for the component.
     */
    protected function getListeners()
    {
        return [
            'refresh' => '$refresh',
        ];
    }

    /**
     * Get the validation rules that apply to the component's properties.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'layout' => ''
        ];
    }

    /**
     * Mount the component.
     *
     * @param Dashboard $dashboard The dashboard to be edited.
     * @return void
     */
    public function mount(Dashboard $dashboard): void
    {
        $this->dashboard = $dashboard;
        $this->name = $this->dashboard->name;
        $this->description = $this->dashboard->description;
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->layout = parseStringToArray($this->dashboard->layout);
        $this->mountDynamicReportsTrait($this->search);
        return view('livewire.tenant.dynamic-dashboard.form')->layoutData(['mainBgColor' => 'bg-esg4']);
    }

    /**
     * Save the changes made to the dashboard.
     */
    public function save(): void
    {
        $data = $this->validate();

        $this->dashboard->name = $data["name"];
        $this->dashboard->description = $data["description"];

        // loop $this->layout as row , loop as col , if has structure , parseStringToArray
        foreach ($this->layout as $rowIndex => $row) {
            foreach ($row as $colIndex => $col) {
                if (isset($col['structure'])) {
                    $this->layout[$rowIndex][$colIndex]['structure'] = parseStringToArray($col['structure']);
                }
            }
        }

        $this->dashboard->layout = gettype($this->layout) !== 'string'
            ? json_encode($this->layout)
            : $this->layout;

        $this->dashboard->save();
        session()->flash('success', __('Dynamic dashboard updated') . '.');
    }

    /**
     * Redirects the user to the dynamic dashboard index page.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        return redirect()->to(route('tenant.dynamic-dashboard.index'));
    }
}
