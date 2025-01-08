<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\gov_case\AdministrativeTribrunalAdalat;
use App\Models\gov_case\AdministrativeTribrunalBibadi;
use App\Models\gov_case\AdministrativeTribrunalCaseRegister;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseOffice;
use App\Models\Role;
use App\Models\User;
use App\Repositories\gov_case\AdministrativeTribrunalRepository;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdministrativeTribrunalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create_new_case', ['only' => ['create']]);
        $this->middleware('permission:appeal_division', ['only' => ['appellate_division_case']]);
        $this->middleware('permission:highcourt_case_update', ['only' => ['edit']]);
    }

    public function administrativeTribrunal(Request $request)
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $finalOfficeIds = $this->getTwoLevelOfficeIds([$officeID]);
        $query = AdministrativeTribrunalCaseRegister::orderby('id', 'DESC')->where('deleted_at', '=', null);

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



        if (!empty($_GET['case_no'])) {
            $query->where('administrative_tribrunal_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);


        $data['page_title'] = 'প্রশাসনিক ট্রাইব্যুনালে সরকারি স্বার্থসংশ্লিষ্ট মামলার তালিকা';

        return view('gov_case.administritive_tribrunal.administrativeTribrunal')->with($data);
    }

    public function administrativeTribrunal_create()
    {
        session()->forget('currentUrlPath');
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $data['ministrys'] = GovCaseOffice::get();
        $data['mainRespondentMinistrys'] = GovCaseOffice::where('doptor_office_id', $officeID)->get();

        $data['administrativeTribrunalAdalat'] = AdministrativeTribrunalAdalat::where('status', 1)->get();

        $data['concern_person_desig'] = Role::where('id', 45)->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();

        $data['page_title'] = 'প্রশাসনিক ট্রাইব্যুনাল মামলা এন্ট্রি ';

        return view('gov_case.administritive_tribrunal.create_new_administritive_tribrunal')->with($data);
    }

    public function administrativeTribrunalGeneralInfo(Request $request)
    {
        if ($request->input('case_category_type') == "এটি") {
            $caseCategory = 1;
        }

        $exists = AdministrativeTribrunalCaseRegister::where('case_no', $request->input('case_no'))
            ->where('case_year', $request->input('case_year'))
            ->where('case_category_type', $caseCategory)
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

    public function show($id)
    {
        $data = AdministrativeTribrunalRepository::AdministrativeTribrunalAllDetails($id);
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36, 45])->get();
        $data['usersInfo'] = User::all();

        // if ($data['case']->case_division_id == 2) {
        //     $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলার বিস্তারিত তথ্য';
        // } else {
        //     $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলার বিস্তারিত তথ্য';
        // }
        $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট প্রশাসনিক মামলার মামলার বিস্তারিত তথ্য';
        return view('gov_case.administritive_tribrunal.showDetails')->with($data);

    }

    public function administrative_tribrunal_case_delete($id)
    {

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $data = AdministrativeTribrunalCaseRegister::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('message', 'WORKS!');
    }

    public function checkCaseNo(Request $request)
    {
        $caseNo = $request->input('case_no');
        $caseYear = $request->input('case_year');
        if ($request->input('case_category_type') == "এটি") {
            $caseCategory = 1;
        }

        // Use a single query to check for the existence of the case
        $exists = AdministrativeTribrunalCaseRegister::where('case_no', $caseNo)
            ->where('case_year', $caseYear)
            ->where('case_category_type', $caseCategory)
            ->exists();

        if ($exists) {
            // Use a single query to fetch case details
            $case = AdministrativeTribrunalCaseRegister::where('case_no', $caseNo)
                ->where('case_year', $caseYear)
                ->where('case_category_type', $caseCategory)
                ->whereNull('deleted_at')
                ->first();

            $officeId = AdministrativeTribrunalBibadi::where('gov_case_id', $case->id)
                ->where('is_main_bibadi', 1)
                ->groupBy('gov_case_id')
                ->first();

            $officeName = GovCaseOffice::where('doptor_office_id', $officeId->respondent_id)->first();

            return response()->json(['exists' => $exists, 'officeName' => $officeName->office_name_bn]);
        }

        return response()->json(['exists' => false]);
    }

    public function getTwoLevelOfficeIds($parentOfficeIds, $maxLevels = 2)
    {
        $allOfficeIds = $parentOfficeIds;
        $currentLevelIds = $parentOfficeIds;
        $level = 1;
        while ($level <= $maxLevels) {
            // Get the child offices for the current level
            $childOfficeIds = DB::table('gov_case_office')
                ->whereIn('parent_office_id', $currentLevelIds)
                ->pluck('doptor_office_id')
                ->toArray();

            if (empty($childOfficeIds)) {
                break;
            }

            $allOfficeIds = array_merge($allOfficeIds, $childOfficeIds);

            // Set up the child IDs as the current level for the next iteration
            $currentLevelIds = $childOfficeIds;
            $level++;
        }

        return array_unique($allOfficeIds);
    }
}
