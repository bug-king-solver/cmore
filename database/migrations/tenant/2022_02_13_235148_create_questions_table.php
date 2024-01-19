<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\Source;
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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QuestionnaireType::class, 'questionnaire_type_id')
                ->nullable()->constrained('questionnaire_types')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->references('id')->on('questions')->nullOnDelete();
            $table->foreignIdFor(Category::class, 'category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Indicator::class, 'indicator_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Source::class, 'source_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->json('description');
            $table->string('source_ref')->nullable();
            $table->enum(
                'answer_type',
                [
                    'binary',
                    'checkbox',
                    'checkbox-obs',
                    'text',
                    'text-long',
                    'integer',
                    'decimal',
                    'matrix',
                    'countries-multi',
                    'sdgs-multi',
                ]
            );
            $table->decimal('weight')->default(0);
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
        Schema::dropIfExists('questions');
    }
};
