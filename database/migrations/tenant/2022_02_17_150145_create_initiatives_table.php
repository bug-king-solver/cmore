<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Category;
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
        Schema::create('initiatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->references('id')->on('initiatives')->nullOnDelete();
            $table->foreignIdFor(Sdg::class, 'sdg_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Category::class, 'category_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('order')->nullable();
            $table->unsignedInteger('impact')->default(0);
            $table->json('name');
            $table->json('description')->nullable();
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
        Schema::dropIfExists('initiatives');
    }
};
