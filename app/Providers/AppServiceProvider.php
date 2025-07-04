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
            fn(): string => $this->getPWAMetaTags()
        );

    }

    private function getPWAMetaTags(): string
    {
        $currentPath = request()->path();

        // تحديد نوع الصفحة والإعدادات المناسبة
        if (str_starts_with($currentPath, 'admin')) {
            return $this->getAdminPWAMeta();
        } elseif (str_starts_with($currentPath, 'user')) {
            return $this->getUserPWAMeta();
        } else {
            return $this->getMainPWAMeta();
        }
    }

    private function getMainPWAMeta(): string
    {
        return '
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="theme-color" content="#000000">
            <meta name="apple-mobile-web-app-capable" content="yes">
            <meta name="apple-mobile-web-app-status-bar-style" content="default">
            <meta name="apple-mobile-web-app-title" content="مار العقارية">
            <meta name="description" content="منصة مار العقارية الرئيسية">
            <link rel="manifest" href="/manifest.json">
            <link rel="icon" href="/images/logo1.jpg">
            <link rel="apple-touch-icon" href="/images/logo1.jpg">
            <script>
                if ("serviceWorker" in navigator) {
                    window.addEventListener("load", () => {
                        navigator.serviceWorker.register("/sw.js");
                    });
                }
            </script>
        ';
    }

    private function getAdminPWAMeta(): string
    {
        return '
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="theme-color" content="#1f2937">
            <meta name="apple-mobile-web-app-capable" content="yes">
            <meta name="apple-mobile-web-app-status-bar-style" content="default">
            <meta name="apple-mobile-web-app-title" content="مار العقارية إدارة">
            <meta name="description" content="لوحة تحكم الإدارة">
            <link rel="manifest" href="/manifest.json">
            <link rel="icon" href="/images/logo1.jpg">
            <link rel="apple-touch-icon" href="/images/logo1.jpg">
            <script>
                if ("serviceWorker" in navigator) {
                    window.addEventListener("load", () => {
                        navigator.serviceWorker.register("/sw-admin.js");
                    });
                }
            </script>
        ';
    }

    private function getUserPWAMeta(): string
    {
        return '
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="theme-color" content="#3b82f6">
            <meta name="apple-mobile-web-app-capable" content="yes">
            <meta name="apple-mobile-web-app-status-bar-style" content="default">
            <meta name="apple-mobile-web-app-title" content="مار العقارية مستخدم">
            <meta name="description" content="لوحة تحكم المستخدم">
            <link rel="manifest" href="/manifest-user.json">
            <link rel="icon" href="/images/logo1.jpg">
            <link rel="apple-touch-icon" href="/images/logo1.jpg">
            <script>
                if ("serviceWorker" in navigator) {
                    window.addEventListener("load", () => {
                        navigator.serviceWorker.register("/sw-user.js");
                    });
                }
            </script>
        ';
    }
}