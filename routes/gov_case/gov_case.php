<?php

use App\Http\Controllers\ApplicationFormAsMainDefendentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\gov_case\AclController;
use App\Http\Controllers\gov_case\AdalatAppealController;
use App\Http\Controllers\gov_case\AdalatHighCourtController;
use App\Http\Controllers\gov_case\AppealGovCaseRegisterController;
use App\Http\Controllers\gov_case\GovCaseActionController;
use App\Http\Controllers\gov_case\GovCaseActivityLogController;
use App\Http\Controllers\gov_case\GovCaseMessageController;
use App\Http\Controllers\gov_case\GovCaseNoticeController;
use App\Http\Controllers\gov_case\GovCaseOfficeController;
use App\Http\Controllers\gov_case\GovCaseOtherActionController;
use App\Http\Controllers\gov_case\GovCaseRegisterController;
use App\Http\Controllers\gov_case\GovCaseSettingsController;
use App\Http\Controllers\gov_case\GovCaseUserManagementController;
use App\Http\Controllers\gov_case\GovCaseUserNotificationController;
use App\Http\Controllers\gov_case\SumpremCourtController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    /////************** Supream Court **************/////

    Route::get('/search/supremecourt/case', [SumpremCourtController::class, 'search_case']);
    Route::post('/search/supremecourt/case/post/value', [SumpremCourtController::class, 'search_case_post_function'])->name('supremecourt.case.search.post.value');

    Route::get('/search/supremecourt/causelist', [SumpremCourtController::class, 'supremecourt_causelist']);

    Route::post('/search/supremecourt/cause/list', [SumpremCourtController::class, 'supremecourt_causelist_pull_data'])->name('supremecourt.cause.list.pull.data');

    //Route::get('/get/notification/supremecourt', [SumpremCourtController::class, 'supremecourt_get_notification']);

    Route::get('/show/notification/supremecourt', [SumpremCourtController::class, 'supremecourt_show_notification'])->name('show.notification.supremecourt');

    Route::get('/modal/case/details/view', [SumpremCourtController::class, 'modal_case_details_view'])->name('modal.case.details.view');

    Route::get('cabinet/doptor/user-management', [GovCaseOfficeController::class, 'doptor_user_management'])->name('user-management');
    Route::post('cabinet/doptor/user/manage', [GovCaseOfficeController::class, 'doptor_user_office'])->name('doptor.user.manage');
    Route::post('cabinet/doptor/updateUserRole', [GovCaseOfficeController::class, 'doptorUpdateUserRole'])->name('doptor.updateUserRole');

    Route::group(['prefix' => 'cabinet/', 'as' => 'cabinet.'], function () {

        Route::resource('highcourt-maintain', AdalatHighCourtController::class);
        Route::resource('appeal-maintain', AdalatAppealController::class);

        /////************** User Management **************/////
        Route::resource('user-management', GovCaseUserManagementController::class);
        Route::get('/e-nothi-assigned-user-list', [GovCaseUserManagementController::class, 'assignedENothiUserManagement'])->name('assignedENothiUserManagement');
        /////************** Office Setting **************/////
        Route::get('/office', [GovCaseOfficeController::class, 'index'])->name('office');
        Route::get('/office/ministry', [GovCaseOfficeController::class, 'totalMinistryOffice'])->name('totalMinistryOffice');
        Route::get('/office/doptor', [GovCaseOfficeController::class, 'totalDoptor'])->name('totalDoptor');
        Route::get('/office/division', [GovCaseOfficeController::class, 'totalDivisionOffice'])->name('totalDivisionOffice');
        Route::get('/office/district', [GovCaseOfficeController::class, 'totalDistrictOffice'])->name('totalDistrictOffice');
        Route::get('/office/level/{level}', [GovCaseOfficeController::class, 'level_wise'])->name('office.level');
        Route::get('/office/parent/{parent}', [GovCaseOfficeController::class, 'parent_wise'])->name('office.parent');
        route::get('/office/create', [GovCaseOfficeController::class, 'create'])->name('office.create');
        Route::post('/office/save', [GovCaseOfficeController::class, 'store'])->name('office.save');
        route::get('/office/edit/{id}', [GovCaseOfficeController::class, 'edit'])->name('office.edit');
        route::post('/office/update', [GovCaseOfficeController::class, 'update'])->name('office.update');
        route::get('/office/dropdownlist/getdependentdistrict/{id}', [GovCaseOfficeController::class, 'getDependentDistrict']);
        route::get('/office/dropdownlist/getdependentupazila/{id}', [GovCaseOfficeController::class, 'getDependentUpazila']);
        route::get('/office/dropdownlist/getdependentoffice/{id}', [GovCaseOfficeController::class, 'getDependentOffice']);
        route::get('/office/dropdownlist/getdependentchildoffice/{id}', [GovCaseOfficeController::class, 'getDependentChildOffice']);
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
        Route::get('update/role-permissions/{id}', [ACLController::class, 'updateRolePermissions'])->name('updateRolePermissions');
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
        Route::post('/get-permissions-by-ajax', [AclController::class, 'getPermissionByAjax'])->name('getPermissionByAjax');

        //// ************** Doptor User List **************/////

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

        //=================== Message End ==================//highcourt/running

        Route::group(['prefix' => 'case/', 'as' => 'case.'], function () {

            // Same Case Entry
            Route::get('/get-case-categories', [ApplicationFormAsMainDefendentController::class, 'getCaseCategories'])->name('getCaseCategories');

            Route::get('createApplicationForm/{caseNo}', [ApplicationFormAsMainDefendentController::class, 'createApplicationForm'])->name('createApplicationForm');

            Route::post('storeApplicationForm', [ApplicationFormAsMainDefendentController::class, 'storeApplicationForm'])->name('storeApplicationForm');

            Route::get('indexApplications', [ApplicationFormAsMainDefendentController::class, 'indexApplications'])->name('indexApplications');

            Route::get('highcourt/indexApplications', [ApplicationFormAsMainDefendentController::class, 'indexApplications'])->name('highcourtIndexApplications');

            Route::get('appeal/indexApplications', [ApplicationFormAsMainDefendentController::class, 'appealIndexApplications'])->name('appealIndexApplications');

            Route::get('/editApplications/{id}/edit', [ApplicationFormAsMainDefendentController::class, 'editApplications'])->name('editApplications');

            // Route::get('/main-defendent', 'MainDefendentController@index')->name('main-defendent.index');

            route::get('/dropdownlist/getdependentmindept/{id}', [GovCaseRegisterController::class, 'getdependentMinDept']);
            route::get('/dropdownlist/getdependentconcernperson/{id}', [GovCaseRegisterController::class, 'getDependentConcernPerson']);
            Route::get('dropdownlist/getdependentcasecategorytype/{id}', [GovCaseRegisterController::class, 'getDependentCaseCategoryType']);
            Route::get('/dropdownlist/getdependentorigincasenumber/{id}', [GovCaseRegisterController::class, 'getDependentCaseOriginNumber']);
            Route::get('/origincasedetails/{id}', [GovCaseRegisterController::class, 'getOriginCaseDetails']);
            Route::get('/highcourtcasedetails/{id}', [GovCaseRegisterController::class, 'getHighCourtCaseDetails']);

            Route::get('index', [GovCaseRegisterController::class, 'index'])->name('index');
            Route::get('highcourt', [GovCaseRegisterController::class, 'high_court_case'])->name('highcourt');
            Route::get('ministryIdInsert', [GovCaseRegisterController::class, 'ministryIdInsert'])->name('ministryIdInsert');
            Route::get('totalHighcourt', [GovCaseRegisterController::class, 'totalHighcourt'])->name('totalHighcourt');
            Route::get('totalHighcourtRunning', [GovCaseRegisterController::class, 'totalHighcourtRunning'])->name('totalHighcourtRunning');
            Route::get('totalHighcourtComplete', [GovCaseRegisterController::class, 'totalHighcourtComplete'])->name('totalHighcourtComplete');
            Route::get('highcourt/five_years_running', [GovCaseRegisterController::class, 'fiveYearsRunningHighCourt'])->name('fiveYearsRunningHighCourt');
            Route::get('highcourt/running', [GovCaseRegisterController::class, 'high_court_running_case'])->name('highcourt.running');
            Route::get('highcourt/complete', [GovCaseRegisterController::class, 'high_court_complete_case'])->name('highcourt.complete');
            Route::get('highcourt/sent-To-SolicitorPending', [GovCaseRegisterController::class, 'sentToSolicitorPending'])->name('highcourt.sentToSolicitorPending');
            Route::get('highcourt/pending-postpondOrder', [GovCaseRegisterController::class, 'pendingPostpondOrder'])->name('highcourt.pendingPostpondOrder');

            Route::get('highcourt/sentToSolicitor', [GovCaseRegisterController::class, 'sentToSolicitor'])->name('sentToSolicitor');
            // Route::get('highcourt/appealAgainstGovt', [GovCaseRegisterController::class, 'appealAgainstGovt'])->name('appealAgainstGovt');
            Route::get('highcourt/againstPostponedOrder', [GovCaseRegisterController::class, 'againstPostponedOrder'])->name('againstPostponedOrder');
            Route::get('appellateDivision/running', [AppealGovCaseRegisterController::class, 'appellate_division_running_case'])->name('appellateDivision.running');
            Route::get('appealCaseAgainstGovt', [GovCaseRegisterController::class, 'appealCaseAgainstGovt'])->name('appealCaseAgainstGovt');
            Route::get('againstCasePostponedOrder', [GovCaseRegisterController::class, 'againstCasePostponedOrder'])->name('againstCasePostponedOrder');

            Route::get('highcourt/contemptCaseList', [GovCaseRegisterController::class, 'contemptCaseList'])->name('contemptCaseList');
            Route::get('sentToSolicitorCase', [GovCaseRegisterController::class, 'sentToSolicitorCase'])->name('sentToSolicitorCase');
            Route::get('appellateDivision/complete', [AppealGovCaseRegisterController::class, 'appellate_division_complete_case'])->name('appellateDivision.complete');
            Route::get('appellateDivision/appeal-not-against-gov', [AppealGovCaseRegisterController::class, 'appellateNotAgainstGov'])->name('appellateDivision.notAgainstGov');
            Route::get('appellateDivision/appeal-against-gov', [AppealGovCaseRegisterController::class, 'appellateAgainstGov'])->name('appellateDivision.againstGov');
            Route::get('running_case', [GovCaseRegisterController::class, 'running_case'])->name('running');
            Route::get('appeal_case', [GovCaseRegisterController::class, 'appeal_case'])->name('appeal');
            Route::get('complete_case', [GovCaseRegisterController::class, 'complete_case'])->name('complete');
            Route::get('govt_not_against_case', [GovCaseRegisterController::class, 'govt_not_against_case'])->name('not_against');
            Route::get('govt_against_case', [GovCaseRegisterController::class, 'govt_against_case'])->name('against');
            Route::get('highcourt/not-against-gov', [GovCaseRegisterController::class, 'highcourtNotAgainstGov'])->name('highcourtNotAgainstGov');
            Route::get('highcourt/against-gov', [GovCaseRegisterController::class, 'highcourtAgainstGov'])->name('highcourtAgainstGov');
            Route::get('mostImportantHighcourtCase', [GovCaseRegisterController::class, 'mostImportantHighcourtCase'])->name('mostImportantHighcourtCase');

            Route::get('againstHighCourtCaseAppealPending', [GovCaseRegisterController::class, 'againstHighCourtCaseAppealPending'])->name('againstHighCourtCaseAppealPending');
            Route::get('sentToSolicitorCaseList', [GovCaseRegisterController::class, 'sentToSolicitorCaseList'])->name('sentToSolicitorCaseList');
            Route::get('postponedInterimOrderCaseList', [GovCaseRegisterController::class, 'postponedInterimOrderCaseList'])->name('postponedInterimOrderCaseList');

            Route::get('division_wise/{id}', [GovCaseRegisterController::class, 'division_wise'])->name('division_wise');
            Route::get('get_details', [GovCaseRegisterController::class, 'get_details'])->name('get_details');
            Route::get('ministry_wise_list/{id}', [GovCaseRegisterController::class, 'ministry_wise_list'])->name('ministry_wise_list');
            Route::get('department_wise_list/{id}', [GovCaseRegisterController::class, 'department_wise_list'])->name('department_wise_list');
            // Route::get('ministry_wise_gov_list/{id}/{id2}', [GovCaseRegisterController::class, 'ministry_wise_gov_list'])->name('ministry_wise_gov_list');
            Route::post('highcourtMostImportantSave', [GovCaseRegisterController::class, 'highcourtMostImportantSave'])->name('highcourtMostImportantSave');
            Route::get('highcourt/mostImportantCase', [GovCaseRegisterController::class, 'highcourtMostImportantCase'])->name('highcourtMostImportantCase');
            Route::post('highcourtImportantSave', [GovCaseRegisterController::class, 'highcourtImportantSave'])->name('highcourtImportantSave');
            Route::get('importgantCaseList', [GovCaseRegisterController::class, 'highcourtAppealMostImportantCase'])->name('highcourtAppealMostImportantCase');
            Route::get('highcourt-appeal/importgantCaseList', [GovCaseRegisterController::class, 'highcourtAppealImportantCase'])->name('highcourtAppealImportantCase');
            Route::get('highcourt/create', [GovCaseRegisterController::class, 'highcourt_create'])->name('highcourt.create');
            Route::post('check-case-no', [GovCaseRegisterController::class, 'checkCaseNo'])->name('check-case-no');
            Route::post('check-appeal-case-no', [AppealGovCaseRegisterController::class, 'checkAppealCaseNo'])->name('check_appeal_caseno');
            Route::get('highcourt/create/old', [GovCaseRegisterController::class, 'highcourt_old_case_create'])->name('highcourt.create.old');
            Route::get('appellateDivision/create', [GovCaseRegisterController::class, 'appellateDivision_create'])->name('appellateDivision.create');
            Route::get('appellateDivision/create/old', [GovCaseRegisterController::class, 'appellateDivision_old_case_create'])->name('appellateDivision.create.old');
            Route::get('create_appeal/{id}', [GovCaseRegisterController::class, 'create_appeal'])->name('create_appeal');
            Route::post('store', [GovCaseRegisterController::class, 'store'])->name('store');
            Route::post('storeGeneralInfo', [GovCaseRegisterController::class, 'storeGeneralInfo'])->name('storeGeneralInfo');
            Route::post('caseGeneralInfoForEdit', [GovCaseRegisterController::class, 'caseGeneralInfoForEdit'])->name('caseGeneralInfoForEdit');
            Route::post('sendingReplyStore', [GovCaseRegisterController::class, 'sendingReplyStore'])->name('sendingReplyStore');
            Route::post('suspensionOrderStore', [GovCaseRegisterController::class, 'suspensionOrderStore'])->name('suspensionOrderStore');
            Route::post('finalOrderStore', [GovCaseRegisterController::class, 'finalOrderStore'])->name('finalOrderStore');
            Route::post('leaveToAppealStore', [GovCaseRegisterController::class, 'leaveToAppealStore'])->name('leaveToAppealStore');
            Route::post('leaveToAppealAnswerStore', [GovCaseRegisterController::class, 'leaveToAppealAnswerStore'])->name('leaveToAppealAnswerStore');
            Route::post('contemptCaseStore', [GovCaseRegisterController::class, 'contemptCaseStore'])->name('contemptCaseStore');
            Route::post('contemptCaseStoreActionButton', [GovCaseRegisterController::class, 'contemptCaseStoreActionButton'])->name('contemptCaseStoreActionButton');
            Route::post('store_appeal/{id}', [GovCaseRegisterController::class, 'store_appeal'])->name('appeal_store');
            Route::get('highcourt/edit/{id}', [GovCaseRegisterController::class, 'highcourt_edit'])->name('highcourt_edit');
            Route::get('highcourt/case-application/{case_no}', [GovCaseRegisterController::class, 'editHighcourtCaseApplication'])->name('editHighcourtCaseApplication');
            Route::get('highcourt_case_delete/{id}', [GovCaseRegisterController::class, 'highcourt_case_delete'])->name('highcourt_case_delete');
            Route::get('appeal_case_delete/{id}', [AppealGovCaseRegisterController::class, 'appeal_case_delete'])->name('appeal_case_delete');
            Route::get('sending/reply/{id}', [GovCaseRegisterController::class, 'sendingReplyEdit'])->name('sendingReplyEdit');
            Route::get('suspension/order/{id}', [GovCaseRegisterController::class, 'suspensionOrderEdit'])->name('suspensionOrderEdit');
            Route::get('final/order/{id}', [GovCaseRegisterController::class, 'finalOrderEdit'])->name('finalOrderEdit');
            Route::get('contemptCaseIssue/{id}', [GovCaseRegisterController::class, 'contemptCaseIssue'])->name('contemptCaseIssue');
            Route::get('leave-to-appeal/create/{id}', [GovCaseRegisterController::class, 'leaveToAppealCreate'])->name('leaveToAppealCreate');
            Route::get('leave-to-appeal-answer/create/{id}', [GovCaseRegisterController::class, 'leaveToAppealAnswerCreate'])->name('leaveToAppealAnswerCreate');
            Route::get('details/{id}', [GovCaseRegisterController::class, 'show'])->name('details');
            Route::get('highcourtDetailsPdf/{id}', [GovCaseRegisterController::class, 'highcourtDetailsPdf'])->name('highcourtDetailsPdf');
            Route::get('highcourtRegisterPdf/{id}', [GovCaseRegisterController::class, 'highcourtRegisterPdf'])->name('highcourtRegisterPdf');
            Route::get('register/{id}', [GovCaseRegisterController::class, 'register'])->name('register');
            Route::post('getCaseCategory/{id}', [GovCaseRegisterController::class, 'getCaseCategory'])->name('getCaseCategory');
            route::post('ajax_badi_del/{id}', [GovCaseRegisterController::class, 'ajax_badi_del']);
            route::post('ajax_bibadi_del/{id}', [GovCaseRegisterController::class, 'ajax_bibadi_del']);
            route::post('ajax_case_file_del/{id}', [GovCaseRegisterController::class, 'ajax_case_file_del']);

            // For Attorney Office
            Route::get('attroneyHighcourt', [GovCaseRegisterController::class, 'attorney_high_court_case'])->name('attroneyHighcourt');
            Route::get('attorney/highcourt/running', [GovCaseRegisterController::class, 'attorney_high_court_running_case'])->name('attorney.highcourt.running');
            Route::get('attorney/highcourt/complete', [GovCaseRegisterController::class, 'attorney_high_court_complete_case'])->name('attorney.highcourt.complete');
            Route::get('attorney/appellateDivision', [AppealGovCaseRegisterController::class, 'attorney_appellate_division_case'])->name('attorney.appellateDivision');
            Route::get('attorney/appellateDivision/running', [AppealGovCaseRegisterController::class, 'attorney_appellate_division_running_case'])->name('attorney.appellateDivision.running');
            Route::get('attorney/appellateDivision/complete', [AppealGovCaseRegisterController::class, 'attoney_appellate_division_complete_case'])->name('attorney.appellateDivision.complete');
            Route::get('attorney/highcourt/mostImportantCase', [GovCaseRegisterController::class, 'attorneyHighcourtMostImportantCase'])->name('attorneyHighcourtMostImportantCase');
            Route::get('attorney/appellateDivision/mostImportantCase', [AppealGovCaseRegisterController::class, 'attorneyHighcourtMostImportantCase'])->name('attorneyAppellateDivisionMostImportantCase');
            Route::get('attorney/appellateDivision', [AppealGovCaseRegisterController::class, 'attorneyAppellateDivision'])->name('attorney.appellateDivision');

            // for appeal controller route
            Route::post('appeal_store', [AppealGovCaseRegisterController::class, 'appealStore'])->name('appealStore');
            Route::post('appeal_store/edit', [AppealGovCaseRegisterController::class, 'appealEditStore'])->name('appealEditStore');
            Route::post('appealFinalOrderStore', [AppealGovCaseRegisterController::class, 'appealFinalOrderStore'])->name('appealFinalOrderStore');
            Route::post('completeAppealCaseStore', [AppealGovCaseRegisterController::class, 'completeAppealCaseStore'])->name('completeAppealCaseStore');
            Route::get('editAppealCaseForm/{id}', [AppealGovCaseRegisterController::class, 'editAppealCaseForm'])->name('editAppealCaseForm');
            Route::get('ministryWiseData/{ministry_id}', [DashboardController::class, 'ministryWiseData'])->name('ministryWiseData');
            // Route::get('highcourt/five_years_running', [GovCaseRegisterController::class, 'fiveYearsRunningHighCourt'])->name('fiveYearsRunningHighCourt');
            Route::get('totalAppellateDivision', [AppealGovCaseRegisterController::class, 'totalAppellateDivision'])->name('totalAppellateDivision');
            Route::get('appellateDivisionRunning', [AppealGovCaseRegisterController::class, 'appellateDivisionRunning'])->name('appellateDivisionRunning');
            Route::get('appellateDivisionComplete', [AppealGovCaseRegisterController::class, 'appellateDivisionComplete'])->name('appellateDivisionComplete');
            Route::post('appealMostImportantSave', [AppealGovCaseRegisterController::class, 'appealMostImportantSave'])->name('appealMostImportantSave');
            Route::post('appealImportantSave', [AppealGovCaseRegisterController::class, 'appealImportantSave'])->name('appealImportantSave');
            Route::get('appealCaseDetails/{id}', [AppealGovCaseRegisterController::class, 'appealCaseShow'])->name('appealCaseDetails');
            Route::get('appealDetailsPdf/{id}', [AppealGovCaseRegisterController::class, 'appealDetailsPdf'])->name('appealDetailsPdf');
            Route::get('appellateDivision/mostImportantCase', [AppealGovCaseRegisterController::class, 'appellateDivisionMostImportantCase'])->name('appellateDivisionMostImportantCase');
            Route::get('mostImportantAppealCase', [AppealGovCaseRegisterController::class, 'mostImportantAppealCase'])->name('mostImportantAppealCase');
            Route::get('appellateDivision', [AppealGovCaseRegisterController::class, 'appellate_division_case'])->name('appellateDivision');
            Route::get('appellateDivision/five_years_appeal_running', [AppealGovCaseRegisterController::class, 'fiveYearsRunningAppealCase'])->name('fiveYearsRunningAppealCase');
            Route::get('appeal/final/order/{id}', [AppealGovCaseRegisterController::class, 'appealFinalOrderEdit'])->name('appealFinalOrderEdit');
            Route::get('appeal/case-application/{case_no}', [AppealGovCaseRegisterController::class, 'editAppealCaseApplication'])->name('editAppealCaseApplication');

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
