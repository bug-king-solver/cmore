<?php

use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // check if questionnaire table exists
        if (!Schema::hasTable('questionnaires')) {
            return;
        }

        $questionnaires = Questionnaire::where("questionnaire_type_id", 12)->get();
        foreach ($questionnaires as $key => $quest) {
            $physicalRisks = $quest->physicalRisks;
            foreach ($physicalRisks as $key => $physicalRisk) {
                $hazards = $physicalRisk->hazards;
                $hazards = parseStringToArray($hazards);
                if (isset($hazards['data'])) {
                    $hazards = $hazards['data'];
                    $physicalRisk->hazards = $hazards;
                    $physicalRisk->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
            //
        });
    }
};
