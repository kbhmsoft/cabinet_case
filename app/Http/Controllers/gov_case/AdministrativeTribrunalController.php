<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Court;
use App\Models\gov_case\AppealAdalat;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseLog;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\HighcourtAdalat;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdministrativeTribrunalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create_new_case', ['only' => ['create']]);
        $this->middleware('permission:appeal_division', ['only' => ['appellate_division_case']]);
        $this->middleware('permission:highcourt_case_update', ['only' => ['edit']]);
    }



    public function administrativeTribrunal_create()
    {
        session()->forget('currentUrlPath');

        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $data['ministrys'] = GovCaseOffice::get();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36, 45])->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategoryHighcourt'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 1)->get();

        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['appealCourtAdalat'] = AppealAdalat::get();

        $data['page_title'] = 'প্রশাসনিক ট্রাইব্যুনাল মামলা এন্ট্রি ';

        return view('gov_case.appeal_case_register.create_new_appeal')->with($data);
    }



}