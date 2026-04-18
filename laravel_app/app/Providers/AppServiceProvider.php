<?php

namespace App\Providers;

use App\Http\Controllers\Frontend\NavbarPatientGuideController;
use App\Http\Controllers\Frontend\NavbarServiceController;
use App\Models\HomeSetting;
use App\Models\Service;
use Illuminate\Support\Str;
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

        View::composer('frontend.pages.*', function ($view) {
            $homeSetting = HomeSetting::firstOrNew(['id' => 1]);
            $aboutPageData = is_array($homeSetting->about_page_data) ? $homeSetting->about_page_data : [];
            $pageHeaderImage = $aboutPageData['page_header_image'] ?? null;

            $pageHeaderImageUrl = filled($pageHeaderImage)
                ? (Str::startsWith((string) $pageHeaderImage, ['http://', 'https://', '/', 'data:image/'])
                    ? $pageHeaderImage
                    : asset('storage/' . ltrim((string) $pageHeaderImage, '/')))
                : asset('frontend/assets/images/page-header-bg.jpg');

            $view->with('pageHeaderImageUrl', $pageHeaderImageUrl);
        });
    }
}