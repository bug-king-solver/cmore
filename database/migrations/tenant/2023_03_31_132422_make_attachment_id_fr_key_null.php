<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Attachment;
use App\Models\Tenant\Media;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachables', function (Blueprint $table) {
            $table->bigInteger('attachment_id')->unsigned()->nullable()->change();
        });
    }
};
