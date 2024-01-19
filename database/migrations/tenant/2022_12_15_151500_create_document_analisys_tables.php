<?php

use App\Models\Tenant\Compliance\DocumentAnalysis\Domain;
use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use App\Models\Tenant\Compliance\DocumentAnalysis\Type;
use App\Models\Tenant\MediaType;
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
        // Schema::create('document_analysis_types', function (Blueprint $table) {
        //     $table->id();
        //     $table->boolean('enabled')->default(false);
        //     $table->json('title');
        //     $table->json('description');
        //     $table->timestamps();
        // });

        Schema::create('document_analysis_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MediaType::class, 'document_analysis_type_id');
            $table->boolean('enabled')->default(false);
            $table->json('title');
            $table->json('description');
            $table->json('terms_base');
            $table->json('terms_prefixes');
            $table->json('terms_suffixes');
            $table->json('terms_both'); // Prefixes and Suffixes
            $table->timestamps();
        });

        Schema::create('document_analysis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MediaType::class, 'document_analysis_type_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->tinyInteger('compliance_level')->nullable();
            $table->timestamps();
        });

        Schema::create('document_analysis_snippets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Result::class, 'document_analysis_result_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Domain::class, 'document_analysis_domain_id')->constrained()->cascadeOnDelete();
            $table->string('term');
            $table->string('prefix');
            $table->string('suffix');
            $table->integer('page');
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
        Schema::dropIfExists('document_analysis_snippets');
        Schema::dropIfExists('document_analysis_results');
        Schema::dropIfExists('document_analysis_domains');
        Schema::dropIfExists('document_analisys_types');
    }
};
