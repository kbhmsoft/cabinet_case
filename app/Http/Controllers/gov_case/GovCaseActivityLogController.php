<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Attachment;
use App\Models\Court;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseLog;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseHearing;
use App\Models\gov_case\GovCaseActivityLog;
use App\Models\Office;
use App\Models\User;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseLogRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;

class GovCaseActivityLogController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:case_audit_menu', ['only' => ['index']]);
         // $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:create', ['only' => ['roleManagement']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->forget('currentUrlPath');


        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC');

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas('bibadis',
                function ($query)use($officeID) {
                  $query->where('ministry_id', $officeID)->where('is_main_bibadi',1);
               }
            );
        }

        if(!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id','=',$_GET['case_division_id']);
        }

        if(!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id','=',$_GET['case_category_id']);
        }

        if(!empty($_GET['date_start'])  && !empty($_GET['date_end'])){
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo =  date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('.case_date', [$dateFrom, $dateTo]);
        }

        if(!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no','=',$_GET['case_no']);
        }


        $data['cases'] = $query->paginate(10);

        // echo "<pre>"; print_r($data['cases']); exit();

          // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();


        $data['page_title'] =   'মামলা নিরীক্ষা';
        // return $atcases;
        // return $data;
        // dd($data['cases']);
        return view('gov_case.activity_log.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        if ($data['case']->case_division_id == 2) {
            $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলা সম্পর্কিত রেজিস্টার এর নিরীক্ষা';
        }else{
            $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট আপিল বিভাগের মামলা সম্পর্কিত রেজিস্টার এর নিরীক্ষা';
        }

        $data['caseActivityLogs'] = GovCaseActivityLog::where('gov_case_id',$id)->orderby('id', 'DESC')->get();
        // return $data;
        return view('gov_case.activity_log.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reg_case_details($id)
    {
        $data['caseActivityLog'] = GovCaseActivityLog::where('id', $id)->orderby('id', 'DESC')->first();

        $data['page_title'] = 'নিরীক্ষা মামলার বিস্তারিত তথ্য ';
        // return $data;
        return view('gov_case.activity_log.caseDetails')->with($data);
    }

    public function against_gov_case_log_details($id)
    {
        $data['caseActivityLog'] = GovCaseActivityLog::where('id', $id)->orderby('id', 'DESC')->first();

        $data['page_title'] = 'সরকারের বিপক্ষে রায় হওয়া মামলার নিরীক্ষার বিস্তারিত তথ্য ';
        // return $data;
        return view('gov_case.activity_log.caseResultDetails')->with($data);
    }

    public function sent_to_solcase_log_details($id)
    {
        $data['caseActivityLog'] = GovCaseActivityLog::where('id', $id)->orderby('id', 'DESC')->first();

        $data['page_title'] = 'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য হালনাগাদের নিরীক্ষার বিস্তারিত';
        // return $data;
        return view('gov_case.activity_log.sentToSolDetails')->with($data);
    }

    public function sent_to_ag_from_solcase_log_details($id)
    {
        $data['caseActivityLog'] = GovCaseActivityLog::where('id', $id)->orderby('id', 'DESC')->first();

        $data['page_title'] = 'সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদের নিরীক্ষার বিস্তারিত';
        // return $data;
        return view('gov_case.activity_log.sentSolToAgDetails')->with($data);
    }

    public function appeal_against_postpond_order_case_log_details($id)
    {
        $data['caseActivityLog'] = GovCaseActivityLog::where('id', $id)->orderby('id', 'DESC')->first();

        $data['page_title'] = 'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদের নিরীক্ষার বিস্তারিত';
        // return $data;
        return view('gov_case.activity_log.appalAgainstPostpondOrderDetails')->with($data);
    }

    public function caseActivityPDFlog($id){
        $data['info'] = DB::table('case_register')
        ->join('court', 'case_register.court_id', '=', 'court.id')
        ->leftJoin('users', 'case_register.gp_user_id', '=', 'users.id')
        ->join('division', 'case_register.division_id', '=', 'division.id')
        ->join('district', 'case_register.district_id', '=', 'district.id')
        ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
        ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
        ->join('role', 'case_register.action_user_group_id', '=', 'role.id')
        ->join('case_status', 'case_register.cs_id', '=', 'case_status.id')
        ->join('case_badi', 'case_register.id', '=', 'case_badi.case_id')
        ->join('case_bibadi', 'case_register.id', '=', 'case_bibadi.case_id')
        ->select('case_register.*', 'court.court_name','users.name', 'division.division_name_bn', 'district.district_name_bn', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn', 'case_status.status_name', 'role.role_name', 'case_badi.badi_name', 'case_badi.badi_spouse_name', 'case_badi.badi_address', 'case_bibadi.bibadi_name', 'case_bibadi.bibadi_spouse_name', 'case_bibadi.bibadi_address')
        ->where('case_register.id', '=', $id)
        ->first();

        $data['badis'] =DB::table('case_badi')
        ->join('case_register', 'case_badi.case_id', '=', 'case_register.id')
        ->select('case_badi.*')
        ->where('case_badi.case_id', '=', $id)
        ->get();

        $data['bibadis'] =DB::table('case_bibadi')
        ->join('case_register', 'case_bibadi.case_id', '=', 'case_register.id')
        ->select('case_bibadi.*')
        ->where('case_bibadi.case_id', '=', $id)
        ->get();

        $data['caseActivityLogs'] = GovCaseActivityLog::where('case_register_id', $id)->orderby('id', 'DESC')->get();

        $data['page_title'] = 'মামলার কার্যকলাপ নিরীক্ষার বিস্তারিত তথ্য'; //exit;

        $html = view('caseActivityLog.showPDF')->with($data);
         // Generate PDF
        $this->generatePDF($html);
    }

    public function generatePDF($html){
        $mpdf = new \Mpdf\Mpdf([
         'default_font_size' => 12,
         'default_font'      => 'kalpurush'
         ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
      }
}
