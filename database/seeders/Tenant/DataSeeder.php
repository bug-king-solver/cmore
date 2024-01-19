<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DataSeeder extends Seeder
{
    /**
     * Disk where the data is storage
     */
    protected $disk = 'portal';

    /**
     * File path to retrieve the data
     */
    protected $file;

    /**
     * Determine if is to import all or just the updates.
     * true » all; false » only updates;
     */
    protected $all;

    /**
     * Related model
     */
    protected $model;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns;

    /**
     * Default values when the column is empty
     */
    protected $default;

    /**
     * Flag that indicates if the modal has the `enabled` column or not
     */
    protected $hasEnabledColumn = true;

    /**
     * Existing ids
     */
    protected $idsExisting = [];

    /**
     * Updated ids
     */
    protected $idsUpdated = [];

    /**
     * Really updated ids
     */
    protected $idsReallyUpdated = [];

    /**
     * Created ids
     */
    protected $idsCreated = [];

    /**
     * Really created ids
     */
    protected $idsReallyCreated = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($all = null)
    {
        $startTime = now();

        $this->getUpdatesOrAll($all);

        $seederName = explode('\\', get_called_class());
        $seederName = end($seederName);

        $message = 'Started "' . $seederName . '" seeder';
        $message .= ' | registers: ' . ($this->all ? 'all' : 'update');

        activity()
            ->event('seeding')
            ->log($message);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $json = Storage::disk($this->disk)->get($this->getFilePath());
        $rows = json_decode($json, true);

        $message = '» Downloaded and decoded successfully: ' . $this->getFilePath();
        $message .= ' | after ' . now()->diffInSeconds($startTime) . ' seconds';
        $message .= ' | with: ' . count($rows ?? []) . ' registers';

        activity()
            ->event('seeding')
            ->log($message);

        if (!$rows) {
            $message = 'Finish with problems "' . $seederName . '" seeder';
            $message .= ' 0 registers founds for:  "' . $this->getFilePath() . '"';

            activity()
                ->event('seeding')
                ->log($message);
            return;
        }

        $this->idsExisting = DB::table((new $this->model())->getTable())->select('id')->get()->pluck('id')->toArray();

        $now = Carbon::now()->format('Y-m-d H:i:s');

        $chunked = collect($rows)->chunk(1000);
        $newRegisters = 0;
        $updatedRegisters = 0;

        foreach ($chunked as $rows) {
            foreach ($rows as $key => $row) {
                $data = [];

                foreach ($this->columns as $our => $their) {
                    // Used for our json columns (single columns in the portal)
                    if (is_array($their)) {
                        $data[$our] = array_intersect_key($row, array_flip($their));
                        continue;
                    }

                    // If the columns does not exists or it is empty
                    if (!isset($row[$their])) {
                        $data[$our] = $this->default[$our] ?? null;
                        continue;
                    }

                    $data[$our] = $row[$their];
                }

                if (isset($data['deleted_at']) && $data['deleted_at']) {
                    $data['deleted_at'] = Carbon::parse($data['deleted_at'])->format('Y-m-d H:i:s');
                }

                $internalTags = $row['internal_tag_id_list'] ?? [];

                if (!in_array($data['id'], $this->idsExisting, false)) {
                    if ($this->hasEnabledColumn) {
                        $data['enabled'] = config('app.env') === 'local'
                            ? true
                            : enableRowWhenSeeding($row);
                    }

                    $data['created_at'] = $now;

                    // Convert jsons to string
                    foreach ($data as &$row) {
                        if (is_array($row)) {
                            $row = json_encode($row);
                        }
                    }

                    $newId = DB::table((new $this->model())->getTable())
                        ->insertGetId($data);

                    $this->idsExisting[] = $newId;
                    $this->idsCreated[] = $newId;

                    if ($newId) {
                        $this->idsReallyCreated[] = $data['id'];
                    }
                } else {
                    // We just need validate what is coming in the file
                    $keysToUpdate = array_keys($data);

                    $dataFromDatabase = (array) DB::table((new $this->model())->getTable())
                        ->select($keysToUpdate)
                        ->where('id', $data['id'])
                        ->first();

                    // Convert json to array
                    foreach ($dataFromDatabase as &$row) {
                        $decoded = @json_decode($row, true);
                        $row = (json_last_error() === JSON_ERROR_NONE)
                            ? $decoded
                            : $row;
                    }

                    // Check if is to update
                    if ($data != $dataFromDatabase) {
                        $this->idsUpdated[] = $data['id'];

                        $updates = DB::table((new $this->model())->getTable())
                            ->where('id', $data['id'])
                            ->update($data);

                        // Check if the update went well
                        if ($updates) {
                            $this->idsReallyUpdated[] = $data['id'];
                        }
                    }
                }

                // check if $row contain internal_tag_id_list and if isnt empty
                if (isset($internalTags) && !empty($internalTags)) {
                    if (!is_array($internalTags)) {
                        $internalTags = [$internalTags];
                    }

                    (new $this->model())->withoutGlobalScopes()
                        ->whereId($data['id'])
                        ->first()
                        ->assignInternalTags($internalTags);
                }
            }

            sleep(3);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (method_exists($this, 'seederCallback')) {
            $this->seederCallback();
        }

        $message = 'Finish "' . $seederName . '" seeder';
        $message .= ' | registers:' . ($this->all ? 'all' : 'update');
        $message .= ' | new: ' . count($this->idsReallyCreated) . ' of ' . count($this->idsCreated);
        $message .= ' | updated: ' . count($this->idsReallyUpdated) . ' of ' . count($this->idsUpdated);
        $message .= ' | after ' . now()->diffInSeconds($startTime) . ' seconds';

        activity()
            ->event('seeding')
            ->log($message);
    }

    /**
     * ask the user if he wants to import all or just the updates
     */
    protected function getUpdatesOrAll($all)
    {
        $this->all = $all ?? $this->command->ask('Do you want to import all data instead of updates? (yes/no)', 'no') === 'yes';
    }

    /**
     * Get the file path to retrieve the data
     */
    protected function getFilePath()
    {
        return ($this->all ? 'all/' : 'updates/') . $this->file;
    }
}
