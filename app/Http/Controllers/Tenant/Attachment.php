<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Media;

class Attachment extends Controller
{
    public function index($ids = null)
    {
        $ids = explode('-', $ids);
        $attachments = Media::whereNotIn('id', $ids)
        ->where('collection_name', config('media-library.collection.attachments'))
        ->whereJsonContains('custom_properties->created_by', auth()->user()->id)
        ->get()
        ->map(
            function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'title' => $attachment->name,
                ];
            }
        );

        return response()->json($attachments);
    }
}
