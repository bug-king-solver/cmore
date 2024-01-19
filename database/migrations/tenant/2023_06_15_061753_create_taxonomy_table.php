<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Tenant\Company;
use App\Models\User;
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
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(User::class, 'created_by_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->date('year')->nullable();
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
        Schema::dropIfExists('taxonomies');
    }
};
