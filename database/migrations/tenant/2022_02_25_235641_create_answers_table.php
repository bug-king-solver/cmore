<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Initiative;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Sdg;
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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Questionnaire::class, 'questionnaire_id')
                ->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Question::class, 'question_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Initiative::class, 'initiative_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Sdg::class, 'sdg_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('weight')->default(0);
            $table->text('value')->nullable();
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
        Schema::dropIfExists('answers');
    }
};
