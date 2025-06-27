<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

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
        // Força HTTPS apenas em produção ou quando configurado
        if (App::environment('production') || config('secure.force_https', false)) {
            URL::forceScheme('https');
        }
    }
    
    protected function mapApiRoutes()
{
    Route::prefix('api')
        ->middleware('api') // O middleware HandleCors já está incluído globalmente
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php'));
}
}
