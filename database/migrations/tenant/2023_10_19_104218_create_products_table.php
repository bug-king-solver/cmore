<?php

use App\Models\Tenant\Category;
use App\Models\Tenant\Company;
use App\Models\Tenant\Product;
use App\Models\Tenant\QuestionnaireType;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->nullableNumericMorphs('productable');
            $table->string('icon')->nullable();
            $table->json('title');
            $table->json('description');
            $table->float('price')->default(0);
            $table->boolean('catalog')->comment('Show in the catalog?')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');

        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });
    }
};
