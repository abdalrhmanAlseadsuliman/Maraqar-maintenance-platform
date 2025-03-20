<?php

namespace App\Providers;
use Filament\Panel;
use Barryvdh\DomPDF\Facade;
use Filament\PanelProvider;
use Filament\Facades\Filament;

use Filament\Support\Assets\Css;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;



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





    }
}
