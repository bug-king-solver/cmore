<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Initiative;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
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
        Schema::create('question_question_option', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Question::class, 'question_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(QuestionOption::class, 'question_option_id')
                ->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Initiative::class, 'initiative_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Sdg::class, 'sdg_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->decimal('weight')->default(0);
            $table->enum('children_action', ['enable', 'disable'])->nullable();
            $table->boolean('comment_required')->default(false);
            $table->boolean('attachment_required')->default(false);
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
        Schema::dropIfExists('question_question_option');
    }
};
