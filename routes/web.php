<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontHomeController;
Use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MyprofileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Gov_ReportController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserNotificationController;



Auth::routes([
    'login'    => true,
    'logout'   => true,
    'register' => false,
    'reset'    => true,   // for resetting passwords
    'confirm'  => false,  // for additional password confirmations
    'verify'   => false,  // for email verification
    ]);

require __DIR__.'/gov_case/gov_case.php';

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

Route::post('/login', [LoginController::class, 'doptorLogin'])->name('doptor.login');
Route::get('/doptor/login', [LoginController::class, 'initiateSSOLogin'])->name('sso.login');
Route::any('/nothi/callback', [LoginController::class, 'ndoptor_sso_callback']);

Route::get('/sso/logout', [DashboardController::class, 'logoutUser'])->name('sso.logout');;

Route::get('/', [DashboardController::class, 'logincheck']);
Route::get('public_home', [FrontHomeController::class, 'public_home']);
Route::get('hearing-case-list', [FrontHomeController::class, 'dateWaysCase'])->name('dateWaysCase');
Route::get('rm-case-hearing-list', [FrontHomeController::class, 'dateWaysRMCase'])->name('dateWaysRMCase');

Route::middleware('auth')->group(function () {
    // setting
    Route::get('site_setting', [SiteSettingController::class, 'edit'])->name('app.setting.index');
    Route::post('site_setting', [SiteSettingController::class, 'update'])->name('app.setting.update');

    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/databaseCaseCheck', [HomeController::class, 'databaseCaseCheck']);
    Route::get('/databaseDataUpdated', [HomeController::class, 'databaseDataUpdated']);
    /////****************** Dashboard *****************/////
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/hearing-case-details/{id}', [DashboardController::class, 'hearing_case_details'])->name('dashboard.hearing-case-details');
    Route::get('/dashboard/hearing-today', [DashboardController::class, 'hearing_date_today'])->name('dashboard.hearing-today');
    Route::get('/dashboard/hearing-tomorrow', [DashboardController::class, 'hearing_date_tomorrow'])->name('dashboard.hearing-tomorrow');
    Route::get('/dashboard/hearing-nextWeek', [DashboardController::class, 'hearing_date_nextWeek'])->name('dashboard.hearing-nextWeek');
    Route::get('/dashboard/hearing-nextMonth', [DashboardController::class, 'hearing_date_nextMonth'])->name('dashboard.hearing-nextMonth');


    /////****************** Gov Case Report Module *************/////
    Route::get('/govcase/report', [Gov_ReportController::class, 'index'])->name('reportss');
    Route::get('/govcase/report/caselist', [Gov_ReportController::class, 'caselist'])->name('report.govcaselist');
    Route::post('/govcase/report/pdf', [Gov_ReportController::class, 'pdf_generate']);
    // Route::get('/report/old-case', [RM_ReportController::class, 'old_case']);
    //============ Case Activity Log End ==============//


    /////************** User Management **************/////
    Route::resource('user-management', UserManagementController::class);
    /////************** MY Profile **************/////
    // Route::resource('my-profile', MyprofileController::class);
    Route::get('/my-profile', [MyprofileController::class, 'index'])->name('my-profile.index');
    Route::get('/my-profile/basic', [MyprofileController::class, 'basic_edit'])->name('my-profile.basic_edit');
    Route::post('/my-profile/basic/update', [MyprofileController::class, 'basic_update'])->name('my-profile.basic_update');
    Route::get('/my-profile/image', [MyprofileController::class, 'imageUpload'])->name('my-profile.imageUpload');
    Route::post('/my-profile/image/update', [MyprofileController::class, 'image_update'])->name('my-profile.image_update');
    Route::get('/my-profile/change-password', [MyprofileController::class, 'change_password'])->name('change.password');
    Route::post('/my-profile/update-password', [MyprofileController::class, 'update_password'])->name('update.password');
    // Route::get('/my-profile', [MyprofileController::class, 'index'])->name('my-profile.index');
    /////************** Office Setting **************/////


    /////************** Court Setting **************/////
    // Route::resource('court-setting', CourtController::class);
    // route::get('/court', [CourtController::class, 'index'])->name('court');
    // route::get('/court/create', [CourtController::class, 'create'])->name('court.create');
    // Route::post('/court/save', [CourtController::class, 'store'])->name('court.save');
    // route::get('/court/edit/{id}', [CourtController::class, 'edit'])->name('court.edit');
    // route::post('/court/update/{id}', [CourtController::class, 'update'])->name('court.update');
    // route::get('/court-setting/dropdownlist/getdependentdistrict/{id}', [CourtController::class , 'getDependentDistrict']);

    /////************** General Setting **************/////
    // Route::resource('setting', SettingController::class);
    //=======================division===============//
    Route::get('/division', [SettingController::class, 'division_list'])->name('division');
    Route::get('/division/edit/{id}', [SettingController::class, 'division_edit'])->name('division.edit');
    Route::post('/division/update/{id}', [SettingController::class, 'division_update'])->name('division.update');

    //======================= //division===============//
    Route::get('/settings/district', [SettingController::class, 'district_list'])->name('district');
    Route::get('/settings/district/edit/{id}', [SettingController::class, 'district_edit'])->name('district.edit');
    Route::post('/settings/district/update/{id}', [SettingController::class, 'district_update'])->name('district.update');
    Route::get('/settings/upazila', [SettingController::class, 'upazila_list'])->name('upazila');
    Route::get('/settings/upazila/edit/{id}', [SettingController::class, 'upazila_edit'])->name('upazila.edit');
    Route::post('/settings/upazila/update/{id}', [SettingController::class, 'upazila_update'])->name('upazila.update');
    Route::get('/settings/mouja', [SettingController::class, 'mouja_list'])->name('mouja');
    Route::get('/settings/mouja/add', [SettingController::class, 'mouja_add'])->name('mouja-add');
    Route::get('/settings/mouja/edit/{id}', [SettingController::class, 'mouja_edit'])->name('mouja.edit');
    Route::post('/settings/mouja/save', [SettingController::class, 'mouja_save'])->name('mouja.save');
    Route::post('/settings/mouja/update/{id}', [SettingController::class, 'mouja_update'])->name('mouja.update');
    Route::get('/settings/survey', [SettingController::class, 'survey_type_list'])->name('survey');
    /*Route::get('/survey/edit/{id}', [SettingController::class, 'survey_edit'])->name('survey.edit');
    Route::post('/survey/update/{id}', [SettingController::class, 'survey_update'])->name('survey.update');*/
     Route::get('/case_type', [SettingController::class, 'case_type_list'])->name('case-type');
     Route::get('/case_status', [SettingController::class, 'case_status_list'])->name('case-status');
     Route::get('/case_status/add', [SettingController::class, 'case_status_add'])->name('case-status.add');
     Route::get('/case_status/details/{id}', [SettingController::class, 'case_status_details'])->name('case-status.details');
     Route::post('/case_status/store', [SettingController::class, 'case_status_store'])->name('case-status.store');
     Route::get('/case_status/edit/{id}', [SettingController::class, 'case_status_edit'])->name('case-status.edit');
     Route::post('/case_status/update/{id}', [SettingController::class, 'case_status_update'])->name('case-status.update');
    /*Route::get('/case_type/edit/{id}', [SettingController::class, 'case_type_edit'])->name('case_type.edit');
    Route::post('/case_type/update/{id}', [SettingController::class, 'case_type_update'])->name('case_type.update');*/
    Route::get('/court_type', [SettingController::class, 'court_type_list'])->name('court-type');
    /*Route::get('/court_type/edit/{id}', [SettingController::class, 'court_type_edit'])->name('court_type.edit');
    Route::post('/court_type/update/{id}', [SettingController::class, 'court_type_update'])->name('court_type.update');*/

    /////************** //General Setting **************/////
    Route::resource('projects', ProjectController::class);
    Route::get('/form-layout', function () {
        return view('form_layout');
    });
    Route::get('/list', function () {
        return view('list');
    });

    //=================== Notification Start ================
    Route::get('/results_completed', [UserNotificationController::class, 'results_completed'])->name('results_completed');
    Route::get('/hearing_date', [UserNotificationController::class, 'hearing_date'])->name('hearing_date');
    Route::get('/rmcase/hearing_date', [UserNotificationController::class, 'rm_hearing_date'])->name('rm_hearing_date');
    Route::get('/new_sf_list', [UserNotificationController::class, 'newSFlist'])->name('newSFlist');
    Route::get('/new_sf_details/{id}', [UserNotificationController::class, 'newSFdetails'])->name('newSFdetails');
    //=================== Notification End ==================

    //=================== Message Start ================
    Route::get('/messages', [MessageController::class, 'messages'])->name('messages');
    Route::get('/messages_recent', [MessageController::class, 'messages_recent'])->name('messages_recent');
    Route::get('/messages_request', [MessageController::class, 'messages_request'])->name('messages_request');
    Route::get('/messages/{id}', [MessageController::class, 'messages_single'])->name('messages_single');
    Route::get('/messages_remove/{id}', [MessageController::class, 'messages_remove'])->name('messages_remove');
    Route::post('/messages/send', [MessageController::class, 'messages_send'])->name('messages_send');
    Route::get('/messages_group', [MessageController::class, 'messages_group'])->name('messages_group');
    // Route::get('/hearing_date', [MessageController::class, 'hearing_date'])->name('hearing_date');
    // Route::get('/new_sf_list', [MessageController::class, 'newSFlist'])->name('newSFlist');
    // Route::get('/new_sf_details/{id}', [MessageController::class, 'newSFdetails'])->name('newSFdetails');
    //=================== Message End ==================
    Route::get('/script', [MessageController::class, 'script']);

});
