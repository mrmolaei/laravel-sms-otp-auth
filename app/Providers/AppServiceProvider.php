<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
        Validator::extend('mobile_number', function ($attribute, $value, $parameters, $validator) {
            // Check if the mobile number starts with 0 and contains only numbers
            return preg_match('/^0[0-9]{10}$/', $value);
        });

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
