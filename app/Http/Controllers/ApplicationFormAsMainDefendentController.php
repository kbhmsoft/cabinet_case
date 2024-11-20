<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateApplicationFormAsMainDefendentRequest;
use App\Models\ApplicationFormAsMainDefendent;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseRegister;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // dd()

        $query = ApplicationFormAsMainDefendent::with('office')->where('court', 2)->where('is_answered', null)

            ->orderBy('id', 'DESC');

            if (!empty($_GET['case_no'])) {
                $query->where('case_no', 'like', '%' . $_GET['case_no'] . '%');
            }
            if (!empty($_GET['office_id'])) {
                $query->where('office_id', '=', $_GET['office_id']);
            }
        $data['users'] = $query->paginate(10)->withQueryString();
        $data['ministrys'] = GovCaseOffice::get();
        $data['page_title'] = 'হাইকোর্ট মামলা তালিকা';

        // Counting the number of unique case numbers
        // $highCourtApplicationsCount = $query->distinct('case_no')->count('case_no');
        // // Passing the count to the view
        // $data['highCourtApplicationsCount'] = $highCourtApplicationsCount;

        return view('gov_case.case_register.application_form_as_main_defendent.index')
            ->with($data);
    }

    public function appealIndexApplications(Request $request)
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $query = ApplicationFormAsMainDefendent::with('office')->where('court', 1)->where('is_answered', null)
            ->orderBy('id', 'DESC');
            if (!empty($_GET['case_no'])) {
                $query->where('case_no', 'like', '%' . $_GET['case_no'] . '%');
            }
            if (!empty($_GET['office_id'])) {
                $query->where('office_id', '=', $_GET['office_id']);
            }
        $data['ministrys'] = GovCaseOffice::get();
        $data['users'] = $query->paginate(10)->withQueryString();
        //   dd($data['users']);
        $data['page_title'] = 'আপিল মামলা তালিকা';

        // Counting the number of unique case numbers
        // $appealCourtApplicationCount = $query->distinct('case_no')->count('case_no');
        // // Passing the count to the view
        // $data['appealCourtApplicationCount'] = $appealCourtApplicationCount;

        return view('gov_case.case_register.application_form_as_main_defendent.appeal_index')
            ->with($data);
    }

    public function createApplicationForm($caseNo, $caseYear, $caseCategory)
    {
        // dd([$caseNo, $caseYear, $caseCategory]);
        $data = [];
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['ministrys'] = GovCaseOffice::get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();

        $data['govCaseData'] = GovCaseRegister::where('case_no', $caseNo)
            ->where('year', $caseYear)
            ->where('case_type_id', $caseCategory)
            ->first();

        $data['appealGovCaseData'] = AppealGovCaseRegister::where('case_no', $caseNo)
            ->where('year', $caseYear)
            ->where('case_type_id', $caseCategory)
            ->first();
// dd($data['appealGovCaseData']);
        $GovCaseDivisionCategoryType = GovCaseDivisionCategoryType::all();
        $data['GovCaseDivisionCategoryType'] = $GovCaseDivisionCategoryType;

        $data['caseNo'] = $caseNo;

        return view('gov_case.case_register.application_form_as_main_defendent.create')->with($data);
    }

    public function storeApplicationForm(Request $request)
    {
        // $validatedData = $request->validated();
        $authUserOfficeId = Auth()->user()->office_id;

        if ($request->hasFile('main_defendant_pdf')) {
            $file = $request->file('main_defendant_pdf');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $filePath = $file->move(public_path('uploads/case_same_number'), $filename);
            $main_defendant_pdf = 'uploads/case_same_number/' . $filename;
        }
        // dd($request->all());
        $applicationForm = new ApplicationFormAsMainDefendent([
            'court' => $request->court,
            'case_no' => $request->case_no,
            'case_year' => $request->case_year,
            'case_category' => $request->case_category_hidden,
            'case_category_type' => $request->case_category_type_hidden,
            'main_defendant_comments' => $request->main_defendant_comments,
            'main_defendant_pdf' => $main_defendant_pdf,
            'office_id' => $authUserOfficeId,
        ]);

        $applicationForm->save();
        if ($request->court == 2) {
            $data['page_title'] = 'হাইকোর্ট মামলা তালিকা';
            return response()->json(['redirect' => route('dashboard')]);
        }

        if ($request->court == 1) {
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
        DB::beginTransaction();

        try {
            $caseNo = $request->case_no;
            $mainRespondent = $request->input('main_respondent');

            $govCaseId = GovCaseRegister::where('case_no', $request->input('case_no'))
                ->where('year', $request->input('case_year'))
                ->where('case_type_id', $request->input('case_category_type'))
                ->whereNull('deleted_at')->pluck('id')->first();

            $caseYear = $request->input('case_year');
            $caseIdQuery = ApplicationFormAsMainDefendent::where('case_no', $request->input('case_no'))
                ->where('case_year', $request->input('case_year'))
                ->where('case_category_type', $request->input('case_category_type'));

            if ($caseYear !== null) {
                $caseIdQuery->where('case_year', $caseYear);
            }

            $mainRespondentApplicationData = $caseIdQuery->first();
            $mainRespondentApplicationData->update(['is_answered' => 1]);
            // Store various case information
            GovCaseRegisterRepository::storeMainRespondentChangingGeneralInfo($request, $govCaseId);
            GovCaseBadiBibadiRepository::storeChangingMainBibadi($request, $govCaseId);
            GovCaseRegisterRepository::storeHighcourtAdalat($request, $govCaseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $govCaseId);
            GovCaseRegisterRepository::storeConcernPerson($request, $govCaseId);
            GovCaseBadiBibadiRepository::storeBadi($request, $govCaseId);

            // Store attachment
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeAttachment('gov_case', $govCaseId, $request);
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $govCaseId]);

        } catch (Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return response()->json(['error' => 'মামলার তথ্য সংরক্ষণ করতে ব্যর্থ হয়েছে', 'message' => $e->getMessage()], 500);
        }
    }

}