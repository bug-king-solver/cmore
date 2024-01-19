<?php

namespace App\Http\Livewire\GarBtarAssets;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Enums\Regulamentation\EntityTypeEnum;
use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    public bool $isSearchable = false;

    public $assetTypeList;

    public $entityTypeList;

    public $rowActive;

    public $model;

    private $assets = [];

    public $total;
    public $assetBtarPer;
    public $assetExcludedPer;
    public $assetBtar;
    public $assetExcluded;
    public $specializedCredit;
    public $nonSpecializedCredit;
    public $realEstate;
    public $families;
    public $publicSector;
    public $companies;
    public $companiesDetailed;
    public $garType;
    public $garTypeNoData;
    public $btarType;
    public $btarTypeNoData;
    public $subjectNFRD;
    public $subjectNFRDDetailed;
    public $subjectNFRDEU;
    public $subjectNFRDNonEU;
    public $subjectNFRDWithData;
    public $subjectNFRDWithoutData;
    public $subjectNFRDEUWithData;
    public $subjectNFRDEUWithoutData;
    public $subjectNFRDNonEUWithData;
    public $subjectNFRDNonEUWithoutData;
    public $excludedNumerator;
    public $excludedNumeratorDetailed;
    public $assetType;
    public $assets_with_taxonomy;
    public $assets_without_taxonomy;
    public $assetWithDataGAR;
    public $assetWithoutDataGAR;
    public $assetWithDataBTAR;
    public $assetWithoutDataBTAR;

    protected $listeners = [
        'updateRow' => '$refresh'
    ];

    /**
     * Mount the component.
     */
    public function mount()
    {
        parent::initFilters($model = BankAssets::class);
        $this->model = new BankAssets();
        $this->assetTypeList = AssetTypeEnum::toArray();
        $this->entityTypeList = EntityTypeEnum::toArray();
        $this->assets = [];

        $nace = request()->query('nace');
        if (isset($nace)) {
            $this->updateFiltersValues('nace_filter', explode(',', $nace));
        }

        $entity = request()->query('entity');
        if (isset($entity)) {
            $this->updateFiltersValues('entity_type_filter', explode(',', $entity));
        }

        $type = request()->query('type');
        if (isset($type)) {
            $this->updateFiltersValues('asset_type_filter', explode(',', $type));
        }

        $this->addBreadcrumb(__('Assets'), route('tenant.garbtar.asset'));
        $this->addBreadcrumb(__('Panel'));
    }

    public function getValues()
    {
        $data = $this->model->getDataForRatios();

        $this->total = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS];

        $this->assetBtarPer = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_BTAR_PERCENT];
        $this->assetExcludedPer = 100 - $this->assetBtarPer;

        $this->assetBtar = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_BTAR];
        $this->assetExcluded = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR];

        $this->specializedCredit = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::SPECIALIZED_LOANS];
        $this->nonSpecializedCredit = $this->assetBtar - $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::SPECIALIZED_LOANS];

        $this->assets_with_taxonomy = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::TAXONOMIC_INFORMATION][BankAssets::WITH_DATA];
        $this->assets_without_taxonomy = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::TAXONOMIC_INFORMATION][BankAssets::NO_DATA];

        $this->realEstate = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::REAL_STATE];
        $this->families = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::FAMILIES];
        $this->publicSector = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::PUBLIC_SECTOR_LOCAL];
        $this->companies = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES] + $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD];
        $this->subjectNFRD = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::NON_FINANCIAL_COMPANIES];
        $this->subjectNFRDEU = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU];
        $this->subjectNFRDNonEU = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU];
        $this->subjectNFRDWithData = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::NON_FINANCIAL_COMPANIES_TAXONOMIC_INFORMATION][BankAssets::WITH_DATA];
        $this->subjectNFRDWithoutData = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::NON_FINANCIAL_COMPANIES_TAXONOMIC_INFORMATION][BankAssets::NO_DATA];
        $this->subjectNFRDEUWithData = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_TAXONOMIC_INFORMATION][BankAssets::WITH_DATA];
        $this->subjectNFRDEUWithoutData = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_TAXONOMIC_INFORMATION][BankAssets::NO_DATA];
        $this->subjectNFRDNonEUWithData = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_TAXONOMIC_INFORMATION][BankAssets::WITH_DATA];
        $this->subjectNFRDNonEUWithoutData = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_TAXONOMIC_INFORMATION][BankAssets::NO_DATA];
        $this->garType = [
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::CREDIT_INSTITUTIONS][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INVESTMENT_COMPANIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::MANAGEMENT_COMPANIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INSURANCE_COMPANIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::NON_FINANCIAL_COMPANIES_SUBJECT_NFRD][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::FAMILIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::PUBLIC_SECTOR][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::REAL_STATE][BankAssets::WITH_DATA],
        ];
        $this->garTypeNoData = [
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::CREDIT_INSTITUTIONS][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INVESTMENT_COMPANIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::MANAGEMENT_COMPANIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INSURANCE_COMPANIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::NON_FINANCIAL_COMPANIES_SUBJECT_NFRD][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::FAMILIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::PUBLIC_SECTOR][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::REAL_STATE][BankAssets::NO_DATA],
        ];
        $this->btarType = [
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::CREDIT_INSTITUTIONS][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INVESTMENT_COMPANIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::MANAGEMENT_COMPANIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INSURANCE_COMPANIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::NON_FINANCIAL_COMPANIES_TAXONOMIC_INFORMATION][BankAssets::WITH_DATA] + $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_TAXONOMIC_INFORMATION][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::FAMILIES][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::PUBLIC_SECTOR][BankAssets::WITH_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::REAL_STATE][BankAssets::WITH_DATA],
        ];
        $this->btarTypeNoData = [
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::CREDIT_INSTITUTIONS][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INVESTMENT_COMPANIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::MANAGEMENT_COMPANIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::INSURANCE_COMPANIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::NON_FINANCIAL_COMPANIES_TAXONOMIC_INFORMATION][BankAssets::NO_DATA] + $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_TAXONOMIC_INFORMATION][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::FAMILIES][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::PUBLIC_SECTOR][BankAssets::NO_DATA],
            $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::REAL_STATE][BankAssets::NO_DATA],
        ];
        $this->excludedNumerator = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR];
        $this->excludedNumeratorDetailed = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED];
        $this->companiesDetailed = [
            BankAssets::GAR => [
                $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_DETAILED][1][BankAssets::ABSOLUTE_VALUE],
                $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_DETAILED][2][BankAssets::ABSOLUTE_VALUE],
                $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_DETAILED][3][BankAssets::ABSOLUTE_VALUE],
            ],
            BankAssets::BTAR => [
                $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_DETAILED][1][BankAssets::ABSOLUTE_VALUE] + $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED][1][BankAssets::ABSOLUTE_VALUE],
                $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_DETAILED][2][BankAssets::ABSOLUTE_VALUE] + $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED][2][BankAssets::ABSOLUTE_VALUE],
                $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_DETAILED][3][BankAssets::ABSOLUTE_VALUE] + $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED][3][BankAssets::ABSOLUTE_VALUE],
            ]
        ];
        $this->subjectNFRDDetailed = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED];
        $this->assetType = $data[BankAssets::STOCK][BankAssets::BANK_BALANCE][BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE];

        $this->assetWithDataGAR = $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::ASSETS_WITH_DATA];
        $this->assetWithoutDataGAR = $data[BankAssets::STOCK][BankAssets::VN][BankAssets::GAR][BankAssets::ASSETS_WITHOUT_DATA];

        $this->assetWithDataBTAR = $data[BankAssets::STOCK][BankAssets::VN][BankAssets::BTAR][BankAssets::ASSETS_WITH_DATA];
        $this->assetWithoutDataBTAR = $data[BankAssets::STOCK][BankAssets::VN][BankAssets::BTAR][BankAssets::ASSETS_WITHOUT_DATA];
    }

    public function render(): View
    {
        $this->assets = BankAssets::list()->get();
        $this->getValues();
        return view('livewire.tenant.garbtarassets.index', [
            'assets' => $this->assets
        ]);
    }

    /**
     * Update graph section
     */
    public function showInformationRow($value = null)
    {
        if (isset($this->rowActive) && $this->rowActive === $value) {
            $this->rowActive = null;
        } else {
            $this->rowActive = $value;
        }
        $this->emit('updateRow');
    }

}
