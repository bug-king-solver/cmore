<?php

namespace App\Nova\Tools;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Visanduma\NovaTwoFactor\NovaTwoFactor as NovaTwoFactorNovaTwoFactor;

class NovaTwoFactor extends NovaTwoFactorNovaTwoFactor
{

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function menu(Request $request)
    {
        if (config('nova-two-factor.showin_sidebar', true) && !tenancy()->initialized) {
            return MenuSection::make(config('nova-two-factor.menu_text'))
                ->path('/nova-two-factor')
                ->icon(config('nova-two-factor.menu_icon'));
        }
    }
}
