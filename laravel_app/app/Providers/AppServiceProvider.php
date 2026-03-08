<?php

namespace App\Providers;

use App\Http\Controllers\Frontend\NavbarPatientGuideController;
use App\Http\Controllers\Frontend\NavbarServiceController;
use App\Models\HomeSetting;
use App\Models\Service;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

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
        // Header Data
        View::composer('frontend.includes.header', function ($view) {
            $navbarServiceController = app(NavbarServiceController::class);
            $navbarPatientGuideController = app(NavbarPatientGuideController::class);

            $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
            $headerFooterData = is_array($homeSetting->header_footer_data) 
                ? $homeSetting->header_footer_data 
                : [];

            $view->with('navbarServices', $navbarServiceController->getNavbarServices());
            $view->with('navbarPatientGuides', $navbarPatientGuideController->getNavbarPatientGuides());
            $view->with('headerFooterData', $headerFooterData);
        });

        // Footer Data
        View::composer('frontend.includes.footer', function ($view) {

            $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
            $headerFooterData = is_array($homeSetting->header_footer_data) 
                ? $homeSetting->header_footer_data 
                : [];

            // Footer Services (latest 4)
            $services = Cache::remember('footer_services', 3600, function () {
                return Service::select('id','name','slug')
                    ->latest()
                    ->take(4)
                    ->get();
            });

            $view->with('headerFooterData', $headerFooterData);
            $view->with('services', $services);
        });
    }
}