<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Court;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseHearing;
use App\Models\Office;
use App\Models\User;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseLogRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GovCaseRegisterController extends Controller
{
    //

    public function index()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;

        $query =  GovCaseRegister::orderby('id','DESC');

        if(!empty($_GET['date_start'])  && !empty($_GET['date_end'])){
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo =  date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('.case_date', [$dateFrom, $dateTo]);
        }

        if(!empty($_GET['case_no'])) {
            $query->where('r_m__case_rgisters.case_no','=',$_GET['case_no']);
        }
        if(!empty($_GET['division'])) {
            $query->where('r_m__case_rgisters.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('r_m__case_rgisters.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('r_m__case_rgisters.upazila_id','=',$_GET['upazila']);
        }
        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

          // Dorpdown

        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';
        // return $atcases;
        // return $data['cases'];
        // dd($data['cases']);
        return view('gov_case.case_register.index')->with($data);
    }
     public function create()
    {
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $data['ministrys'] = Office::whereIn('level', [8,9])->get();

        $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['courts'] = DB::table('court')
                ->select('id', 'court_name')
                ->whereIn('id',[1,2])
                ->get();
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['page_title'] = 'নতুন মামলা রেজিষ্টার এন্ট্রি ফরম'; //exit;
        // dd($data);
        return view('gov_case.case_register.create')->with($data);
    }

    public function store(Request $request)
    {
        // return $request;
        $caseId = $request->caseId;
        // 'email' => 'unique:users,email_address,'.$user->id

        $request->validate([
            'case_no' => 'required|unique:gov_case_registers,case_no,'.$caseId,
            ],
            [
            'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',

        ]);
        try{
            $caseId =GovCaseRegisterRepository::storeGovCase($request);
            GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);
            // dd($caseId);
            GovCaseLogRepository::storeGovCaseLog($caseId);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
            }
        } catch (\Exception $e){
               dd($e);
               $flag='false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
           }
        return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }
    public function edit($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['courts'] = Court::select('id', 'court_name')->get();
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['page_title'] =   'মামলা সংশোধন';
        // return $data;
        return view('gov_case.case_register.edit')->with($data);
    }

    public function create_appeal($id)
    {

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['courts'] = Court::select('id', 'court_name')->get();
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['page_title'] =   'আপিল মামলা এন্ট্রি ফরম'; //exit;
        // dd($data);
        return view('gov_case.case_register.creat_appeal')->with($data);
    }

    public function store_appeal(Request $request, $id = '')
    {
        // return $request;
        $caseId = $request->caseId;
        // dd($caseId);
        // 'email' => 'unique:users,email_address,'.$user->id

        $request->validate([
            'case_no' => 'required|unique:gov_case_registers,case_no,'.$caseId,
            ],
            [
            'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',

            ]);
        try{
            $caseId =GovCaseRegisterRepository::storeAppealGovCase($request,$id);
            // dd($caseId);
            GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);
            GovCaseLogRepository::storeGovCaseLog($caseId);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
            }
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

    public function show($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['page_title'] =   'মামলার বিস্তারিত তথ্য';
        // return $atcases;
        return view('gov_case.case_register.show')->with($data);
    }

    public function ajax_badi_del($id)
    {
        $badi = GovCaseBadi::find($id);
        $badi->delete();
        return Response()->json(["success" => 'সফলভাবে বাদীর তথ্য  মুছে ফেলা হয়েছে']);
    }
    public function ajax_bibadi_del($id)
    {
        $bibadi = GovCaseBibadi::find($id);
        $bibadi->delete();
        return Response()->json(["success" => 'সফলভাবে বিবাদীর তথ্য  মুছে ফেলা হয়েছে']);
    }
    public function ajax_case_file_del($id)
    {
        $res = AttachmentRepository::deleteFileByFileID($id);
        if($res == false){
            return Response()->json(["error" => 'Something Went Wrong']);
        }
        return Response()->json(["success" => 'সফলভাবে তথ্য  মুছে ফেলা হয়েছে']);
    }
}
