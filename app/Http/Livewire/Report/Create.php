<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\Traits\DynamicReportsTrait;
use App\Models\Tenant\Dashboard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;
    use DynamicReportsTrait;

    public Dashboard $dashboard;
    public $name;
    public $description;
    public $layout = [];
    public $search;

    /**
     * Returns an array of validation rules for the report creation form.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'layout' => 'required'
        ];
    }


    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->mountDynamicReportsTrait($this->search);
        return view(
            'livewire.tenant.dynamic-dashboard.form'
        )->layoutData(
            [
                'mainBgColor' => 'bg-esg4',
            ]
        );
    }

    /**
     * Saves the report to the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $data = $this->validate();
        $data['layout'] = json_encode($data['layout']);
        Dashboard::create($data);

        return redirect()->to(route('tenant.dynamic-dashboard.index'));
    }

    /**
     * Redirects the user to the dynamic dashboard index page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        return redirect()->to(route('tenant.dynamic-dashboard.index'));
    }
}
