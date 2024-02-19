<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationFormAsMainDefendentRequest;
use App\Http\Requests\UpdateApplicationFormAsMainDefendentRequest;
use App\Models\ApplicationFormAsMainDefendent;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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
        // $cases = ApplicationFormAsMainDefendent::with('court')->paginate(10);

        // $court = new Court();

        // foreach ($cases as $key => $value) {
        //     $court = Court::find($value->court);
        //     if ($court) {
        //         $value->court_name = $court->court_name;
        //     }
        // }

        // return view('gov_case.case_register.application_form_as_main_defendent.index', compact('cases'));

        // Retrieve cases for আপিল বিভাগ (court id: 1)
        $appealCases = ApplicationFormAsMainDefendent::where('court', 1)->paginate(10);

        // Retrieve cases for হাইকোর্ট বিভাগ (court id: 2)
        $highcourtCases = ApplicationFormAsMainDefendent::where('court', 2)->paginate(10);
        /*  $value =
        dd($value); */
        $category = $request->input('category');

        $appealCases->load('court');
        $highcourtCases->load('court');

        // return view('gov_case.case_register.application_form_as_main_defendent.index', compact('appealCases', 'highcourtCases', 'category'));
        return view('gov_case.case_register.application_form_as_main_defendent.index', [

            'appealCases' => $appealCases,
            'highcourtCases' => $highcourtCases,
            'appealPagination' => $appealCases->appends(request()->except('page')),
            'highcourtPagination' => $highcourtCases->appends(request()->except('page')),
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createApplicationForm($caseNo)
    {
        $data = [];

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

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
        // dd($request->all());
        $validatedData = $request->validated();
        // $officeID = userInfo()->office_id;
        $authUserOfficeId = Auth()->user()->office_id;
        $applicationForm = new ApplicationFormAsMainDefendent([
            'court' => $validatedData['court'],
            'case_no' => $validatedData['case_no'],
            'case_category' => $validatedData['case_category'],
            'case_category_type' => $validatedData['case_category_type'],
            'main_defendant_comments' => $validatedData['main_defendant_comments'],
            'additional_comments' => $validatedData['additional_comments'],
            'main_defendant_pdf' => $validatedData['main_defendant_pdf'],
            'office_id' => $authUserOfficeId,
        ]);

        // $validatedData = $request->validated();
        if ($request->hasFile('main_defendant_pdf')) {
            $filePath = $request->file('main_defendant_pdf')->store('MainDefendantPDFs', 'public');
            $validatedData['main_defendant_pdf'] = $filePath;
        }

        // $applicationForm->save();
        $applicationForm = new ApplicationFormAsMainDefendent($validatedData);
        $applicationForm->save();

        return redirect()->route('cabinet.case.indexApplications')->with('success', 'সফলভাবে, আপনার উদ্দেশ্যে ও লক্ষ্য তৈরি করা হয়েছে।');
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
}
