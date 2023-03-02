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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }
        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // echo "<pre>"; print_r($data['cases']); exit();

          // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';
        // return $atcases;
        // return $data;
        // dd($data['cases']);
        return view('gov_case.case_register.index')->with($data);
    }

    public function high_court_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->where('case_division_id',2);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }
        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলা সম্পর্কিত রেজিস্টার';
        
        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function appellate_division_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::with('mainBibadis')->orderby('id','DESC')->where('case_division_id',1);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }
        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }
        $data['cases'] = $query->paginate(10);
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট আপিল বিভাগের মামলা সম্পর্কিত রেজিস্টার';
         // return $data;
        return view('gov_case.case_register.appealcourt')->with($data);
    }


    //============Running Case list============//
    public function running_case()
    {
        // dd(user_office_info());
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->whereIn('gov_case_registers.status',[1,2]);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }


    //============Appeal Case list============//
    public function appeal_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->where('case_division_id', 1)->where('status', '!=' , 3);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }


    //============Complete Case list============/
    public function complete_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->where('status',3);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }    


    //============Not Against Govt Case list============/
    public function govt_not_against_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->where('gov_case_registers.in_favour_govt',1);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }


    //============Against Govt Case list============/
    public function govt_against_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->where('status', 3)->where('gov_case_registers.in_favour_govt',0);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }


    //============Division Wise Case list============/
    public function division_wise($id)
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query =  GovCaseRegister::orderby('id','DESC')->where('gov_case_registers.case_division_id',$id)->where('status', '!=' , 3);

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
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['division_wise_id'] = $id;
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('role')->select('id', 'role_name')->get();


        $data['page_title'] =   'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.division_wise_list')->with($data);
    }

    //============Ministry Wise Case list============/
    public function ministry_wise_list($id)
    {
        // dd($id);


        $query = DB::table('office')
                ->select('office.id', 'office.office_name_bn', 'office.office_name_en',
                    \DB::raw('SUM(CASE WHEN gcr.status != "3" THEN 1 ELSE 0 END) AS running_case'),
                    \DB::raw('SUM(CASE WHEN gcr.status = "3" THEN 1 ELSE 0 END) AS completed_case'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" THEN 1 ELSE 0 END) AS against_gov'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
                )
                ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.department_id')
                ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
                ->where('office.parent', $id);

               /*if($status == 0) {
                   $query->where('gcr.in_favour_govt',$is_fav);
                }
                if($status != 0) {
                    if ($status == 3) {
                       $query->where('gcr.status',$status);
                    } else {
                       $query->whereIn('gcr.status',[1,2]);
                    }
                }*/

        $data['ministry_wise'] = $query->groupBy('office.id')
                                        ->groupBy('gcb.department_id')
                                        ->orderBy('office.id', 'asc')
                                        ->paginate(10);

        // $data['ministry_wise'] = $query;
        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get(); 
        $data['ministry'] = DB::table('office')->select('id', 'office_name_bn')->where('id',$id)->first();
        $data['page_title'] =   $data['ministry']->office_name_bn.' এর মামলার তালিকা';

        return view('gov_case.case_register.ministry_wise_list')->with($data);
    }

    //============Department Wise Case list============/
    public function department_wise_list($id)
    {
        // dd($id);
        /*$data['ministry_wise'] = DB::table('office')
                ->select('office.id', 'office.office_name_bn', 'office.office_name_en',
                    \DB::raw('SUM(CASE WHEN gcr.status != "3" THEN 1 ELSE 0 END) AS running_case'),
                    \DB::raw('SUM(CASE WHEN gcr.status = "3" THEN 1 ELSE 0 END) AS completed_case'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" THEN 1 ELSE 0 END) AS against_gov'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
                )
                ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.department_id')
                ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
                ->where('office.id', $id)->groupBy('office.id')->groupBy('gcb.department_id')
                ->orderBy('office.id', 'asc')->paginate(10);*/

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;

        $query =  GovCaseRegister::select('gov_case_registers.*')->orderby('gov_case_registers.id','DESC');
        $query->leftJoin('gov_case_bibadis as gcb', 'gov_case_registers.id', '=', 'gcb.gov_case_id');
        $query->where('gcb.department_id', $id);

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
            $query->whereBetween('gov_case_registers.case_date', [$dateFrom, $dateTo]);
        }

        if(!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no','=',$_GET['case_no']);
        }
        if(!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id','=',$_GET['division']);
        }
        if(!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id','=',$_GET['district']);
        }
        if(!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id','=',$_GET['upazila']);
        }

        if($roleID == 5 || $roleID == 7){
            $query->where('gov_case_registers.district_id',$officeInfo->district_id)->orderby('id','DESC');
        }elseif($roleID == 9 || $roleID == 21){
            $query->where('gov_case_registers.upazila_id',$officeInfo->upazila_id)->orderby('id','DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $ministry = DB::table('office')->select('office.id', 'op.office_name_bn', 'office.office_name_bn as ministry_name')
                        ->leftJoin('office as op', 'office.id', '=', 'op.parent')
                        ->where('op.id',$id)->first();

        $data['ministry'] = $ministry;
        $data['roleID'] = $roleID;
        $data['page_title'] =   $data['ministry']->office_name_bn.' এর মামলার তালিকা';
        return view('gov_case.case_register.department_wise_list')->with($data);
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
        if($roleID != 33){
            $data['depatments'] = Office::where('parent', $officeID)->get();
        }else{
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['appealCase'] =  DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id',2)->where('status',3)->get();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['page_title'] = 'নতুন মামলা রেজিষ্টার এন্ট্রি ফরম'; //exit;
        // dd($data);
        return view('gov_case.case_register.create')->with($data);
    }

    public function get_details(Request $request)
    {
        //$query =  DB::table('gov_case_registers')->where('id',$request->case_id)->first();
       $query =  GovCaseRegister::where('id',$request->case_id)->first();
        return $query;
        
    }


    public function store(Request $request)
    {
        return $request;
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
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['appealCase'] =  DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id',2)->where('status',3)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['courts'] = Court::select('id', 'court_name')->get();
        if($roleID != 33){
            $data['depatments'] = Office::where('parent', $officeID)->get();
        }else{
            $data['depatments'] = Office::where('level', 12)->get();
        }
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

    public function register($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        // return $data;
        if ($data['case']->case_division_id == 2) {
            $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলা সম্পর্কিত রেজিস্টার';
            return view('gov_case.case_register.highCourtRegister')->with($data);
        }else{
            $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট আপিল বিভাগের মামলা সম্পর্কিত রেজিস্টার';
            return view('gov_case.case_register.appealRegister')->with($data);
        }
        // return $data;
    }

    public function show($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        // return $data;
        if ($data['case']->case_division_id == 2) {
            $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলা সম্পর্কিত রেজিস্টার';
        }else{
            $data['page_title'] =   'সরকারি স্বার্থসংশ্লিষ্ট আপিল বিভাগের মামলা সম্পর্কিত রেজিস্টার';
        }
            return view('gov_case.case_register.showDetails')->with($data);
        // return $data;
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
