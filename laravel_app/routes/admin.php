<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PatientGuideController;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('pages', PageController::class, [
        'as' => 'admin'
    ]);

    // Admin users list/index
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

    // Additional user-management routes used by the admin sidebar/menu
    Route::get('/users/all', [UserController::class, 'all'])->name('all_users');
    Route::get('/users/types', [UserController::class, 'types'])->name('all_types');
    Route::get('/users/active', [UserController::class, 'active'])->name('active_users');
    Route::get('/users/block', [UserController::class, 'block'])->name('block_users');

    // Profile and security routes used by sidebar
    Route::get('/profile', [SettingController::class, 'profile'])->name('profile');
    Route::get('/security', [SettingController::class, 'security'])->name('security');

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::get('/site-settings', [SettingController::class, 'home'])->name('admin.settings.home');
    Route::get('/hero-sections', [SettingController::class, 'heroSections'])->name('admin.settings.hero');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('admin.contact-messages.index');




    Route::get('/add-site-services', [SettingController::class, 'addSiteService'])->name('admin.site.services.add');
    Route::get('/get-parent-services', [SettingController::class, 'getParentServices'])->name('admin.get.parent.services');

// Services Page
    Route::post('/site-services', [SettingController::class, 'storeSiteService'])->name('admin.site.services.store');


    Route::get('/site-services', [SettingController::class, 'siteServices'])->name('admin.site.services');
    Route::get('/site-services/data', [SettingController::class, 'siteServicesData'])->name('admin.site.services.data');
    Route::get('/site-services/{parentId}/children', [SettingController::class, 'getServicesByParent'])->name('admin.site.services.children');

// Dynamic View (Unlimited Levels)
    Route::get('/service/{slug}', [SettingController::class, 'getServiceData']);
    Route::get('/service/{slug}/children', [SettingController::class, 'getServiceChildren']);

    Route::get('/service/edit/{id}', [SettingController::class, 'editService']);
    Route::post('/service/update/{id}', [SettingController::class, 'updateService']);
// Delete
    Route::delete('/service/{id}', [SettingController::class, 'deleteService']);

    Route::get('/patient-guides', [PatientGuideController::class, 'index'])->name('admin.patient-guides.index');
    Route::get('/patient-guides/data', [PatientGuideController::class, 'data'])->name('admin.patient-guides.data');
    Route::get('/patient-guides/add', [PatientGuideController::class, 'add'])->name('admin.patient-guides.add');
    Route::post('/patient-guides/store', [PatientGuideController::class, 'store'])->name('admin.patient-guides.store');
    Route::get('/patient-guides/parents', [PatientGuideController::class, 'getParentGuides'])->name('admin.patient-guides.parents');
    Route::get('/patient-guides/{parentId}/children', [PatientGuideController::class, 'getGuidesByParent'])->name('admin.patient-guides.children');

    Route::get('/patient-guide/{slug}', [PatientGuideController::class, 'getGuideData'])->name('admin.patient-guide.data');
    Route::get('/patient-guide/{slug}/children', [PatientGuideController::class, 'getGuideChildren'])->name('admin.patient-guide.children.data');
    Route::get('/patient-guide/edit/{id}', [PatientGuideController::class, 'edit'])->name('admin.patient-guide.edit');
    Route::post('/patient-guide/update/{id}', [PatientGuideController::class, 'update'])->name('admin.patient-guide.update');
    Route::delete('/patient-guide/{id}', [PatientGuideController::class, 'delete'])->name('admin.patient-guide.delete');


//    pages route
    Route::get('/site-Home', [SettingController::class, 'siteHome'])->name('admin.site.home');
    Route::get('/site-about', [SettingController::class, 'siteAbout'])->name('admin.site.about');
    Route::get('/site-contact', [SettingController::class, 'siteContactPage'])->name('admin.site.contact');
    Route::post('/site-contact', [SettingController::class, 'storeContactPage'])->name('admin.site.contact.store');
    Route::get('/site-services-page', [SettingController::class, 'siteServicesPage'])->name('admin.site.pages.services');
    Route::post('/site-services-page/header', [SettingController::class, 'storeServicesPageSettings'])->name('admin.site.pages.services.header.store');
    Route::get('/site-patient-guide-page', [SettingController::class, 'sitePatientGuidePage'])->name('admin.site.pages.patientguide');
    Route::post('/site-patient-guide-page/header', [SettingController::class, 'storePatientGuidePageSettings'])->name('admin.site.pages.patientguide.header.store');
    Route::get('/site-smtp', [SettingController::class, 'siteSmtpPage'])->name('admin.site.smtp');
    Route::post('/site-smtp', [SettingController::class, 'storeSmtpSettings'])->name('admin.site.smtp.store');
    Route::get('/site-header-footer', [SettingController::class, 'siteHeaderFooterPage'])->name('admin.site.header-footer');
    Route::post('/site-header-footer', [SettingController::class, 'storeHeaderFooterSettings'])->name('admin.site.header-footer.store');
    Route::get('/site-patient-guide-page/data/{id}', [SettingController::class, 'getPatientGuidePageData'])->name('admin.site.pages.patientguide.data');
    Route::post('/site-patient-guide-page/update/{id}', [SettingController::class, 'updatePatientGuidePageData'])->name('admin.site.pages.patientguide.update');
    Route::post('/site-about', [SettingController::class, 'storeAboutPage'])->name('admin.site.about.store');
    Route::post('/site-home/hero', [SettingController::class, 'storeHeroSection'])->name('admin.site.home.hero.store');
    Route::post('/site-home/remove-item', [SettingController::class, 'removeHomeItem'])->name('admin.site.home.remove-item');
    Route::post('/site-home/about', [SettingController::class, 'storeAboutSection'])->name('admin.site.home.about.store');
    Route::post('/site-home/contact', [SettingController::class, 'storeContactSection'])->name('admin.site.home.contact.store');
    Route::post('/site-home/services', [SettingController::class, 'storeServicesSection'])->name('admin.site.home.services.store');
    Route::post('/site-home/whatwedo', [SettingController::class, 'storeWhatWeDoSection'])->name('admin.site.home.whatwedo.store');
    Route::post('/site-home/causes', [SettingController::class, 'storeCausesSection'])->name('admin.site.home.causes.store');
    Route::post('/site-home/why-choose', [SettingController::class, 'storeWhyChooseSection'])->name('admin.site.home.whychoose.store');
    Route::post('/site-home/how-it-work', [SettingController::class, 'storeHowItWorkSection'])->name('admin.site.home.howitwork.store');
    Route::post('/site-home/testimonials', [SettingController::class, 'storeTestimonialsSection'])->name('admin.site.home.testimonials.store');
    Route::post('/site-home/gallery', [SettingController::class, 'storeGallerySection'])->name('admin.site.home.gallery.store');
    Route::post('/site-home/last-hope', [SettingController::class, 'storeLastHopeSection'])->name('admin.site.home.lasthope.store');
});

