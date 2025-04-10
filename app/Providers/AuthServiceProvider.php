<?php
namespace App\Providers;

use App\Models\MaintenanceRequests;
use App\Policies\MaintenanceRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        MaintenanceRequests::class => MaintenanceRequestPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
