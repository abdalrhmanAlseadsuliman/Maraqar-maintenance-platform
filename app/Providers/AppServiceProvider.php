<?php

namespace App\Providers;
use Filament\Panel;
use Barryvdh\DomPDF\Facade;
use Filament\PanelProvider;
use Filament\Facades\Filament;

use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use App\Models\MaintenanceRequests;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use App\Observers\MaintenanceRequestObserver;



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
    public function boot()
    {
        FilamentAsset::register([
            Css::make('filament-rtl', resource_path('css/filament-rtl.css')),
        ]);



        FilamentAsset::register([
            Js::make('custom-script', resource_path('js/filament-js.js')),
        ]);
        MaintenanceRequests::observe(MaintenanceRequestObserver::class);




    }
}
