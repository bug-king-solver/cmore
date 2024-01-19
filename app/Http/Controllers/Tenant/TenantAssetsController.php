<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Media;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class TenantAssetsController extends Controller
{
    public static $tenancyMiddleware = 'Stancl\Tenancy\Middleware\InitializeTenancyByDomain';

    protected $path;

    protected $name;

    protected $mimeType;

    public function __construct()
    {
        $this->middleware(static::$tenancyMiddleware);
    }

    /**
     * No authenticated: is only allowed to see public documents
     * Authenticated: can see all the documents
     */
    protected function authorize($disk, $path)
    {
        $disks = config('filesystems.disks');

        if (($disk !== 'public' && ! auth()->user()) || ! array_key_exists($disk, $disks)) {
            abort(404);
        }

        // If the file is in the library, get data from there
        // Otherwise, load it from the bucket
        if (is_numeric($path)) {
            $media = Media::find($path);

            if (! $media) {
                abort(404);
            }

            $this->path = $media->getPath();
            $this->mimeType = $media->mime_type;
            $this->name = $media->name;
        } else {
            $this->path = $path;
            $this->mimeType = Storage::disk($disk)->mimeType($path);
        }
    }

    /**
     * Show assets
     */
    public function asset($disk, $path)
    {
        $this->authorize($disk, $path);

        try {
            return response(Storage::disk($disk)->get($this->path))
                ->header('Content-Type', $this->mimeType);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    /**
     * Download assets
     */
    public function download($disk, $path)
    {
        $this->authorize($disk, $path);

        try {
            return Storage::disk($disk)->download($this->path, $this->name);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
