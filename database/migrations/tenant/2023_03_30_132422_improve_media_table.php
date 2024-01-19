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
            $table->foreignIdFor(Media::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
        $this->moveAttachmentsToMedia();
    }

    public function moveAttachmentsToMedia()
    {
        $attachments = Attachment::all();
        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                if ($attachment->created_by_user_id != '') {
                    $user = User::find($attachment->created_by_user_id);
                } else {
                    $attached = $attachment->attachables->first();
                    $user = $attached ? User::find($attached->attachmentator_id) : null;
                }
                if ($user && Storage::disk('local')->exists($attachment->file)) {
                    $filepath = Storage::disk('local')->path($attachment->file);
                    $addedMedia = $user->addMedia($filepath)
                    ->preservingOriginal()
                    ->usingName($attachment->name)
                    ->withCustomProperties(
                        [
                            'folder' => null,
                            'created_by'=>$user->id,
                        ]
                    )
                    ->toMediaCollection('library', 'attachments');
                    $attachment->attachables()->update(['media_id' => $addedMedia->id]);
                }
            }
        }
    }
};
