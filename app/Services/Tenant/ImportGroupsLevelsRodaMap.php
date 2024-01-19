<?php

namespace App\Services\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Company;
use App\Models\Tenant\Groups;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Target;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportGroupsLevelsRodaMap
{
    protected Tenant $tenant;

    protected string $path;

    protected array $data;

    protected string $folder;

    protected array $files;

    protected int $groupsLelves;

    public function __construct($tenant)
    {
        tenancy()->initialize($tenant);
        $this->tenant = tenant();

        $folder = base_path() . '/database/data/groups/';
        $pattern = $folder . '*.xlsx';
        $this->files = glob($pattern);
    }

    /**
     * Import groups from csv file
     */
    public function extractExcelData()
    {
        foreach ($this->files as $file) {
            $this->extractExcelDataFromFile($file);
        }
    }

    public function extractExcelDataFromFile($file)
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('groupables')->truncate();
        DB::table('groups')->truncate();
        DB::table('targets')->truncate();
        DB::raw("DELETE FROM userables WHERE userable_type like '%Target%';");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        foreach ($spreadsheet->getWorksheetIterator() as $key => $worksheet) {
            $sheetName = Str::slug($worksheet->getTitle());
            $dados = [];
            $dados[$sheetName]['title'] = $worksheet->getTitle();

            foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                if ($rowNumber <= 6 || in_array($rowNumber, [8])) {
                    continue;
                }

                foreach ($row->getCellIterator() as $cell) {
                    $dados[$sheetName]['subgroups'][$rowNumber][] = $cell->getCalculatedValue() ?? '';
                }
            }

            $this->makeArrayOfGroupsAndTargets($dados);
        }

        return true;
    }

    public function makeArrayOfGroupsAndTargets($dados)
    {
        $groups = [];
        foreach ($dados as $key => $dado) {
            $groups[$key]['title'] = $dado['title'];
            $nivelcount = 1;
            $subGroupCount = 1;

            foreach ($dado['subgroups'] as $i => $subgroup) {
                /** If the value contains the text "our ambition", it is understood to be a group (in this case, at level 1). */
                if (preg_match('/our ambition/i', $subgroup[0])) {
                    if (isset($groups[$key]['subgroups'][$nivelcount]['title'])) {
                        $nivelcount++;
                    }

                    $groups[$key]['subgroups'][$nivelcount]['title'] = $subgroup[2] ?? null;
                    $groups[$key]['subgroups'][$nivelcount]['subgroups'] = [];

                    /** If the value contains the text "Driver", it is understood to be a subgroup (in this case, at level 2). */
                } elseif (preg_match('/Driver/i', $subgroup[0])) {
                    if (isset($groups[$key]['subgroups'][$nivelcount]['subgroups'][$subGroupCount]['title'])) {
                        $subGroupCount++;
                    }
                    $groups[$key]['subgroups'][$nivelcount]['subgroups'][$subGroupCount]['title'] = $subgroup[2] ?? null;
                    $groups[$key]['subgroups'][$nivelcount]['subgroups'][$subGroupCount]['targets'] = [];
                } else {
                    if (isset($groups[$key]['subgroups'][$nivelcount]['subgroups'][$subGroupCount]['targets'])) {
                        $groups[$key]['subgroups'][$nivelcount]['subgroups'][$subGroupCount]['targets'][] = $subgroup;
                    }
                }
            }
        }

        return $this->createGroupsAndTargets($groups);
    }

    public function createGroupsAndTargets($dados, $parentGroup = null)
    {
        $indicatorId = Indicator::first()->id;
        $createdbyUser = User::first();
        $companyId = Company::first()->id;

        foreach ($dados as $firstGroupName => $group) {
            if (isset($group['title'])) {
                $newGroup = $this->createGroup($group['title'], $parentGroup != null ? 0 : 1);
                if ($parentGroup) {
                    $newGroup->saveGroupInGroup($newGroup, $parentGroup);
                }
            }
            if (isset($group['subgroups']) && isset($newGroup)) {
                return $this->createGroupsAndTargets($group['subgroups'], $newGroup);
            } elseif (isset($group['targets']) && isset($newGroup)) {
                foreach ($group['targets'] as $target) {
                    $newTarget = $this->createTarget($target, $indicatorId, $createdbyUser->id, $companyId);

                    if ($newTarget) {
                        $newGroup->saveResourceInGroup($newTarget, $newGroup->id);
                        $newTarget->assignUsers([], $createdbyUser);
                    }
                }
            }
        }
    }

    /**
     * Creates a new group with the given title.
     *
     * @param string $title The title of the new group.
     * @param int $saveToTarget Whether to save the group to a target resource (1) or not (0).
     * @return \App\Models\Tenant\Groups The newly created group.
     * @throws \Exception If an error occurs while creating the group.
     */
    public function createGroup(string $title, $saveToTarget = 0)
    {
        return Groups::create([
            'name' => $title,
            'resource' => $saveToTarget ? 'App\Models\Tenant\Target' : null,
        ]);
    }

    public function createTarget(array $data, int $indicatorId, int $createdbyUserId, int $companyId)
    {
        try {
            if (empty($data[5]) || empty($data[6])) {
                return false;
            }

            $startDate = carbon()->now()->startOfYear()->format('Y-m-d');

            if (strlen($data[3]) == 4) {
                $startDate = carbon()->createFromFormat('Y', $data[3])->startOfYear()->format('Y-m-d');
            }

            // rand true or false
            if (rand(0, 1)) {
                $endDate = carbon()->parse($startDate)->endOfYear()->format('Y-m-d');
            } else {
                $endDate = carbon()->now()->subDay(rand(0, 4))->format('Y-m-d');
            }

            if (strlen($data[4]) == 4) {
                $endDate = carbon()->createFromFormat('Y', $data[4])->endOfYear()->format('Y-m-d');
            }

            $createdAt = carbon()->now()->subDays(rand(0, 30));

            /**
             * Double check ,because some sheets have the columns 5 and 6 different.
             */
            if (preg_match('/^F\d+_/i', $data[5])) {
                $title = strlen($data[6]) > 255 ? 'Target ' . $data[5] : $data[6];
                $description = $data[6];
                $reference = $data[5];
            } else {
                $title = strlen($data[7]) > 255 ? 'Target ' . $data[6] : $data[7];
                $description = $data[7];
                $reference = $data[6];
            }

            $statusSlugs = ['ongoing', 'completed', 'not-started'];
            $status = $statusSlugs[rand(0, 2)];
            $startedAt = null;
            $completedAt = null;
            if ($status == 'ongoing' || $status == 'completed') {
                $startedAt = '2023-01-01';
            }

            if ($status == 'completed') {
                $completedAt = '2023-03-01';
            }

            return Target::create([
                'title' => $title,
                'description' => $description,
                'our_reference' => $reference,
                'goal' => null,
                'company_id' => $companyId,
                'indicator_id' => $indicatorId,
                'created_by_user_id' => $createdbyUserId,
                'status' => $status,
                'start_date' => $startDate,
                'due_date' => $endDate,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
