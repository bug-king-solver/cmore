<?php

namespace App\Http\Controllers\Central;

use App\Actions\CreateTenantAction;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function hasView($view)
    {
        $view = customView($view);
        if (!config('app.instance')) {
            abort(404, __("Instance not defined"));
        }

        if (!$view) {
            if (request()->url() !== route("central.landing")) {
                return redirect()->route("central.landing");
            }
            abort(404, __("Instance not defined"));
        }

        return $view;
    }

    /**
     * Show the central landing page.
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return $this->hasView("central.solution");
    }

    /**
     * Show the about us page.
     * @return \Illuminate\Contracts\View\View
     */
    public function aboutUs()
    {
        return $this->hasView("central.about-us");
    }

    /**
     * Show the packages page.
     * @return \Illuminate\Contracts\View\View
     */
    public function packages()
    {
        return $this->hasView("central.packages");
    }

    /**
     * Show the our partners page.
     * @return \Illuminate\Contracts\View\View
     */
    public function ourPartners()
    {
        return $this->hasView("central.our-partners");
    }

    /**
     * Show the become partner page.
     * @return \Illuminate\Contracts\View\View
     */
    public function becomePartner()
    {
        return $this->hasView("central.become-partner");
    }

    /**
     * Show the faqs page.
     * @return \Illuminate\Contracts\View\View
     */
    public function faqs()
    {
        return $this->hasView("central.faqs");
    }

    /**
     * Show the support request page.
     * @return \Illuminate\Contracts\View\View
     */
    public function supportRequest()
    {
        return $this->hasView("central.support-request");
    }

    /**
     * Show the contact us page.
     * @return \Illuminate\Contracts\View\View
     */
    public function contactUs()
    {
        return $this->hasView("central.contact-us");
    }

    /**
     * Show the solutions page.
     * @return \Illuminate\Contracts\View\View
     */
    public function solutions()
    {
        return $this->hasView("central.solution");
    }

    /**
     * Show assets
     */
    public function asset($disk, $path)
    {
        try {
            return response(Storage::disk($disk)->get($path))
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
        try {
            return Storage::disk($disk)->download($path);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
