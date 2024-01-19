<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Questionnaire::class, 'questionnaire_id')
                ->nullable()->constrained('questionnaires')->nullOnDelete();
            $table->foreignIdFor(Question::class, 'question_id')->nullable()->constrained('questions')->nullOnDelete();
            $table->string('file');
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
        Schema::dropIfExists('attachments');
    }
};
