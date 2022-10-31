<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseLog;
use App\Models\Office;
use App\Models\User;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseLogRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabinetController extends Controller
{
    //
     public function create()
    {
        $data['ministrys'] = Office::where('level', 9)->get();
        $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['courts'] = DB::table('court')
                ->select('id', 'court_name')
                // ->where('district_id', $userDistrict)
                ->get();
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        $data['GovCaseDivision'] = GovCaseDivision::all();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['page_title'] = 'নতুন মামলা রেজিষ্টার এন্ট্রি ফরম'; //exit;
        // return view('case.case_add', compact('page_title', 'case_type'));
        return view('cabinet.case_register.create')->with($data);
    }

    public function store(Request $request)
    {
        // $request = $request->all();
        // dd($request->caseId);
        // return $request;
        try{
            $caseId =GovCaseRegisterRepository::storeGovCase($request);
            // dd($caseId);
            GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);
            GovCaseLogRepository::storeGovCaseLog($caseId);
        } catch (\Exception $e){
               dd($e);
               $flag='false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
           }
        return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }
    public function getCaseCategory($id)
    {
        $categories = GovCaseDivisionCategory::orderby('id', 'desc')->where('gov_case_division_id', $id)->pluck("name_bn","id");
        return json_encode($categories);

    }

}
