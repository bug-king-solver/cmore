<?php

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

        Schema::create('questionnaire_physicalrisks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_activity_id')->constrained('business_activities')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('country_iso');
            $table->string('country_code');
            $table->string('region_code');
            $table->string('city_code');
            $table->json('hazards');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_physicalrisks');
    }
};
