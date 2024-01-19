<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Media;
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
        $medias = Media::where('disk', 's3')->update(
            [
                'disk'=>'attachments',
                'conversions_disk'=>'attachments',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
