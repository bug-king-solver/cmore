<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //abort_if(! env('APP_INSTANCE'), 403, 'Missing APP_INSTANCE!');

        /* Force https */
        if (env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
            // Required to solve uploads issue: https://github.com/livewire/livewire/issues/1216#issuecomment-849917370
            request()->server->set('HTTPS', 'on');
        }

        /*
         * Set a default cookie prefix
         * Spec https://tools.ietf.org/html/draft-ietf-httpbis-cookie-prefixes-00#section-3
         */
        if ((! preg_match('/^__/', Config::get('session.cookie'))) && (Config::get('session.secure') == true) && (Request::secure())) {
            // Set the host prefix
            if (strtolower(Config::get('session.cookie_prefix')) === 'host') {
                // Set the host prefix
                Config::set('session.cookie', '__Host-' . Config::get('session.cookie'));
                Config::set('session.secure', true);
                Config::set('session.path', '/');
            } elseif (strtolower(Config::get('session.cookie_prefix')) !== 'none') {
                // Set the secure prefix
                Config::set('session.cookie', '__Secure-' . Config::get('session.cookie'));
                Config::set('session.secure', true);
            }
        }

        Cashier::ignoreMigrations();
        \Spatie\NovaTranslatable\Translatable::defaultLocales(['en', 'pt-PT', 'pt-BR', 'es', 'fr']);

        Blade::directive('nl2br', function ($string) {
            return "<?php echo nl2br($string); ?>";
        });

        Activity::saving(function (Activity $activity) {
            $activity->properties = $activity->properties->put('agent', [
                'ip' => Request::ip(),
                'url' => Request::fullUrl(),
            ]);
        });
    }
}
