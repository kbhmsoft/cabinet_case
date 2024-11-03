<?php

namespace App\Http\Controllers\gov_case;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\HighcourtAdalat;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Repositories\gov_case\AttachmentRepository;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\AdministrativeTribrunalAdalat;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Models\gov_case\AdministrativeTribrunalCaseRegister;
use App\Models\gov_case\AppealAdministrativeTribrunalCaseRegister;

class AppealAdministrativeTribrunalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create_new_case', ['only' => ['create']]);
        $this->middleware('permission:appeal_division', ['only' => ['appellate_division_case']]);
        $this->middleware('permission:highcourt_case_update', ['only' => ['edit']]);
    }

    public function appealAdministrativeTribrunal()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = AppealAdministrativeTribrunalCaseRegister::orderby('id', 'DESC')->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            );
        }

        if ($roleID == 44 || $roleID == 45) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            );
        }

        $userId = Auth::id();
        if ($roleID == 45) {
            $query->whereHas(
                'concernPersons',
                function ($query) use ($userId) {
                    $query->where('concern_user_id', $userId);
                }
            );
        };

        if (!empty($_GET['case_category_type'])) {
            $query->where('administrative_tribrunal_case_registers.case_category_type', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('administrative_tribrunal_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        $data['page_title'] = 'প্রশাসনিক আপিল ট্রাইব্যুনালে সরকারি স্বার্থসংশ্লিষ্ট মামলার তালিকা';

        return view('gov_case.appeal_administritive_tribrunal.appealAdministrativeTribrunal')->with($data);
    }

    // public function appealAdministrativeTribrunal()
    // {
    //     session()->forget('currentUrlPath');
    //     $roleID = userInfo()->role_id;
    //     $officeID = userInfo()->office_id;

    //     $data['ministrys'] = GovCaseOffice::get();
    //     $data['mainRespondentMinistrys'] = GovCaseOffice::where('doptor_office_id', $officeID)->get();

    //     $data['highCourtAdalat'] = HighcourtAdalat::get();

    //     $data['concern_person_desig'] = Role::where('id', 45)->get();

    //     $data['courts'] = DB::table('court')
    //         ->select('id', 'court_name')
    //         ->whereIn('id', [1, 2])
    //         ->get();

    //     $data['GovCaseDivision'] = GovCaseDivision::all();
    //     $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
    //     $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
    //     $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

    //     $data['page_title'] = 'প্রশাসনিক আপিল ট্রাইব্যুনাল মামলা এন্ট্রি ';

    //     return view('gov_case.appeal_administritive_tribrunal.create_new_appeal_administritive_tribrunal')->with($data);
    // }

    public function administrativeTribrunalGeneralInfo(Request $request)
    {
        //    dd($request->all());
        $exists = AdministrativeTribrunalCaseRegister::where('case_no', $request->input('case_no'))
            ->where('case_year', $request->input('case_year'))
            ->where('case_category_type', $request->input('case_category_type'))
        // ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'মামলা নং, বছর, এবং মামলার শ্রেণী/কেস-টাইপ এই তিনটি মান সম্বলিত মামলা ইতিমধ্যে বিদ্যমান আছে'], 422);
        } else {
            DB::beginTransaction();

            try {
                $caseId = GovCaseRegisterRepository::storeAdministrativeTribrunalGeneralInfo($request);
                GovCaseBadiBibadiRepository::storeMainBibadiAdministritiveTribrunal($request, $caseId);
                GovCaseRegisterRepository::storeConcernPersonAdministritiveTribrunal($request, $caseId);
                GovCaseBadiBibadiRepository::storeBibadiAdministritiveTribrunal($request, $caseId);
                GovCaseRegisterRepository::storeAdministritiveTribrunalHighcourtAdalat($request, $caseId);

                GovCaseBadiBibadiRepository::storeAdministritiveTribrunalBadi($request, $caseId);

                if ($request->file_type && $_FILES["file_name"]['name']) {
                    AttachmentRepository::storeAdministrativeTribrunalAttachment('gov_case', $caseId, $request);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'তথ্য সংরক্ষণ করা হয়নি '], 500);
            }

            return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);
        }
    }

    public function appealAdministrativeTribrunal_create()
    {
        session()->forget('currentUrlPath');
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $data['ministrys'] = GovCaseOffice::get();
        $data['mainRespondentMinistrys'] = GovCaseOffice::where('doptor_office_id', $officeID)->get();

        $data['administrativeTribrunalAdalat'] = AdministrativeTribrunalAdalat::where('status',1)->get();

        $data['concern_person_desig'] = Role::where('id', 45)->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        // $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();
        $data['atCase'] = DB::table('administrative_tribrunal_case_registers')->select('id', 'case_no','case_year')
        ->where('deleted_at',null)->orderBy('id','desc')->get();
        // dd($data['atCase']);
        $data['page_title'] = 'প্রশাসনিক আপিল ট্রাইব্যুনাল মামলা এন্ট্রি ';

        return view('gov_case.appeal_administritive_tribrunal.create_new_appeal_administritive_tribrunal')->with($data);
    }

    public function appealAdministrativeTribrunalGeneralInfo(Request $request)
    {
        $exists = AppealAdministrativeTribrunalCaseRegister::where('case_no', $request->input('case_no'))
            ->where('case_year', $request->input('case_year'))
            ->where('case_category_type', $request->input('case_category_type'))
        // ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'মামলা নং, বছর, এবং মামলার শ্রেণী/কেস-টাইপ এই তিনটি মান সম্বলিত মামলা ইতিমধ্যে বিদ্যমান আছে'], 422);

        } else {
            DB::beginTransaction();

            try {
                $caseId = GovCaseRegisterRepository::storeAppealAdministrativeTribrunalGeneralInfo($request);
                // GovCaseBadiBibadiRepository::storeMainBibadiAppealAdministritiveTribrunal($request, $caseId);
                GovCaseRegisterRepository::storeConcernPersonAppealAdministritiveTribrunal($request, $caseId);
                // GovCaseBadiBibadiRepository::storeBibadiAppealAdministritiveTribrunal($request, $caseId);
                GovCaseRegisterRepository::storeAppealAdministritiveTribrunalHighcourtAdalat($request, $caseId);

                // GovCaseBadiBibadiRepository::storeAppealAdministritiveTribrunalBadi($request, $caseId);

                if ($request->file_type && $_FILES["file_name"]['name']) {
                    AttachmentRepository::storeAppealAdministrativeTribrunalAttachment('gov_case', $caseId, $request);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'তথ্য সংরক্ষণ করা হয়নি '], 500);
            }

            return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);
        }
    }

}
