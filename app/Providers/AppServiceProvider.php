<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Laravel\Passport\Passport;  // Ensure Passport is imported

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // You would bind services to the container here if needed.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set application locale to 'id' (Indonesian)
        config(['app.locale' => 'id']);

        // Set Carbon's locale to 'id' (for date formatting in Indonesian)
        Carbon::setLocale('id');

        // Set the default timezone to 'Asia/Jakarta'
        date_default_timezone_set('Asia/Jakarta');

        // Register Passport routes for token issuance and validation
        Passport::hashClientSecrets();

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(12));
    }
}


