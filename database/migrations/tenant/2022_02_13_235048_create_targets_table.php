<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')
                ->nullable()->constrained('users')->cascadeOnDelete()->comment('Owner Id');
            $table->foreignIdFor(Indicator::class, 'indicator_id')
                ->nullable()->constrained('indicators')->cascadeOnDelete();
            $table->string('title');
            $table->longText('description');
            $table->bigInteger('goal');
            $table->date('due_date');
            $table->enum('status', ['not-started', 'ongoing', 'completed'])->default('not-started');
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
        Schema::dropIfExists('targets');
    }
};
