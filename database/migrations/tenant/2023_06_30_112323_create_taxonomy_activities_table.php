<?php

use App\Models\Tenant\BusinessActivities;
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
        Schema::create('taxonomy_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->constrained('taxonomies')->cascadeOnDelete();
            $table->foreignIdFor(BusinessActivities::class, 'business_activities_id')->constrained('business_activities')->cascadeOnDelete();

            $table->string('name');
            $table->json('activity_resume');
            $table->json('substancial_contribute');
            $table->json('nps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_activities');
    }
};
