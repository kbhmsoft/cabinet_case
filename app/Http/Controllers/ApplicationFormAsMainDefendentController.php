<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationFormAsMainDefendentRequest;
use App\Http\Requests\UpdateApplicationFormAsMainDefendentRequest;
use App\Models\ApplicationFormAsMainDefendent;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
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

        $data['page_title'] = 'হাইকোর্ট মামলা তালিকা';

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

        $data['users'] = $query->paginate(10)->withQueryString();
    //   dd($data['users']);
        $data['page_title'] = 'আপিল মামলা তালিকা';

        return view('gov_case.case_register.application_form_as_main_defendent.appeal_index')
            ->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createApplicationForm($caseNo)
    {
        $data = [];
        $data['GovCaseDivision'] = GovCaseDivision::all();

        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();

        $GovCaseDivisionCategoryType = GovCaseDivisionCategoryType::all();
        $data['GovCaseDivisionCategoryType'] = $GovCaseDivisionCategoryType;

        $data['caseNo'] = $caseNo;

        return view('gov_case.case_register.application_form_as_main_defendent.create')->with($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApplicationFormAsMainDefendentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeApplicationForm(StoreApplicationFormAsMainDefendentRequest $request)
    {

        //  dd($request->all());
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

}
