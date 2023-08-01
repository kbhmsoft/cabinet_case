<?php

use App\Http\Controllers\gov_case\GovCaseActionController;
use App\Http\Controllers\gov_case\GovCaseUserNotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RM_CaseActionController;
use App\Http\Controllers\gov_case\GovCaseRegisterController;
use App\Http\Controllers\gov_case\GovCaseUserManagementController;
use App\Http\Controllers\gov_case\GovCaseOfficeController;
use App\Http\Controllers\gov_case\GovCaseMessageController;
use App\Http\Controllers\gov_case\GovCaseNoticeController;
use App\Http\Controllers\gov_case\GovCaseSettingsController;
use App\Http\Controllers\gov_case\GovCaseOtherActionController;
use App\Http\Controllers\gov_case\GovCaseActivityLogController;
use App\Http\Controllers\gov_case\SumpremCourtController;
use App\Http\Controllers\gov_case\AclController;


Route::middleware('auth')->group(function () {

   /////************** Supream Court **************/////

    Route::get('/search/supremecourt/case', [SumpremCourtController::class, 'search_case']);
    Route::post('/search/supremecourt/case/post/value', [SumpremCourtController::class, 'search_case_post_function'])->name('supremecourt.case.search.post.value');
    
    Route::get('/search/supremecourt/causelist', [SumpremCourtController::class, 'supremecourt_causelist']);
    
    Route::post('/search/supremecourt/cause/list', [SumpremCourtController::class, 'supremecourt_causelist_pull_data'])->name('supremecourt.cause.list.pull.data');
    
    //Route::get('/get/notification/supremecourt', [SumpremCourtController::class, 'supremecourt_get_notification']);
    
    Route::get('/show/notification/supremecourt', [SumpremCourtController::class, 'supremecourt_show_notification'])->name('show.notification.supremecourt');
    
    Route::get('/modal/case/details/view', [SumpremCourtController::class, 'modal_case_details_view'])->name('modal.case.details.view');
    


    Route::group(['prefix' => 'cabinet/', 'as' => 'cabinet.'], function () {

        Route::resource('user-management', GovCaseUserManagementController::class);
        /////************** Office Setting **************/////
        Route::get('/office', [GovCaseOfficeController::class, 'index'])->name('office');
        Route::get('/office/level/{level}', [GovCaseOfficeController::class, 'level_wise'])->name('office.level');
        Route::get('/office/parent/{parent}', [GovCaseOfficeController::class, 'parent_wise'])->name('office.parent');
        route::get('/office/create', [GovCaseOfficeController::class, 'create'])->name('office.create');
        Route::post('/office/save', [GovCaseOfficeController::class, 'store'])->name('office.save');
        route::get('/office/edit/{id}', [GovCaseOfficeController::class, 'edit'])->name('office.edit');
        route::post('/office/update', [GovCaseOfficeController::class, 'update'])->name('office.update');
        route::get('/office/dropdownlist/getdependentdistrict/{id}', [GovCaseOfficeController::class , 'getDependentDistrict']);
        route::get('/office/dropdownlist/getdependentupazila/{id}', [GovCaseOfficeController::class , 'getDependentUpazila']);
        route::get('/office/dropdownlist/getdependentoffice/{id}', [GovCaseOfficeController::class , 'getDependentOffice']);
        route::get('/office/dropdownlist/getdependentchildoffice/{id}', [GovCaseOfficeController::class , 'getDependentChildOffice']);
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
        Route::get('/notice/list', [GovCaseNoticeController::class, 'index'])->name('notice.list');
        Route::get('/notice/create', [GovCaseNoticeController::class, 'create'])->name('notice.create');
        Route::post('/notice/store', [GovCaseNoticeController::class, 'store'])->name('notice.store');
        Route::get('/notice/edit/{id}', [GovCaseNoticeController::class, 'edit'])->name('notice.edit');
        Route::get('/notice/show/{id}', [GovCaseNoticeController::class, 'show'])->name('notice.show');
        Route::post('/notice/update', [GovCaseNoticeController::class, 'update'])->name('notice.update');

        // ++++++++++++++>>>>>>>>>> custom ACL routes <<<<<<<<<<<<++++++++++++
        Route::get('/user-role-management', [AclController::class, 'roleManagement'])->name('roleManagement');
        Route::post('/store-user-role', [AclController::class, 'storeRole'])->name('storeRole');
        Route::post('/update-user-role', [AclController::class, 'updateRole'])->name('updateRole');
        Route::get('/delete-user-role/{id}', [AclController::class, 'roleItemDelete'])->name('roleItemDelete');

        // for permissions 
        Route::get('/user-permissions', [AclController::class, 'permissionManagement'])->name('permissionManagement');
        Route::post('/store-user-permission', [AclController::class, 'storePermission'])->name('storePermission');
        Route::post('/update-permission', [AclController::class, 'updatePermission'])->name('updatePermission');
        Route::get('/delete-user-permission/{id}', [AclController::class, 'permissionItemDelete'])->name('permissionItemDelete');


        // parent permission name
        Route::post('/parent-permission-name', [AclController::class, 'storePatentPermissionName'])->name('storePatentPermissionName');
        

        // give permission to users
        Route::get('/user-permission-management', [AclController::class, 'permissionToUserManagement'])->name('permissionToUserManagement');

        Route::get('/manage-user-permission/{user_id}', [AclController::class, 'userPermissionManage'])->name('userPermissionManage');

        Route::post('/update-user-permission', [AclController::class, 'storeUpdateUserPermissionAll'])->name('storeUpdateUserPermissionAll');
        

     

        // ++++++++++++++>>>>>>>>>> custom ACL routes <<<<<<<<<<<<++++++++++++







        Route::group(['prefix' => 'settings/', 'as' => 'settings.'], function () {
            Route::get('/category/list', [GovCaseSettingsController::class, 'div_category_index'])->name('category.list');
            Route::get('/category/add', [GovCaseSettingsController::class, 'div_category_add'])->name('category.add');
            Route::post('/category/store', [GovCaseSettingsController::class, 'div_category_store'])->name('category.store');
            Route::get('/category/edit/{id}', [GovCaseSettingsController::class, 'div_category_edit'])->name('category.edit');
            Route::post('/category/update}', [GovCaseSettingsController::class, 'div_category_update'])->name('category.update');
            Route::get('/category_type/list', [GovCaseSettingsController::class, 'div_category_type_index'])->name('category_type.list');
            Route::get('/category_type/add', [GovCaseSettingsController::class, 'div_category_type_add'])->name('category_type.add');
            Route::post('/category_type/store', [GovCaseSettingsController::class, 'div_category_type_store'])->name('category_type.store');
            Route::get('/category_type/edit/{id}', [GovCaseSettingsController::class, 'div_category_type_edit'])->name('category_type.edit');
            Route::post('/category_type/update', [GovCaseSettingsController::class, 'div_category_type_update'])->name('category_type.update');
            Route::get('/office_type/list', [GovCaseSettingsController::class, 'office_type_index'])->name('office_type.list');
            Route::get('/office_type/add', [GovCaseSettingsController::class, 'office_type_add'])->name('office_type.add');
            Route::post('/office_type/store', [GovCaseSettingsController::class, 'office_type_store'])->name('office_type.store');
            Route::get('/office_type/edit/{id}', [GovCaseSettingsController::class, 'office_type_edit'])->name('office_type.edit');
            Route::post('/office_type/update', [GovCaseSettingsController::class, 'office_type_update'])->name('office_type.update');
        });
        // Route::get('/new_sf_list', [GovCaseMessageController::class, 'newSFlist'])->name('newSFlist');
        // Route::get('/new_sf_details/{id}', [GovCaseMessageController::class, 'newSFdetails'])->name('newSFdetails');
        Route::get('/script', [GovCaseMessageController::class, 'script']);
        
        //=================== Message End ==================//

        Route::group(['prefix' => 'case/', 'as' => 'case.'], function () {

            route::get('/dropdownlist/getdependentmindept/{id}', [GovCaseRegisterController::class , 'getdependentMinDept']);
            route::get('/dropdownlist/getdependentconcernperson/{id}', [GovCaseRegisterController::class , 'getDependentConcernPerson']);
            Route::get('dropdownlist/getdependentcasecategorytype/{id}', [GovCaseRegisterController::class , 'getDependentCaseCategoryType']);
            Route::get('index', [GovCaseRegisterController::class, 'index'])->name('index');
            Route::get('highcourt', [GovCaseRegisterController::class, 'high_court_case'])->name('highcourt');
            Route::get('highcourt/running', [GovCaseRegisterController::class, 'high_court_running_case'])->name('highcourt.running');
            Route::get('highcourt/complete', [GovCaseRegisterController::class, 'high_court_complete_case'])->name('highcourt.complete');
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
            Route::post('sendingReplyStore', [GovCaseRegisterController::class, 'sendingReplyStore'])->name('sendingReplyStore');
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


            Route::group(['prefix' => 'othersaction/', 'as' => 'othersaction.'], function () {  
                Route::get('againstgov', [GovCaseOtherActionController::class, 'againstGovCaseIndex'])->name('againstgov');
                Route::get('againstgovedit/{id}', [GovCaseOtherActionController::class, 'againstGovCaseEdit'])->name('againstgovedit');
                Route::post('againstgovstore', [GovCaseOtherActionController::class, 'againstGovCaseStore'])->name('againstgovstore');
                    //===========================//
                Route::get('senttosol', [GovCaseOtherActionController::class, 'sentToSolCaseIndex'])->name('senttosol');
                Route::get('senttosoledit/{id}', [GovCaseOtherActionController::class, 'sentToSolCaseEdit'])->name('senttosoledit');
                Route::post('senttosolstore', [GovCaseOtherActionController::class, 'sentToSolCaseStore'])->name('sentToSolCaseStore');
                    //===========================//
                Route::get('senttoagfromsol', [GovCaseOtherActionController::class, 'sentToAgFromSolCaseIndex'])->name('senttoagfromsol');
                Route::get('senttoagfromsoledit/{id}', [GovCaseOtherActionController::class, 'sentToAgFromSolCaseEdit'])->name('senttoagfromsoledit');
                Route::post('senttoagfromsolstore', [GovCaseOtherActionController::class, 'sentToAgFromSolCaseStore'])->name('senttoagfromsolstore');
                    //===========================//
                Route::get('stepnottakenAgainstpostpondorder', [GovCaseOtherActionController::class, 'stepNotTakenAgainstPostpondOrderCaseIndex'])->name('stepnottakenAgainstpostpondorder');
                Route::get('stepnottakenAgainstpostpondorderedit/{id}', [GovCaseOtherActionController::class, 'stepNotTakenAgainstPostpondOrderCaseEdit'])->name('stepnottakenAgainstpostpondorderedit');
                Route::post('stepnottakenAgainstpostpondorderstore', [GovCaseOtherActionController::class, 'stepNotTakenAgainstPostpondOrderCaseStore'])->name('stepnottakenAgainstpostpondorderstore');
            });  



        });

        

            //============ Case Activity Log Start ==============//
        Route::get('/case_audit', [GovCaseActivityLogController::class, 'index'])->name('case_audit.index');
        Route::get('/case_audit/details/{id}', [GovCaseActivityLogController::class, 'show'])->name('case_audit.show');
        Route::get('/case_audit/case_details/{id}', [GovCaseActivityLogController::class, 'reg_case_details'])->name('case_audit.reg_case_details');
        Route::get('/case_audit/against_gov_case_log_details/{id}', [GovCaseActivityLogController::class, 'against_gov_case_log_details'])->name('case_audit.against_gov_case_log_details');
        Route::get('/case_audit/sent_to_solcase_log_details/{id}', [GovCaseActivityLogController::class, 'sent_to_solcase_log_details'])->name('case_audit.sent_to_solcase_log_details');
        Route::get('/case_audit/sent_to_ag_from_solcase_log_details/{id}', [GovCaseActivityLogController::class, 'sent_to_ag_from_solcase_log_details'])->name('case_audit.sent_to_ag_from_solcase_log_details');
        Route::get('/case_audit/appeal_against_postpond_order_case_log_details/{id}', [GovCaseActivityLogController::class, 'appeal_against_postpond_order_case_log_details'])->name('case_audit.appeal_against_postpond_order_case_log_details');
            //============ Case Activity Log End ==============//
    });
        Route::get('/case_audit/pdf-Log/{id}', [GovCaseActivityLogController::class, 'caseActivityPDFlog'])->name('case_audit.caseActivityPDFlog');
        Route::get('/case_audit/sf/details/{id}', [GovCaseActivityLogController::class, 'sflog_details'])->name('case_audit.sf.details');
});
