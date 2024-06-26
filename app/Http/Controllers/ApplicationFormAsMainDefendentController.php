<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationFormAsMainDefendentRequest;
use App\Http\Requests\UpdateApplicationFormAsMainDefendentRequest;
use App\Models\ApplicationFormAsMainDefendent;
use App\Models\Attachment;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseLog;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseRegister;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// use Illuminate\Routing\Route;

class ApplicationFormAsMainDefendentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexApplications(Request $request)
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $query = ApplicationFormAsMainDefendent::with('office')->where('court', 2)
            ->orderBy('id', 'DESC');

        $data['users'] = $query->paginate(10)->withQueryString();
        $data['ministrys'] = GovCaseOffice::get();
        $data['page_title'] = 'হাইকোর্ট মামলা তালিকা';

        // Counting the number of unique case numbers
        $highCourtApplicationsCount = $query->distinct('case_no')->count('case_no');
        // Passing the count to the view
        $data['highCourtApplicationsCount'] = $highCourtApplicationsCount;

        return view('gov_case.case_register.application_form_as_main_defendent.index')
            ->with($data);
    }

    public function appealIndexApplications(Request $request)
    {
        // dd('aaa');
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $query = ApplicationFormAsMainDefendent::with('office')->where('court', 1)
            ->orderBy('id', 'DESC');
        $data['ministrys'] = GovCaseOffice::get();
        $data['users'] = $query->paginate(10)->withQueryString();
        //   dd($data['users']);
        $data['page_title'] = 'আপিল মামলা তালিকা';

        // Counting the number of unique case numbers
        $appealCourtApplicationCount = $query->distinct('case_no')->count('case_no');
        // Passing the count to the view
        $data['appealCourtApplicationCount'] = $appealCourtApplicationCount;

        return view('gov_case.case_register.application_form_as_main_defendent.appeal_index')
            ->with($data);
    }

    public function createApplicationForm($caseNo)
    {
        $data = [];
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['ministrys'] = GovCaseOffice::get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['govCaseData'] = GovCaseRegister::where('case_no', $caseNo)
            ->first();

        $GovCaseDivisionCategoryType = GovCaseDivisionCategoryType::all();
        $data['GovCaseDivisionCategoryType'] = $GovCaseDivisionCategoryType;

        $data['caseNo'] = $caseNo;

        return view('gov_case.case_register.application_form_as_main_defendent.create')->with($data);
    }

    public function storeApplicationForm(StoreApplicationFormAsMainDefendentRequest $request)
    {
        $validatedData = $request->validated();
        $authUserOfficeId = Auth()->user()->office_id;

        if ($request->hasFile('main_defendant_pdf')) {
            $file = $request->file('main_defendant_pdf');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $filePath = $file->move(public_path('uploads/case_same_number'), $filename);
            $validatedData['main_defendant_pdf'] = 'uploads/case_same_number/' . $filename;
        }

        $applicationForm = new ApplicationFormAsMainDefendent([
            'court' => $validatedData['court'],
            'case_no' => $validatedData['case_no'],
            'case_category' => $validatedData['case_category'],
            'case_category_type' => $validatedData['case_category_type'],
            'main_defendant_comments' => $validatedData['main_defendant_comments'],
            'main_defendant_pdf' => $validatedData['main_defendant_pdf'],
            'office_id' => $authUserOfficeId,
        ]);

        $applicationForm->save();
        if ($validatedData['court'] == 2) {
            $data['page_title'] = 'হাইকোর্ট মামলা তালিকা';
            return response()->json(['redirect' => route('dashboard')]);
        }

        if ($validatedData['court'] == 1) {
            $data['page_title'] = 'আপিল মামলা তালিকা';
            return response()->json(['redirect' => route('dashboard')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApplicationFormAsMainDefendent  $applicationFormAsMainDefendent
     * @return \Illuminate\Http\Response
     */
    public function show($caseNo)
    {

        // return redirect()->route('createApplicationForm.show', ['caseNo' => $caseNo])->with('success', 'Form data stored successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApplicationFormAsMainDefendent  $applicationFormAsMainDefendent
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationFormAsMainDefendent $applicationFormAsMainDefendent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApplicationFormAsMainDefendentRequest  $request
     * @param  \App\Models\ApplicationFormAsMainDefendent  $applicationFormAsMainDefendent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApplicationFormAsMainDefendentRequest $request, ApplicationFormAsMainDefendent $applicationFormAsMainDefendent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApplicationFormAsMainDefendent  $applicationFormAsMainDefendent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationFormAsMainDefendent $applicationFormAsMainDefendent)
    {
        //
    }
    public function editApplications($id)
    {
        $data = [];
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $applicationFormAsMainDefendent = ApplicationFormAsMainDefendent::findOrFail($id);
        $GovCaseDivisionCategory = GovCaseDivisionCategory::all();
        $GovCaseDivisionCategoryType = GovCaseDivisionCategoryType::all();

        return view('gov_case.case_register.application_form_as_main_defendent.edit', compact('applicationFormAsMainDefendent', 'GovCaseDivisionCategory', 'GovCaseDivisionCategoryType', 'data'));
    }

    public function getCaseCategories(Request $request)
    {
        $courtId = $request->court_id;
        $caseCategories = GovCaseDivisionCategory::where('gov_case_division_id', $courtId)->get();

        $options = '<option value="">-- নির্বাচন করুন --</option>';
        foreach ($caseCategories as $category) {
            $options .= '<option value="' . $category->id . '">' . $category->name_bn . '</option>';
        }

        return $options;
    }

    public function caseMainRespondentFormForEdit(Request $request)
    {
        // dd($request->all());
        $caseNo = $request->case_no;

        $mainRespondent = $request->input('main_respondent');
        $govCaseId = GovCaseRegister::where('case_no', $caseNo)->pluck('id')->first();

        GovCaseRegisterRepository::storeMainRespondentChangingGeneralInfo($request, $govCaseId);

        GovCaseBadiBibadiRepository::storeChangingMainBibadi($request, $govCaseId);
        GovCaseRegisterRepository::storeHighcourtAdalat($request, $govCaseId);
        GovCaseBadiBibadiRepository::storeBibadi($request, $govCaseId);
        GovCaseRegisterRepository::storeConcernPerson($request, $govCaseId);

        GovCaseBadiBibadiRepository::storeBadi($request, $govCaseId);

        if ($request->file_type && $_FILES["file_name"]['name']) {
            AttachmentRepository::storeAttachment('gov_case', $govCaseId, $request);
        }
        // if ($request->reply_file_type && $_FILES["reply_file_name"]['name']) {
        //     AttachmentRepository::storeReplyAttachment('gov_case', $id, $request);
        // }
        // if ($request->suspension_file_type && $_FILES["suspension_file_name"]['name']) {
        //     AttachmentRepository::storeSuspentionOrderAttachment('gov_case', $id, $request);
        // }
        // if ($request->final_order_file_type && $_FILES["final_order_file_name"]['name']) {
        //     AttachmentRepository::storeFinalOrderAttachment('gov_case', $id, $request);
        // }
        // if ($request->contempt_file_type && $_FILES["contempt_file_name"]['name']) {
        //     AttachmentRepository::storeContemptAttachment('gov_case', $id, $request);
        // }

        //========= Gov Case Activity Log -  start ============
        $caseRegister = GovCaseRegister::findOrFail($govCaseId)->toArray();

        $caseRegisterData = array_merge($caseRegister, [
            'badi' => GovCaseBadi::where('gov_case_id', $govCaseId)->get()->toArray(),
            'bibadi' => GovCaseBibadi::where('gov_case_id', $govCaseId)->get()->toArray(),
            'attachment' => Attachment::where('gov_case_id', $govCaseId)->get()->toArray(),
            'log_data' => GovCaseLog::where('gov_case_id', $govCaseId)->get()->toArray(),
        ]);

        $cs_activity_data['case_register_id'] = $govCaseId;

        if ($request->formType != 'edit') {
            $cs_activity_data['activity_type'] = 'create';
            $cs_activity_data['message'] = 'নতুন মামলা রেজিস্ট্রেশন করা হয়েছে';
        } else {
            $cs_activity_data['activity_type'] = 'update';
            $cs_activity_data['message'] = 'মামলার তথ্য হালনাগাদ করা হয়েছে';
        }
        $cs_activity_data['old_data'] = null;
        $cs_activity_data['new_data'] = json_encode($caseRegisterData);
        gov_case_activity_logs($cs_activity_data);

        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $govCaseId]);
    }
}