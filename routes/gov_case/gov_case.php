<?php

use App\Http\Controllers\gov_case\GovCaseActionController;
use App\Http\Controllers\gov_case\GovCaseUserNotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RM_CaseActionController;
use App\Http\Controllers\gov_case\GovCaseRegisterController;
use App\Http\Controllers\gov_case\GovCaseUserManagementController;
use App\Http\Controllers\gov_case\GovCaseOfficeController;
use App\Http\Controllers\gov_case\GovCaseMessageController;

Route::middleware('auth')->group(function () {

    Route::group(['prefix' => 'cabinet/', 'as' => 'cabinet.'], function () {
        Route::resource('user-management', GovCaseUserManagementController::class);
        /////************** Office Setting **************/////
        Route::get('/office', [GovCaseOfficeController::class, 'index'])->name('office');
        Route::get('/office/level/{level}', [GovCaseOfficeController::class, 'level_wise'])->name('office.level');
        Route::get('/office/parent/{parent}', [GovCaseOfficeController::class, 'parent_wise'])->name('office.parent');
        route::get('/office/create', [GovCaseOfficeController::class, 'create'])->name('office.create');
        Route::post('/office/save', [GovCaseOfficeController::class, 'store'])->name('office.save');
        route::get('/office/edit/{id}', [GovCaseOfficeController::class, 'edit'])->name('office.edit');
        route::post('/office/update/{id}', [GovCaseOfficeController::class, 'update'])->name('office.update');
        route::get('/office/dropdownlist/getdependentdistrict/{id}', [GovCaseOfficeController::class , 'getDependentDistrict']);
        route::get('/office/dropdownlist/getdependentupazila/{id}', [GovCaseOfficeController::class , 'getDependentUpazila']);
            /////************** //Office Setting **************/////
            //=================== Message Start ================
        Route::get('/messages', [GovCaseMessageController::class, 'messages'])->name('messages');
        Route::get('/messages_recent', [GovCaseMessageController::class, 'messages_recent'])->name('messages_recent');
        Route::get('/messages_request', [GovCaseMessageController::class, 'messages_request'])->name('messages_request');
        Route::get('/messages/{id}', [GovCaseMessageController::class, 'messages_single'])->name('messages_single');
        Route::get('/messages_remove/{id}', [GovCaseMessageController::class, 'messages_remove'])->name('messages_remove');
        Route::post('/messages/send', [GovCaseMessageController::class, 'messages_send'])->name('messages_send');
        Route::get('/messages_group', [GovCaseMessageController::class, 'messages_group'])->name('messages_group');
        Route::get('/hearing_date', [GovCaseUserNotificationController::class, 'hearing_date'])->name('hearing_date');
        Route::get('/results_completed', [GovCaseUserNotificationController::class, 'results_completed'])->name('results_completed');
        // Route::get('/new_sf_list', [GovCaseMessageController::class, 'newSFlist'])->name('newSFlist');
        // Route::get('/new_sf_details/{id}', [GovCaseMessageController::class, 'newSFdetails'])->name('newSFdetails');
        Route::get('/script', [GovCaseMessageController::class, 'script']);
        
        //=================== Message End ==================//

        Route::group(['prefix' => 'case/', 'as' => 'case.'], function () {

            Route::get('index', [GovCaseRegisterController::class, 'index'])->name('index');
            Route::get('highcourt', [GovCaseRegisterController::class, 'high_court_case'])->name('highcourt');
            Route::get('appellateDivision', [GovCaseRegisterController::class, 'appellate_division_case'])->name('appellateDivision');
            Route::get('running_case', [GovCaseRegisterController::class, 'running_case'])->name('running');
            Route::get('appeal_case', [GovCaseRegisterController::class, 'appeal_case'])->name('appeal');
            Route::get('complete_case', [GovCaseRegisterController::class, 'complete_case'])->name('complete');
            Route::get('govt_not_against_case', [GovCaseRegisterController::class, 'govt_not_against_case'])->name('not_against');
            Route::get('govt_against_case', [GovCaseRegisterController::class, 'govt_against_case'])->name('against');
            
            Route::get('division_wise/{id}', [GovCaseRegisterController::class, 'division_wise'])->name('division_wise');
            Route::get('get_details', [GovCaseRegisterController::class, 'get_details'])->name('get_details');
            Route::get('ministry_wise_list/{id}', [GovCaseRegisterController::class, 'ministry_wise_list'])->name('ministry_wise_list');
            Route::get('department_wise_list/{id}', [GovCaseRegisterController::class, 'department_wise_list'])->name('department_wise_list');
            // Route::get('ministry_wise_gov_list/{id}/{id2}', [GovCaseRegisterController::class, 'ministry_wise_gov_list'])->name('ministry_wise_gov_list');
           

            Route::get('create', [GovCaseRegisterController::class, 'create'])->name('create');
            Route::get('create_appeal/{id}', [GovCaseRegisterController::class, 'create_appeal'])->name('create_appeal');
            Route::post('store', [GovCaseRegisterController::class, 'store'])->name('store');
            Route::post('store_appeal/{id}', [GovCaseRegisterController::class, 'store_appeal'])->name('store_appeal');
            Route::get('edit/{id}', [GovCaseRegisterController::class, 'edit'])->name('edit');
            Route::get('details/{id}', [GovCaseRegisterController::class, 'show'])->name('details');
            Route::get('register/{id}', [GovCaseRegisterController::class, 'register'])->name('register');
            Route::post('getCaseCategory/{id}', [GovCaseRegisterController::class, 'getCaseCategory'])->name('getCaseCategory');
            route::post('ajax_badi_del/{id}', [GovCaseRegisterController::class , 'ajax_badi_del']);
            route::post('ajax_bibadi_del/{id}', [GovCaseRegisterController::class , 'ajax_bibadi_del']);
            route::post('ajax_case_file_del/{id}', [GovCaseRegisterController::class , 'ajax_case_file_del']);

            Route::group(['prefix' => 'action/', 'as' => 'action.'], function () {
                Route::get('receive/{id}', [GovCaseActionController::class, 'receive'])->name('receive');
                Route::get('details/{id}', [GovCaseActionController::class, 'details'])->name('details');
                Route::post('forward', [GovCaseActionController::class, 'store'])->name('forward');
                Route::post('createsf', [GovCaseActionController::class, 'create_sf'])->name('createsf');
                Route::post('editsf', [GovCaseActionController::class, 'edit_sf'])->name('editsf');
                Route::post('hearingadd', [GovCaseActionController::class, 'hearing_store'])->name('hearingadd');
                Route::post('file_store_hearing', [GovCaseActionController::class, 'file_store_hearing'])->name('file_store_hearing');
                Route::post('hearing_result_upload', [GovCaseActionController::class, 'hearing_result_upload'])->name('hearing_result_upload');
                Route::post('result_update', [GovCaseActionController::class, 'result_update'])->name('result_update');
                Route::get('pdf_sf/{id}', [GovCaseActionController::class, 'pdf_sf'])->name('pdf_sf');
                Route::get('testpdf', [GovCaseActionController::class, 'test_pdf'])->name('testpdf');
                Route::post('file_store', [GovCaseActionController::class, 'file_store'])->name('file_store');
                Route::post('file_save', [GovCaseActionController::class, 'file_save']);
                Route::get('getDependentCaseStatus/{id}', [GovCaseActionController::class, 'getDependentCaseStatus']);
            });
        });
    });
});
