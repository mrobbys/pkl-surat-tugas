<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Custom throttle untuk login
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3) // 3 attempts per menit
                ->by($request->input('email') . '|' . $request->ip()) // by email + IP
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.429', [], 429)->withHeaders($headers);
                });
        });
    }
}
