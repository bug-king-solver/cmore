<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Attachment;
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
        Schema::dropIfExists('attachments');

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file');
            $table->string('name');
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('attachables', function (Blueprint $table) {
            $table->foreignIdFor(Attachment::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->morphs('attachmentator');
            $table->morphs('attachable');
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
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('attachables');
    }
};
