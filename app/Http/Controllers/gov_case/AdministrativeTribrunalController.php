<?php

namespace App\Http\Controllers\gov_case;

use App\Models\Role;
use App\Models\User;
use App\Models\Court;
use App\Models\Office;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\gov_case\GovCaseLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\gov_case\GovCaseBadi;
use Illuminate\Support\Facades\Auth;
use App\Models\gov_case\AppealAdalat;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseRegister;

use App\Models\gov_case\HighcourtAdalat;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Repositories\gov_case\AttachmentRepository;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Models\gov_case\AdministrativeTribrunalCaseRegister;

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
        $data['mainRespondentMinistrys'] = GovCaseOffice::where('doptor_office_id', $officeID)->get();

        $data['highCourtAdalat'] = HighcourtAdalat::get();

        $data['concern_person_desig'] = Role::where('id', 45)->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['page_title'] = 'প্রশাসনিক ট্রাইব্যুনাল মামলা এন্ট্রি ';

        return view('gov_case.administritive_tribrunal.create_new_administritive_tribrunal')->with($data);
    }

    public function administrativeTribrunalGeneralInfo(Request $request)
    {
       dd($request->all());
        $exists =  AdministrativeTribrunalCaseRegister::where('case_no', $request->input('case_no'))
            ->where('year', $request->input('case_year'))
            ->where('case_category_type', $request->input('case_category_type'))
            ->whereNull('deleted_at')
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
                    AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
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
