<?php

use App\Models\Tenant\Attachable;
use App\Models\Tenant\Attachment;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
        });
    }

    public function moveAttachmentsToMedia()
    {
        //$attachable  = Attachable::all();
        $attachments = Attachment::all();
        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $totalAttachables = $attachment->attachables->count();
                if ($totalAttachables > 0) {
                    $copiedMediaId = '';
                    foreach ($attachment->attachables as $key => $attachable) {
                        $mediaModel = $attachable->attachable_type::find($attachable->attachable_id);
                        if ($mediaModel && Storage::disk('local')->exists($attachment->file)) {
                            $filepath = Storage::disk('local')->path($attachment->file);
                            $customProperties = [
                                'folder' => null,
                                'created_by'=>$attachable->attachmentator_id,
                            ];
                            if ($totalAttachables > 1 && $key > 0) {
                                $customProperties['copied_media_id'] = $copiedMediaId;
                            }
                            $addedMedia = $mediaModel->addMedia($filepath)
                            ->preservingOriginal()
                            ->usingName($attachment->name)
                            ->withCustomProperties($customProperties)
                            ->toMediaCollection('library', 'attachments');

                            if ($totalAttachables > 1 && $key == 0) {
                                $copiedMediaId = $addedMedia->id;
                            }
                        }
                    }
                } else {
                    $user = User::find($attachment->created_by_user_id);
                    if ($user && Storage::disk('local')->exists($attachment->file)) {
                        $filepath = Storage::disk('local')->path($attachment->file);

                        $user->addMedia($filepath)
                        ->preservingOriginal()
                        ->usingName($attachment->name)
                        ->withCustomProperties(
                            [
                                'folder' => null,
                                'created_by'=>$attachment->created_by_user_id,
                            ]
                        )
                        ->toMediaCollection('library', 'attachments');
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
