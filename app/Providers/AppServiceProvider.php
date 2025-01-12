<?php

namespace App\Providers;

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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
    }
    
    protected function mapApiRoutes()
{
    Route::prefix('api')
        ->middleware('api') // O middleware HandleCors já está incluído globalmente
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php'));
}
}
