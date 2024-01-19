<?php

use App\Models\Tenant\Media;
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
        Schema::table('document_folders', function (Blueprint $table) {
            $table->enum('visibility', ['internal', 'external'])->default('internal')->after('id');
        });
        $this->updateCollectionName();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_folders', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }

    public function updateCollectionName()
    {
        $mediaObjects = Media::where('collection_name', 'library')->get();
        if (count($mediaObjects) > 0) {
            foreach ($mediaObjects as $mediaObject) {
                if (! is_null($mediaObject->custom_properties['folder'])) {
                    $mediaObject->collection_name = config('media-library.collection.internal');
                } else {
                    $mediaObject->collection_name = config('media-library.collection.attachments');
                }
                $mediaObject->save();
            }
        }
    }
};
