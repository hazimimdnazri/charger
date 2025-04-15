<?php

namespace App\Providers;

use App\Models\Charger;
use App\Models\Customer;
use App\Policies\ChargerPolicy;
use App\Policies\CustomerPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Charger::class, ChargerPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
    }
}
