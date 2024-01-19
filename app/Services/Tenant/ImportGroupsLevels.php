<?php

namespace App\Services\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Company;
use App\Models\Tenant\Groups;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Target;
use App\Models\User;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportGroupsLevels
{
    protected Tenant $tenant;

    protected string $path;

    protected array $data;

    protected string $folder;

    protected string $file;

    protected int $groupsLelves;

    public function __construct($tenant, int $groupsLelves = 3)
    {
        tenancy()->initialize($tenant);
        $this->tenant = tenant();
        $this->groupsLelves = $groupsLelves;
    }

    /**
     * Check if a file exists into database folder.
     * The file must exists into $folder/tenant$tenantId
     * @param string $name
     * @param string $folder
     */
    public function checkIfFileExists()
    {
        $path = base_path() . '/database/data/' . $this->folder . '/tenant' . $this->tenant->id . '/' . $this->file;
        if (! file_exists($path)) {
            throw new \Exception("File $this->file not found into $this->folder/tenant" . $this->tenant->id);
        } else {
            $this->path = $path;
        }
    }

    //importFile() takes a file and a folder as arguments
    //It checks if the file exists in the folder
    //It returns an instance of the class
    public function importFile(string $file, string $folder)
    {
        // Set the folder variable to the one passed in to the constructor
        $this->folder = $folder;
        // Set the file variable to the one passed in to the constructor
        $this->file = $file;
        // Call the checkIfFileExists method, which checks if the file exists in the folder
        $this->checkIfFileExists($this->file, $this->folder);

        return $this;
    }

    /**
     * Import groups from csv file
     */
    public function extractExcelData()
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->path);
        $worksheet = $spreadsheet->getActiveSheet();
        $dados = [];
        foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
            foreach ($row->getCellIterator() as $colNumber => $cell) {
                $value = $cell->getValue();
                if ($value) {
                    $dados["row_$rowNumber"]["column_$colNumber"] = $cell->getValue();
                }
            }
        }
        $this->data = $dados;

        return $this;
    }

    public function importDataFromExcel()
    {
        $this->extractExcelData();
        $maxGroupLevel = $this->groupsLelves;

        $rows = [];
        foreach ($this->data as $i => $row) {
            /** row 2 its the NIVEL row */
            if ($i == 'row_2' || $i == 'row_3') {
                continue;
            }
            $rows[$i][] = $row;
        }

        /** nivel column will be maxGrouLevel + 1 */
        $nivelColumn = chr(ord('A') + $maxGroupLevel - 1);
        $parentGroup = null;

        $indicatorId = Indicator::first()->id;
        $createdbyUserId = User::first()->id;
        $companyId = Company::first()->id;

        try {
            foreach ($rows as $row) {
                foreach ($row as $groups) {
                    foreach ($groups as $key => $group) {
                        /* if key text  >=  "column_$nivelColumn" , continue */
                        if (ord(str_replace('column_', '', $key)) >= ord($nivelColumn)) {
                            if ($key == "column_$nivelColumn") {
                                $this->createTarget($groups, $parentGroup, $indicatorId, $createdbyUserId, $companyId);
                            }
                            continue;
                        }

                        $resource = null;
                        if ($key == 'column_A') {
                            $resource = (new Target())->getMorphClass();
                        }

                        $newGroup = Groups::updateOrCreate([
                            'name' => $group,
                            'resource' => $resource,
                        ]);

                        if ($key != 'column_A') {
                            $newGroup->saveGroupInGroup($newGroup, $parentGroup);
                        }

                        $parentGroup = $newGroup;
                    }
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createTarget($data, $parentGroup, $indicatorId, $createdbyUserId = null, $companyId = null)
    {
        try {
            $dates = explode('-', $data['column_H']);
            $startDate = null;
            $endDate = null;

            if ($dates) {
                if (isset($dates[0])) {
                    $startDate = carbon()->createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');
                }
                if (isset($dates[1])) {
                    $endDate = carbon()->createFromFormat('d/m/Y', $dates[1])->format('Y-m-d');
                }
            }

            $target = Target::create([
                'title' => $data['column_D'],
                'description' => $data['column_F'],
                'our_reference' => $data['column_E'],
                'goal' => $data['column_G'],
                'company_id' => $companyId,
                'indicator_id' => $indicatorId,
                'created_by_user_id' => $createdbyUserId,
                'start_date' => $startDate,
                'due_date' => $endDate,
            ]);

            if ($target) {
                $parentGroup->saveResourceInGroup($target, $parentGroup->id);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
