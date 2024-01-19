<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laravel\Nova\Notifications\NovaNotification;

class BackofficeActionsController extends Controller
{
    /**
     * Clear the Laravel cache.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return redirect()->back();
    }
}
