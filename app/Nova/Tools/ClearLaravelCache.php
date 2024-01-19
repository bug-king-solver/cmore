<?php

namespace App\Nova\Tools;

use BeyondCode\QueryDetector\Outputs\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Card;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\Tool;

class ClearLaravelCache extends Tool
{
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Clear cache')
            ->path('/backoffice/clear-cache')
            ->icon('refresh');
    }
}
