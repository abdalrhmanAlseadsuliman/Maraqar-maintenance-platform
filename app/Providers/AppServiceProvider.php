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


use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;


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

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn(): string => '
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="theme-color" content="#000000">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="default">
                <meta name="apple-mobile-web-app-title" content="اسم موقعك">
                <meta name="description" content="وصف موقعك">
                <link rel="manifest" href="/manifest.json">
                <link rel="icon" href="/images/icon-192x192.png">
                <link rel="apple-touch-icon" href="/images/icon-192x192.png">
                <script>
                    if ("serviceWorker" in navigator) {
                        window.addEventListener("load", () => {
                            navigator.serviceWorker.register("/sw.js")
                                .then(registration => console.log("SW registered"))
                                .catch(error => console.log("SW registration failed"));
                        });
                    }
                </script>
            '
        );



    }
}