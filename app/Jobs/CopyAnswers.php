<?php

namespace App\Jobs;

use App\Models\Tenant\Questionnaire;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CopyAnswers implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Questionnaire */
    protected $newQuestionnaire;

    /** @var Questionnaire */
    protected $oldQuestionnaire;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Questionnaire $newQuestionnaire, Questionnaire $oldQuestionnaire)
    {
        $this->onQueue('questionnaires');

        $this->newQuestionnaire = $newQuestionnaire;
        $this->oldQuestionnaire = $oldQuestionnaire;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sql = "INSERT INTO answers (
            assigned_to_user_id,
            assigned_by_user_id,
            questionnaire_id,
            question_id,
            initiative_id,
            sdg_id,
            weight,
            value,
            assigned_at,
            created_at,
            updated_at,
            validation,
            comment
            ) SELECT  assigned_to_user_id,
            assigned_by_user_id,
            {$this->newQuestionnaire->id},
            question_id,
            initiative_id,
            sdg_id,
            weight,
            value,
            assigned_at,
            created_at,
            updated_at,
            validation,
            comment FROM answers  WHERE questionnaire_id = {$this->oldQuestionnaire->id}";

        DB::statement($sql);

        if ($this->oldQuestionnaire->questionnaire_type_id == 10) {
            $this->copyTaxonomy();
        }

        if ($this->oldQuestionnaire->questionnaire_type_id == 12) {
            $this->copyPhysicalRisk();
        }

        $this->newQuestionnaire->is_ready = true;
        $this->newQuestionnaire->update();
    }

    /**
     * Duplicate taxonomy data to new questionnaire
     */
    public function copyTaxonomy()
    {
        $taxonomySql = "INSERT INTO taxonomies (
                questionnaire_id,
                imported_file_url,
                created_at,
                updated_at,
                summary,
                safeguard,
                started_at
                ) SELECT
                {$this->newQuestionnaire->id},
                imported_file_url,
                created_at,
                updated_at,
                summary,
                safeguard,
                started_at FROM taxonomies WHERE questionnaire_id = {$this->oldQuestionnaire->id}";

        DB::statement($taxonomySql);

        $oldTaxonomyId = DB::table('taxonomies')->where('questionnaire_id', $this->oldQuestionnaire->id)->value('id');
        $newTaxonomyId = DB::table('taxonomies')->where('questionnaire_id', $this->newQuestionnaire->id)->value('id');

        $taxonomyActivitiesSql = "INSERT INTO taxonomy_activities (
                taxonomy_id,
                business_activities_id,
                name,
                summary,
                contribute,
                dnsh,
                created_at,
                updated_at,
                deleted_at
                ) SELECT
                {$newTaxonomyId},
                business_activities_id,
                name,
                summary,
                contribute,
                dnsh,
                created_at,
                updated_at,
                deleted_at
                FROM taxonomy_activities WHERE taxonomy_id = {$oldTaxonomyId}";

        DB::statement($taxonomyActivitiesSql);
    }

    /**
     * Duplicate physical risk data to new questionnaire
     */
    public function copyPhysicalRisk()
    {
        $physicalrisksSql = "INSERT INTO questionnaire_physicalrisks (
            created_by_user_id,
            questionnaire_id,
            name,
            description,
            hazards,
            note,
            relevant,
            completed,
            completed_at,
            created_at,
            updated_at,
            deleted_at,
            business_sector_id,
            company_address_id
            ) SELECT
            created_by_user_id,
            {$this->newQuestionnaire->id},
            name,
            description,
            hazards,
            note,
            relevant,
            completed,
            completed_at,
            created_at,
            updated_at,
            deleted_at,
            business_sector_id,
            company_address_id FROM questionnaire_physicalrisks WHERE questionnaire_id = {$this->oldQuestionnaire->id}";

        DB::statement($physicalrisksSql);
    }
}
