<?php

namespace App\Providers;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Barryvdh\DomPDF\Facade;

use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\ServiceProvider;
use App\Models\MaintenanceRequests;
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
