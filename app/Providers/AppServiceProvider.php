<?php

namespace App\Providers;

use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        if (PHP_OS_FAMILY === 'Windows' && $this->app->runningInConsole()) {
            foreach (['TEMP', 'TMP'] as $variable) {
                $value = getenv($variable);

                if ($value !== false) {
                    $_ENV[$variable] = $value;
                }
            }

            ServeCommand::$passthroughVariables = array_values(array_unique([
                ...ServeCommand::$passthroughVariables,
                'TEMP',
                'TMP',
            ]));
        }
    }
}
