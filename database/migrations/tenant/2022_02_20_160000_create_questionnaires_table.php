<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionnaireType;
use App\Models\User;
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
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QuestionnaireType::class, 'questionnaire_type_id')
                ->nullable()->constrained('questionnaire_types')->nullOnDelete();
            $table->foreignIdFor(User::class, 'created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(Company::class, 'company_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Question::class, 'last_question_id')->nullable()
                ->constrained('questions')->nullOnDelete();
            $table->boolean('is_ready')->default(false);
            $table->date('from');
            $table->date('to');
            $table->json('categories')->nullable();
            $table->json('questions')->nullable();
            $table->json('initiatives')->nullable();
            $table->json('sdgs')->nullable();
            $table->decimal('maturity')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
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
        Schema::dropIfExists('questionnaires');
    }
};
