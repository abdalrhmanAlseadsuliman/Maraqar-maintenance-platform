<?php

namespace App\Providers;

use App\Models\Property;
use App\Auth\NationalIdGuard;
use App\Policies\PropertyPolicy;
use App\Models\MaintenanceRequests;
use Illuminate\Support\Facades\Auth;
use App\Policies\MaintenanceRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        MaintenanceRequests::class => MaintenanceRequestPolicy::class,
        Property::class => PropertyPolicy::class,

    ];

    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('national_id', function ($app, $name, array $config) {
            return new NationalIdGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
