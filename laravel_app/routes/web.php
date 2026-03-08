
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


use App\Http\Controllers\SiteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;


Route::get('/storage-link', function () {
    abort_unless(app()->environment('local'), 403);
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Storage linked!';
});

// Frontend routes
Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/services/load-more', 'loadMoreServices')->name('services.load-more');
    Route::get('/services/{slug}', 'serviceDetails')->name('services.details');
    Route::get('/patient-guide', 'patientGuide')->name('patient-guide');
    Route::get('/patient-guide/{slug}', 'patientGuideDetails')->name('patient-guide.details');
    Route::post('/patient-guide/{slug}/submit-analysis', 'submitHairAnalysis')->name('patient-guide.submit.analysis');
    Route::post('/patient-guide/{slug}/submit-booking', 'submitBooking')->name('patient-guide.submit.booking');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::post('/contact-us/submit', 'submitContact')->name('contact.submit');
});

// Dynamic pages
Route::get('/pages/{slug}', [FrontendPageController::class, 'show'])->name('pages.show');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

