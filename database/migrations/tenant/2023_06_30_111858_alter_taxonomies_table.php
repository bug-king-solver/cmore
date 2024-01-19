<?php

use App\Models\Tenant\Company;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\User;
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
        // disable foreign key checks
        Schema::disableForeignKeyConstraints();
        Taxonomy::truncate();

        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('year');
            // drop created_by_user_id and constraint
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn('created_by_user_id');
            // drop company_id and constraint
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');

            $table->foreignIdFor(Questionnaire::class, 'questionnaire_id')
                ->constrained('questionnaires')->cascadeOnDelete()->after('id');
            // businnes_resume : json
            $table->json('business_resume')->after('questionnaire_id');
            //safeguard : json
            $table->json('safeguard')->after('business_resume');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->string('name');
            $table->foreignIdFor(User::class, 'created_by_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->date('year')->nullable();

            $table->dropForeign(['questionnaire_id']);
            $table->dropColumn('questionnaire_id');
            $table->dropColumn('business_resume');
            $table->dropColumn('safeguard');
        });
        Schema::enableForeignKeyConstraints();
    }
};
