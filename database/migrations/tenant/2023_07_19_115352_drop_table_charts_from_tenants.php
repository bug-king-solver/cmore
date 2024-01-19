<?php

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
        //check if chats table exists , if it does then drop it
        if (Schema::hasTable('charts')) {
            Schema::dropIfExists('charts');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // check if charts table does not exist, if it does not then create it
        if (!Schema::hasTable('charts')) {
            Schema::create('charts', function (Blueprint $table) {
                $table->id();
                $table->string('slug');
                $table->string('name');
                $table->string('type');
                $table->json('structure')->nullable();
                $table->json('indicators')->nullable();
                $table->string('placeholder')->nullable();
                $table->timestamps();
            });
        }
    }
};
