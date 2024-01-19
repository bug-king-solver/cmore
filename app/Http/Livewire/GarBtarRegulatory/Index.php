<?php

namespace App\Http\Livewire\GarBtarRegulatory;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use BreadcrumbsTrait;

    public $listTables;

    public function mount()
    {
        $this->addBreadcrumb(__('Regulation'));
        $this->addBreadcrumb(__('Regulatory Tables'));
    }

    public function render(): View
    {
        $this->listTables = [
            // [
            //     'title' => __('Banking Book - Climate Change transition risk: Credit quality of exposures by sector, emissions and residual maturity'),
            //     'link' => '#',
            //     'regulation' => __('Article 449.º-A of CRR'),
            // ],
            // [
            //     'title' => __('Banking Book - Climate Change transition risk: Alignment metrics'),
            //     'link' => '#',
            //     'regulation' => __('Article 449.º-A of CRR'),
            // ],
            // [
            //     'title' => __('Banking Book - Climate Change transition risk: Exposures subject to physical risk'),
            //     'link' => '#',
            //     'regulation' => __('Article 449.º-A of CRR'),
            // ],
            [
                'title' => __('Summary of GAR Key Performance Indicators'),
                'link' => route('tenant.garbtar.regulatory.summarygar'),
                'regulation' => __('Article 449.º-A of CRR'),
            ],
            [
                'title' => __('Mitigating actions: Assets for the calculation of GAR'),
                'link' => route('tenant.garbtar.regulatory.garassetsmitigate'),
                'regulation' => __('Article 449.º-A of CRR'),
            ],
            [
                'title' => __('Mitigating actions: % for the calculation of GAR'),
                'link' => route('tenant.garbtar.regulatory.garpercetagetable'),
                'regulation' => __('Article 449.º-A of CRR'),
            ],
            [
                'title' => __('Mitigating actions: Assets for the calculation of BTAR'),
                'link' => route('tenant.garbtar.regulatory.btarassetsmitigate'),
                'regulation' => __('Article 449.º-A of CRR'),
            ],
            // [
            //     'title' => __('Assets for the calculation of GAR'),
            //     'link' => '#',
            //     'regulation' => __('Taxonomy Delegated Act'),
            // ],
            // [
            //     'title' => __('GAR sector information'),
            //     'link' => '#',
            //     'regulation' => __('Taxonomy Delegated Act'),
            // ],
            // [
            //     'title' => __('GAR KPI Stock'),
            //     'link' => '#',
            //     'regulation' => __('Taxonomy Delegated Act'),
            // ],
            // [
            //     'title' => __('GAR KPI flow'),
            //     'link' => '#',
            //     'regulation' => __('Taxonomy Delegated Act'),
            // ],
            // [
            //     'title' => __('KPI off-balance sheet exposures'),
            //     'link' => '#',
            //     'regulation' => __('Taxonomy Delegated Act'),
            // ],
        ];
        return view('livewire.tenant.garbtarregulatory.index');
    }
}
