<?php

namespace App\Http\Controllers;

// use Auth;
use App\Models\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\calendar\CaseHearingCollection;
use App\Http\Resources\calendar\RM_CaseHearingCollection;
use App\Models\gov_case\GovCaseNotice;
use App\Models\gov_case\GovCaseRegister;
use App\Models\RoleHasPermission;
use App\Repositories\gov_case\GovCaseRegisterRepository;


// use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use App\Http\Controllers\CommonController;

class DashboardController extends Controller
{

   // use AuthenticatesUsers;
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
   {
     session()->forget('currentUrlPath');

      $officeInfo = user_office_info();
      $user = Auth::user();
      // dd($user);
      $roleID = Auth::user()->role_id;
      $officeID = Auth::user()->office_id;
      
      $data = [];
      $data['rm_case_status'] = [];


      if($roleID == 1){
         // Superadmi dashboard

         // Counter
         $data['total_case'] = DB::table('case_register')->count();
         $data['total_at_case'] = '';
         $data['running_case'] = DB::table('case_register')->where('status', 1)->count();
         $data['appeal_case'] = DB::table('case_register')->where('status', 2)->count();
         $data['completed_case'] = DB::table('case_register')->where('status', 3)->count();

         $data['total_office'] = DB::table('office')->whereNotIn('id', [1,2,7])->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();

         $data['cases'] = DB::table('case_register')->select('case_register.*')->get();

            // Get case status by group
         $data['case_status'] = DB::table('case_register')
         ->select('case_register.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
         ->leftJoin('case_status', 'case_register.cs_id', '=', 'case_status.id')
         ->groupBy('case_register.cs_id')
         ->where('case_register.action_user_group_id', $roleID)
         ->get();

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         // Drildown Statistics
         $division_list = DB::table('division')
         ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
         ->get();

         $divisiondata=array();
         $districtdata=array();
         // $dis_data=array();
         $upazilatdata=array();

         // Division List
         foreach ($division_list as $division) {
            // $data_arr[$item->id] = $this->get_drildown_case_count($item->id);
            // Division Data
            $data['divisiondata'][] = array('name' => $division->division_name_bn, 'y' => $this->get_drildown_case_count($division->id), 'drilldown' => $division->id);

            // District List
            $district_list = DB::table('district')->select('district.id', 'district.district_name_bn')->where('division_id', $division->id)->get();
            foreach ($district_list as $district) {
               // $dis_count = $this->Employee_model->get_count_employees('', '', '', $district->id);
               // $number2 = (int) $dis_count['count']; //exit;

               $dis_data[$division->id][] = array('name' => $district->district_name_bn, 'y' => $this->get_drildown_case_count('', $district->id), 'drilldown' => $district->id);

               // Upazila Data
               // $upazila_list = $this->Common_model->get_data_where('upazilas', 'district_id', $district->id);
               $upazila_list = DB::table('upazila')->select('upazila.id', 'upazila.upazila_name_bn')->where('district_id', $district->id)->get();
               foreach ($upazila_list as $upazila) {
                  // $upa_count = $this->Employee_model->get_count_employees('', '', '', '', $upazila->id);
                  // $number3 = (int) $upa_count['count']; //exit;

                  $upa_data[$district->id][] = array($upazila->upazila_name_bn, $this->get_drildown_case_count('', '', $upazila->id));
               }

               $upadata = $upa_data[$district->id];
               $upazilatdata[] = array('name' => $district->district_name_bn, 'id' => $district->id, 'data' => $upadata);
            }

            $disdata = $dis_data[$division->id];
            $districtdata[] = array('name' => $division->division_name_bn, 'id' => $division->id, 'data' => $disdata);

            $data['dis_upa_data'] = array_merge($upazilatdata, $districtdata); //$districtdata;  $upazilatdata;

         }
         // dd($result);
         // $data['divisiondata'] = $divisiondata;
         // dd($data['division_arr']);



         // View
         $data['page_title'] = 'সুপার অ্যাডমিন ড্যাশবোর্ড';
         return view('dashboard.superadmin')->with($data);

      }elseif($roleID == 27){
         // Superadmin dashboard

         // Counter
         $data['total_case'] = GovCaseRegister::count();
         $data['running_case'] = GovCaseRegister::where('status', 1)->count();
         $data['appeal_case'] = GovCaseRegister::where('status', 2)->count();
         $data['completed_case'] = GovCaseRegister::where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereIn('status', [1])->count();
         // $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', '!=' , 3)->count();
         $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', 1)->count();
         // $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', '!=' , 3)->count();
         $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', 1)->count();
         $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->where('status', 3)->count();
         $data['against_gov'] = GovCaseRegister::where('in_favour_govt', 0)->where('status', 3)->count();
       

         $data['total_office'] = DB::table('office')->whereNotIn('id', [1,2,7])->count();
         $data['total_ministry'] = DB::table('office')->where('level', 9)->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();
        
         $data['cases'] = DB::table('case_register')->select('case_register.*')->get();

        /* // count ministry wise case status
         $ministry_wise = DB::table('office')
                           ->select('office.id', 'office.office_name_bn', 'office.office_name_en',
                              \DB::raw('SUM(CASE WHEN gcr.status != "3" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS running_case'),
                              \DB::raw('SUM(CASE WHEN gcr.status = "3" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS completed_case'),
                              \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS against_gov'),
                              \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
                           )
                           ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.respondent_id')
                           ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
                           ->where('office.level', 9)
                           ->groupBy('office.id')
                           ->groupBy('gcb.respondent_id')
                           ->orderBy('office.id', 'asc')
                           ->paginate(10);

         $data['ministry_wise'] = $ministry_wise;
         // Drildown Statistics
         $ministry_list = DB::table('office')
         ->select('office.id', 'office.office_name_bn', 'office.office_name_en')
         ->where('office.level', 9)
         ->get();

         $ministrydata=array();
         $departmentdata=array();


         // Ministry List
         foreach ($ministry_list as $ministry) {
            // Ministry Data
            $data['ministrydata'][] = array('name' => $ministry->office_name_bn, 'y' => $this->get_drildown_gov_case_count($ministry->id), 'drilldown' => $ministry->id);

            // Department List
            $department_list = DB::table('office')->select('office.id', 'office.office_name_bn')->where('parent', $ministry->id)->get();
            foreach ($department_list as $department) {

               $dept_data[$ministry->id][] = array('name' => $department->office_name_bn, 'y' => $this->get_drildown_gov_case_count('', $department->id), 'drilldown' => $department->id);
            }

            $deptdata = $dept_data[$ministry->id];
            $departmentdata[] = array('name' => $ministry->office_name_bn, 'id' => $ministry->id, 'data' => $deptdata);

            $data['department_data'] = array_merge( $departmentdata);

         }*/

 

         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         // return $data;

         // View
         $data['page_title'] = 'মন্ত্রিপরিষদ সচিবের ড্যাশবোর্ড';
         return view('dashboard.cabinet.cabinet_admin')->with($data);

      }elseif($roleID == 28){
         // Superadmin dashboard

         // Counter
         $data['total_case'] = GovCaseRegister::count();
         $data['running_case'] = GovCaseRegister::where('status', 1)->count();
         $data['appeal_case'] = GovCaseRegister::where('status', 2)->count();
         $data['completed_case'] = GovCaseRegister::where('status', 3)->count();
         $data['running_case_appeal'] = GovCaseRegister::where('status', 1)->count();
         // $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', '!=' , 3)->count();
         $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', 1)->count();
         // $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', '!=' , 3)->count();
         $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status',1)->count();
         $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->where('status', 3)->count();
         $data['against_gov'] = GovCaseRegister::where('in_favour_govt', 0)->where('status', 3)->count();

         $data['total_office'] = DB::table('office')->whereNotIn('id', [1,2,7])->count();
         $data['total_ministry'] = DB::table('office')->where('level', 9)->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();


         // count ministry wise case status
         /*$ministry_wise = DB::table('office')
                              ->select('office.id', 'office.office_name_bn', 'office.office_name_en',
                                 \DB::raw('SUM(CASE WHEN gcr.status != "3" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS running_case'),
                                 \DB::raw('SUM(CASE WHEN gcr.status = "3" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS completed_case'),
                                 \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS against_gov'),
                                 \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" AND gcb.is_main_bibadi = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
                              )
                              ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.respondent_id')
                              ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
                              ->where('office.level', 9)
                              ->groupBy('office.id')
                              ->groupBy('gcb.respondent_id')
                              ->orderBy('office.id', 'asc')
                              ->paginate(10);

         $data['ministry_wise'] = $ministry_wise;


         // Drildown Statistics
         $ministry_list = DB::table('office')
         ->select('office.id', 'office.office_name_bn', 'office.office_name_en')
         ->where('office.level', 9)
         ->get();

         $ministrydata=array();
         $departmentdata=array();


         // Ministry List
         foreach ($ministry_list as $ministry) {
            // Ministry Data
            $data['ministrydata'][] = array('name' => $ministry->office_name_bn, 'y' => $this->get_drildown_gov_case_count($ministry->id), 'drilldown' => $ministry->id);

            // Department List
            $department_list = DB::table('office')->select('office.id', 'office.office_name_bn')->where('parent', $ministry->id)->get();
            foreach ($department_list as $department) {

               $dept_data[$ministry->id][] = array('name' => $department->office_name_bn, 'y' => $this->get_drildown_gov_case_count('', $department->id), 'drilldown' => $department->id);
            }

            $deptdata = $dept_data[$ministry->id];
            $departmentdata[] = array('name' => $ministry->office_name_bn, 'id' => $ministry->id, 'data' => $deptdata);

            $data['department_data'] = array_merge( $departmentdata);

         }*/


         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         // return $data;
         // View
         $data['page_title'] = 'মন্ত্রিপরিষদ সচিবের সহকারীর ড্যাশবোর্ড';
         return view('dashboard.cabinet.cabinet_admin')->with($data);

      }elseif($roleID == 14){
         // Solicitor
         // Get case status by group
         // Counter
         $data['total_case'] = GovCaseRegister::count();
         $data['running_case'] = GovCaseRegister::where('status', 1)->count();
         $data['appeal_case'] = GovCaseRegister::where('status', 2)->count();
         $data['completed_case'] = GovCaseRegister::where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereIn('status', [1,2])->count();
         $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', '!=' , 3)->count();
         $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', '!=' , 3)->count();
         $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->where('status', 3)->count();
         $data['against_gov'] = GovCaseRegister::where('in_favour_govt', 0)->where('status', 3)->count();

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         $data['case_status'] = DB::table('case_register')
         ->select('case_register.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
         ->leftJoin('case_status', 'case_register.cs_id', '=', 'case_status.id')
         ->groupBy('case_register.cs_id')
         ->where('case_register.action_user_group_id', $roleID)
         ->get();
         // dd($data['case_status']);

         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         
         // dd($data['gov_case_status']);
         $data['page_title'] = 'আইনজীবীর ড্যাশবোর্ড';
         return view('dashboard.cabinet.solicitor')->with($data);
      }elseif($roleID == 29){
         // Ministry dashboard

         // Counter
         $data['total_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->count();

         $data['running_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('status', 1)->count();

         $data['appeal_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('status', 2)->count();

         $data['completed_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->whereIn('status', [1,2])->count();

         $data['high_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 2)->where('status', '!=' , 3)->count();

         $data['appeal_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 1)->where('status', '!=' , 3)->count();

         $data['not_against_gov'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('in_favour_govt', 1)->count();

         $data['against_gov'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('in_favour_govt', 0)->count();


         $data['total_office'] = DB::table('office')->whereIn('level', [10,11,12])->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();
        

         $data['notice'] = GovCaseNotice::where('notice_for',$roleID)->where('expiry_date','>=', date(now()))->get();

         // count ministry wise case status
         $ministry = DB::table('office')
                ->select('office.id', 'office.office_name_bn', 'office.office_name_en',
                    \DB::raw('SUM(CASE WHEN gcr.status != "3" THEN 1 ELSE 0 END) AS running_case'),
                    \DB::raw('SUM(CASE WHEN gcr.status = "3" THEN 1 ELSE 0 END) AS completed_case'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" THEN 1 ELSE 0 END) AS against_gov'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
                )
                ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.department_id')
                ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
                ->where('office.parent', $officeID);

        $data['ministry_wise'] = $ministry->groupBy('office.id')
                                        ->groupBy('gcb.department_id')
                                        ->orderBy('office.id', 'asc')
                                        ->paginate(10);

         // Drildown Statistics
         $division_list = DB::table('division')
         ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
         ->get();

        
          
          // Drildown Statistics
         $department_list = DB::table('office')
         ->select('office.id', 'office.office_name_bn', 'office.office_name_en')
         ->where('office.parent', $officeID)
         ->get();

         $ministrydata=array();
         $departmentdata=array();


         // Ministry List

         foreach ($department_list as $department) {
            // Department List
            $data['departmentdata'][] = array('name' => $department->office_name_bn, 'y' => $this->get_drildown_gov_case_count('', $department->id), 'drilldown' => $department->id);;

            
            $data['department_data'] = array_merge( $departmentdata);

         }
         

        
        

        

         // View
         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         $data['page_title'] = 'মিনিস্ট্রি এডমিনের ড্যাশবোর্ড';
         // return $data;
         return view('dashboard.cabinet.min_admin')->with($data);

      }elseif($roleID == 30){
         // Superadmin dashboard

         // Counter
         $data['total_case'] = DB::table('case_register')->count();
         $data['total_at_case'] = AtCaseRegister::count();
         $data['total_rm_case'] = RM_CaseRgister::count();
         $data['running_case'] = DB::table('case_register')->where('status', 1)->count();
         $data['appeal_case'] = DB::table('case_register')->where('status', 2)->count();
         $data['completed_case'] = DB::table('case_register')->where('status', 3)->count();

         $data['total_office'] = DB::table('office')->whereIn('id', [10,12,11])->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();
        

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         // Drildown Statistics
         $division_list = DB::table('division')
         ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
         ->get();

         $divisiondata=array();
         $districtdata=array();
         // $dis_data=array();
         $upazilatdata=array();

         // Division List
         foreach ($division_list as $division) {
            // $data_arr[$item->id] = $this->get_drildown_case_count($item->id);
            // Division Data
            $data['divisiondata'][] = array('name' => $division->division_name_bn, 'y' => $this->get_drildown_case_count($division->id), 'drilldown' => $division->id);

            // District List
            $district_list = DB::table('district')->select('district.id', 'district.district_name_bn')->where('division_id', $division->id)->get();
            foreach ($district_list as $district) {
               // $dis_count = $this->Employee_model->get_count_employees('', '', '', $district->id);
               // $number2 = (int) $dis_count['count']; //exit;

               $dis_data[$division->id][] = array('name' => $district->district_name_bn, 'y' => $this->get_drildown_case_count('', $district->id), 'drilldown' => $district->id);

               // Upazila Data
               // $upazila_list = $this->Common_model->get_data_where('upazilas', 'district_id', $district->id);
               $upazila_list = DB::table('upazila')->select('upazila.id', 'upazila.upazila_name_bn')->where('district_id', $district->id)->get();
               foreach ($upazila_list as $upazila) {
                  // $upa_count = $this->Employee_model->get_count_employees('', '', '', '', $upazila->id);
                  // $number3 = (int) $upa_count['count']; //exit;

                  $upa_data[$district->id][] = array($upazila->upazila_name_bn, $this->get_drildown_case_count('', '', $upazila->id));
               }

               $upadata = $upa_data[$district->id];
               $upazilatdata[] = array('name' => $district->district_name_bn, 'id' => $district->id, 'data' => $upadata);
            }

            $disdata = $dis_data[$division->id];
            $districtdata[] = array('name' => $division->division_name_bn, 'id' => $division->id, 'data' => $disdata);

            $data['dis_upa_data'] = array_merge($upazilatdata, $districtdata); //$districtdata;  $upazilatdata;

         }
         // dd($result);
         // $data['divisiondata'] = $divisiondata;
         // dd($data['division_arr']);

         
         

         

         // View
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         $data['page_title'] = 'মন্ত্রণালয়ের সচিবের সহকারীর ড্যাশবোর্ড';
         return view('dashboard.cabinet.admin')->with($data);

      }elseif($roleID == 31){
         // Asst. Ministry dashboard

         // Counter
         $data['total_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->count();

         $data['running_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('status', 1)->count();

         $data['appeal_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('status', 2)->count();

         $data['completed_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->whereIn('status', [1,2])->count();

         $data['high_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 2)->where('status', '!=' , 3)->count();

         $data['appeal_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 1)->where('status', '!=' , 3)->count();

         $data['not_against_gov'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('in_favour_govt', 1)->count();

         $data['against_gov'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('respondent_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('in_favour_govt', 0)->count();


         $data['total_office'] = DB::table('office')->whereIn('id', [10,12,11])->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();
        

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         // count ministry wise case status
         $ministry = DB::table('office')
                ->select('office.id', 'office.office_name_bn', 'office.office_name_en',
                    \DB::raw('SUM(CASE WHEN gcr.status != "3" THEN 1 ELSE 0 END) AS running_case'),
                    \DB::raw('SUM(CASE WHEN gcr.status = "3" THEN 1 ELSE 0 END) AS completed_case'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" THEN 1 ELSE 0 END) AS against_gov'),
                    \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
                )
                ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.department_id')
                ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
                ->where('office.parent', $officeID);

         $data['ministry_wise'] = $ministry->groupBy('office.id')
                                        ->groupBy('gcb.department_id')
                                        ->orderBy('office.id', 'asc')
                                        ->paginate(10);

         // Drildown Statistics
         $division_list = DB::table('division')
         ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
         ->get();

         $divisiondata=array();
         $districtdata=array();
         // $dis_data=array();
         $upazilatdata=array(); 
          // Drildown Statistics
         $department_list = DB::table('office')
         ->select('office.id', 'office.office_name_bn', 'office.office_name_en')
         ->where('office.parent', $officeID)
         ->get();

         $ministrydata=array();
         $departmentdata=array();


         // Ministry List

         foreach ($department_list as $department) {
            // Department List
            $data['departmentdata'][] = array('name' => $department->office_name_bn, 'y' => $this->get_drildown_gov_case_count('', $department->id), 'drilldown' => $department->id);;

            
            $data['department_data'] = array_merge( $departmentdata);

         }
         

         // dd($result);
         // $data['divisiondata'] = $divisiondata;
         // dd($data['division_arr']);

         
         

         

         // View
         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         $data['page_title'] = 'মিনিস্ট্রি এডমিনের সহকারীর ড্যাশবোর্ড';
         return view('dashboard.cabinet.min_admin')->with($data);

      }elseif($roleID == 32){
         // Department/Odhidhoptor dashboard
          // Counter
         $data['total_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->count();
         $data['running_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('status', 1)->count();
         $data['appeal_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('status', 2)->count();
         $data['completed_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->whereIn('status', [1,2])->count();

         $data['high_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 2)->where('status', '!=' , 3)->count();

         $data['appeal_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 1)->where('status', '!=' , 3)->count();

         $data['not_against_gov'] = GovCaseRegister::whereHas('bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('in_favour_govt', 1)->count();

         $data['against_gov'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('in_favour_govt', 0)->count();

         $data['total_office'] = DB::table('office')->whereIn('id', [11,12])->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();
        

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         // Drildown Statistics
         $division_list = DB::table('division')
         ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
         ->get();

         $divisiondata=array();
         $districtdata=array();
         // $dis_data=array();
         $upazilatdata=array();

         // Division List
         foreach ($division_list as $division) {
            // $data_arr[$item->id] = $this->get_drildown_case_count($item->id);
            // Division Data
            $data['divisiondata'][] = array('name' => $division->division_name_bn, 'y' => $this->get_drildown_case_count($division->id), 'drilldown' => $division->id);

            // District List
            $district_list = DB::table('district')->select('district.id', 'district.district_name_bn')->where('division_id', $division->id)->get();
            foreach ($district_list as $district) {
               // $dis_count = $this->Employee_model->get_count_employees('', '', '', $district->id);
               // $number2 = (int) $dis_count['count']; //exit;

               $dis_data[$division->id][] = array('name' => $district->district_name_bn, 'y' => $this->get_drildown_case_count('', $district->id), 'drilldown' => $district->id);

               // Upazila Data
               // $upazila_list = $this->Common_model->get_data_where('upazilas', 'district_id', $district->id);
               $upazila_list = DB::table('upazila')->select('upazila.id', 'upazila.upazila_name_bn')->where('district_id', $district->id)->get();
               foreach ($upazila_list as $upazila) {
                  // $upa_count = $this->Employee_model->get_count_employees('', '', '', '', $upazila->id);
                  // $number3 = (int) $upa_count['count']; //exit;

                  $upa_data[$district->id][] = array($upazila->upazila_name_bn, $this->get_drildown_case_count('', '', $upazila->id));
               }

               $upadata = $upa_data[$district->id];
               $upazilatdata[] = array('name' => $district->district_name_bn, 'id' => $district->id, 'data' => $upadata);
            }

            $disdata = $dis_data[$division->id];
            $districtdata[] = array('name' => $division->division_name_bn, 'id' => $division->id, 'data' => $disdata);

            $data['dis_upa_data'] = array_merge($upazilatdata, $districtdata); //$districtdata;  $upazilatdata;

         }
         // dd($result);
         // $data['divisiondata'] = $divisiondata;
         // dd($data['division_arr']);

         
         

         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         // View
         $data['page_title'] = 'অধিদপ্তর এডমিনের ড্যাশবোর্ড';
         return view('dashboard.cabinet.dept_admin')->with($data);

      }elseif($roleID == 33){
         // Asst. Department/Odhidhoptor dashboard
         // Counter
         $data['total_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->count();
         $data['running_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('status', 1)->count();
         $data['appeal_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('status', 2)->count();
         $data['completed_case'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->whereIn('status', [1,2])->count();

         $data['high_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 2)->where('status', '!=' , 3)->count();

         $data['appeal_court_case'] = GovCaseRegister::whereHas( 'bibadis', 
               function ($query)use($officeID) {
                  $query->where('department_id', $officeID)->where('is_main_bibadi',1);
               }
            )->where('case_division_id', 1)->where('status', '!=' , 3)->count();
         
         $data['not_against_gov'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('in_favour_govt', 1)->count();
         $data['against_gov'] = GovCaseRegister::whereHas( 'bibadis', function ($query)use($officeID) {$query->where('department_id', $officeID)->where('is_main_bibadi',1);})->where('in_favour_govt', 0)->count();
         $data['total_office'] = DB::table('office')->whereIn('id', [11,12])->count();
         $data['total_user'] = DB::table('users')->count();
         $data['total_court'] = DB::table('court')->whereNotIn('id', [1,2])->count();
         $data['total_mouja'] = DB::table('mouja')->count();
         $data['total_ct'] = DB::table('case_type')->count();
        

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         // Drildown Statistics
         $division_list = DB::table('division')
         ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
         ->get();

         $divisiondata=array();
         $districtdata=array();
         // $dis_data=array();
         $upazilatdata=array();

         // Division List
         foreach ($division_list as $division) {
            // $data_arr[$item->id] = $this->get_drildown_case_count($item->id);
            // Division Data
            $data['divisiondata'][] = array('name' => $division->division_name_bn, 'y' => $this->get_drildown_case_count($division->id), 'drilldown' => $division->id);

            // District List
            $district_list = DB::table('district')->select('district.id', 'district.district_name_bn')->where('division_id', $division->id)->get();
            foreach ($district_list as $district) {
               // $dis_count = $this->Employee_model->get_count_employees('', '', '', $district->id);
               // $number2 = (int) $dis_count['count']; //exit;

               $dis_data[$division->id][] = array('name' => $district->district_name_bn, 'y' => $this->get_drildown_case_count('', $district->id), 'drilldown' => $district->id);

               // Upazila Data
               // $upazila_list = $this->Common_model->get_data_where('upazilas', 'district_id', $district->id);
               $upazila_list = DB::table('upazila')->select('upazila.id', 'upazila.upazila_name_bn')->where('district_id', $district->id)->get();
               foreach ($upazila_list as $upazila) {
                  // $upa_count = $this->Employee_model->get_count_employees('', '', '', '', $upazila->id);
                  // $number3 = (int) $upa_count['count']; //exit;

                  $upa_data[$district->id][] = array($upazila->upazila_name_bn, $this->get_drildown_case_count('', '', $upazila->id));
               }

               $upadata = $upa_data[$district->id];
               $upazilatdata[] = array('name' => $district->district_name_bn, 'id' => $district->id, 'data' => $upadata);
            }

            $disdata = $dis_data[$division->id];
            $districtdata[] = array('name' => $division->division_name_bn, 'id' => $division->id, 'data' => $disdata);

            $data['dis_upa_data'] = array_merge($upazilatdata, $districtdata); //$districtdata;  $upazilatdata;

         }
         // dd($result);
         // $data['divisiondata'] = $divisiondata;
         // dd($data['division_arr']);


         // View
         $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
         $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
         $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
         $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
         $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
         
         $data['page_title'] = 'অধিদপ্তর এডমিনের সহকারীর ড্যাশবোর্ড';
         return view('dashboard.cabinet.dept_admin')->with($data);

      }elseif($roleID == 34 ){
         // Attorney General
         // Get case status by group
         // Counter
         $data['total_case'] = DB::table('case_register')->count();
         $data['running_case'] = DB::table('case_register')->where('status', 1)->count();
         $data['appeal_case'] = DB::table('case_register')->where('status', 2)->count();
         $data['completed_case'] = DB::table('case_register')->where('status', 3)->count();

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         $data['case_status'] = DB::table('case_register')
         ->select('case_register.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
         ->leftJoin('case_status', 'case_register.cs_id', '=', 'case_status.id')
         ->groupBy('case_register.cs_id')
         ->where('case_register.action_user_group_id', $roleID)
         ->get();
         // dd($data['case_status']);

         $data['page_title'] = 'অতিরিক্ত-অ্যাটর্নি-জেনারেল';
         return view('dashboard.cabinet.officer')->with($data);
      }elseif($roleID == 35 ){
         // Attorney General
         // Get case status by group
         // Counter
         $data['total_case'] = DB::table('case_register')->count();
         $data['running_case'] = DB::table('case_register')->where('status', 1)->count();
         $data['appeal_case'] = DB::table('case_register')->where('status', 2)->count();
         $data['completed_case'] = DB::table('case_register')->where('status', 3)->count();

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         $data['case_status'] = DB::table('case_register')
         ->select('case_register.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
         ->leftJoin('case_status', 'case_register.cs_id', '=', 'case_status.id')
         ->groupBy('case_register.cs_id')
         ->where('case_register.action_user_group_id', $roleID)
         ->get();
         // dd($data['case_status']);

         $data['page_title'] = '‎ডেপুটি-অ্যাটর্নি-জেনারেল';
         return view('dashboard.cabinet.officer')->with($data);
      }elseif($roleID == 36){
         // Solicitor
         // Get case status by group
         // Counter

        
         $data['total_case'] = GovCaseRegister::count();
         $data['running_case'] = GovCaseRegister::where('status', 1)->count();
         $data['appeal_case'] = GovCaseRegister::where('status', 2)->count();
         $data['completed_case'] = GovCaseRegister::where('status', 3)->count();

         $data['running_case_appeal'] = GovCaseRegister::whereIn('status', [1,2])->count();
         $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', '!=' , 3)->count();
         $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', '!=' , 3)->count();
         $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->where('status', 3)->count();
         $data['against_gov'] = GovCaseRegister::where('in_favour_govt', 0)->where('status', 3)->count();

         $data['cases'] = DB::table('case_register')
         ->select('case_register.*')
         ->get();

         $data['case_status'] = DB::table('case_register')
         ->select('case_register.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
         ->leftJoin('case_status', 'case_register.cs_id', '=', 'case_status.id')
         ->groupBy('case_register.cs_id')
         ->where('case_register.action_user_group_id', $roleID)
         ->get();
         // dd($data['case_status']);

        $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleIds([$roleID, 14]);

        // dd($data['gov_case_status']);
         $data['page_title'] = 'অ্যাটর্নি জেনারেল অফিস অপারেটরের ড্যাশবোর্ড';
         return view('dashboard.cabinet.officer')->with($data);
      }

   }

   


     public function hearing_date_today()
     {
       $data['hearing'] = DB::table('case_hearing')
       ->join('case_register', 'case_hearing.case_id', '=', 'case_register.id')
       ->join('court', 'case_register.court_id', '=', 'court.id')
       ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
       ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
       ->select('case_hearing.*', 'case_register.id', 'case_register.court_id', 'case_register.case_number', 'case_register.status', 'court.court_name')
       ->where('case_hearing.hearing_date', '=', date('Y-m-d'))
       ->get();

        // dd($data['hearing']);

       $data['page_title'] = 'আজকের দিনে শুনানী/মামলার তারিখ';
       return view('dashboard.hearing_date')->with($data);
    }


    public function hearing_date_tomorrow()
    {
       $d = date('Y-m-d',strtotime('+1 day')) ;
       $data['hearing'] = DB::table('case_hearing')
       ->join('case_register', 'case_hearing.case_id', '=', 'case_register.id')
       ->join('court', 'case_register.court_id', '=', 'court.id')
       ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
       ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
       ->select('case_hearing.*', 'case_register.id', 'case_register.court_id', 'case_register.case_number', 'case_register.status', 'court.court_name')
       ->where('case_hearing.hearing_date', '=', $d)
       ->get();

        // dd($data['hearing']);

       $data['page_title'] = 'আগামী দিনে শুনানী/মামলার তারিখ';
       return view('dashboard.hearing_date')->with($data);
    }


    public function hearing_date_nextWeek()
    {

       $d = date('Y-m-d',strtotime('+7 day')) ;
       $data['hearing'] = DB::table('case_hearing')
       ->join('case_register', 'case_hearing.case_id', '=', 'case_register.id')
       ->join('court', 'case_register.court_id', '=', 'court.id')
       ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
       ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
       ->select('case_hearing.*', 'case_register.id', 'case_register.court_id', 'case_register.case_number', 'case_register.status', 'court.court_name')
       ->where('case_hearing.hearing_date', '>=', date('Y-m-d'))
       ->where('case_hearing.hearing_date', '<=', $d)
       ->get();

        // dd($data['hearing']);

       $data['page_title'] = 'আগামী সপ্তাহের শুনানী/মামলার তারিখ';
       return view('dashboard.hearing_date')->with($data);
    }


    public function hearing_date_nextMonth()
    {
       $d = date('Y-m-d',strtotime('+1 month')) ;
       /* $m = date('m',strtotime($d));
       dd($d);*/
       $data['hearing'] = DB::table('case_hearing')
       ->join('case_register', 'case_hearing.case_id', '=', 'case_register.id')
       ->join('court', 'case_register.court_id', '=', 'court.id')
       ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
       ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
       ->select('case_hearing.*', 'case_register.id', 'case_register.court_id', 'case_register.case_number', 'case_register.status', 'court.court_name')
       ->where('case_hearing.hearing_date', '>=', date('Y-m-d'))
       ->where('case_hearing.hearing_date', '<=', $d)
       ->get();

        // dd($data['hearing']);

       $data['page_title'] = 'আগামী মাসের শুনানী/মামলার তারিখ';
       return view('dashboard.hearing_date')->with($data);
    }

    public function hearing_case_details($id)
    {

     $data['info'] = DB::table('case_register')
     ->join('court', 'case_register.court_id', '=', 'court.id')
     ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
     ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
     // ->join('case_type', 'case_register.ct_id', '=', 'case_type.id')
     ->join('case_status', 'case_register.cs_id', '=', 'case_status.id')
     // ->join('case_badi', 'case_register.id', '=', 'case_badi.case_id')
     // ->join('case_bibadi', 'case_register.id', '=', 'case_bibadi.case_id')
     ->select('case_register.*', 'court.court_name', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn',  'case_status.status_name')
     ->where('case_register.id', '=', $id)
     ->first();
		// dd($data['info']);
     	// dd($data['info']);

     $data['badis'] = DB::table('case_badi')
     ->join('case_register', 'case_badi.case_id', '=', 'case_register.id')
     ->select('case_badi.*')
     ->where('case_badi.case_id', '=', $id)
     ->get();

     $data['bibadis'] = DB::table('case_bibadi')
     ->join('case_register', 'case_bibadi.case_id', '=', 'case_register.id')
     ->select('case_bibadi.*')
     ->where('case_bibadi.case_id', '=', $id)
     ->get();

     $data['surveys'] = DB::table('case_survey')
     ->join('case_register', 'case_survey.case_id', '=', 'case_register.id')
     ->join('survey_type', 'case_survey.st_id', '=', 'survey_type.id')
     ->join('land_type', 'case_survey.lt_id', '=', 'land_type.id')
     ->select('case_survey.*','survey_type.st_name','land_type.lt_name')
     ->where('case_survey.case_id', '=', $id)
     ->get();

        // Get SF Details
     $data['sf'] = DB::table('case_sf')
     ->select('case_sf.*')
     ->where('case_sf.case_id', '=', $id)
     ->first();
        // dd($data['sf']);

        // Get SF Details
     $data['logs'] = DB::table('case_log')
     ->select('case_log.comment', 'case_log.created_at', 'case_status.status_name', 'roles.name', 'users.name')
     ->join('case_status', 'case_status.id', '=', 'case_log.status_id')
     ->leftjoin('roles', 'case_log.send_user_group_id', '=', 'roles.id')
     ->join('users', 'case_log.user_id', '=', 'users.id')
     ->where('case_log.case_id', '=', $id)
     ->orderBy('case_log.id', 'desc')
     ->get();
        // dd($data['sf']);

        // Get SF Details
     $data['hearings'] = DB::table('case_hearing')
     ->select('case_hearing.*')
     ->where('case_hearing.case_id', '=', $id)
     ->orderBy('case_hearing.id', 'desc')
     ->get();

        // Dropdown
     $data['roles'] = DB::table('roles')
     ->select('id', 'name')
     ->where('in_action', '=', 1)
     ->orderBy('sort_order', 'asc')
     ->get();

      // dd($data['bibadis']);

     $data['page_title'] = 'শুনানী মামলার বিস্তারিত তথ্য';
     return view('dashboard.hearing_case_details')->with($data);
  }

  public function get_drildown_case_count($division=NULL, $district=NULL, $upazila=NULL, $status=NULL) {
     $query = DB::table('case_register');

     if($division != NULL){
       $query->where('division_id', $division);
    }
    if($district != NULL){
       $query->where('district_id', $district);
    }
    if($upazila != NULL){
       $query->where('upazila_id', $upazila);
    }

    return $query->count();
 }

  public function get_drildown_gov_case_count($ministry=NULL, $department=NULL,  $status=NULL) {
     $query = DB::table('gov_case_bibadis');

     if($ministry != NULL){
       $query->where('respondent_id', $ministry);
       $query->where('is_main_bibadi',1);
       // $query->groupBy('gov_case_id');
    }
    if($department != NULL){
       $query->where('department_id', $department);
       $query->where('is_main_bibadi', 1);
       // $query->groupBy('gov_case_id');
    }
    /*if($upazila != NULL){
       $query->where('upazila_id', $upazila);
    }*/

    return $query->count();
 }

    public function get_mouja_by_ulo_office_id($officeID){
        return DB::table('mouja_ulo')->where('ulo_office_id', $officeID)->pluck('mouja_id');
        // return DB::table('mouja_ulo')->select('mouja_id')->where('ulo_office_id', $officeID)->get();
        // return DB::table('division')->select('id', 'division_name_bn')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CaseRegister  $caseRegister
     * @return \Illuminate\Http\Response
     */
    /*public function case_details($id)
    {
        $data['info'] = DB::table('case_register')
        ->join('court', 'case_register.court_id', '=', 'court.id')
        ->join('upazila', 'case_register.upazila_id', '=', 'upazila.id')
        ->join('mouja', 'case_register.mouja_id', '=', 'mouja.id')
        ->join('case_type', 'case_register.ct_id', '=', 'case_type.id')
        ->join('case_status', 'case_register.cs_id', '=', 'case_status.id')
        ->join('case_badi', 'case_register.id', '=', 'case_badi.case_id')
        ->join('case_bibadi', 'case_register.id', '=', 'case_bibadi.case_id')
        ->select('case_register.*', 'court.court_name', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn', 'case_type.ct_name', 'case_status.status_name', 'case_badi.badi_name', 'case_badi.badi_spouse_name', 'case_badi.badi_address', 'case_bibadi.bibadi_name', 'case_bibadi.bibadi_spouse_name', 'case_bibadi.bibadi_address')
        ->where('case_register.id', '=', $id)
        ->first();

        $data['badis'] = DB::table('case_badi')
        ->join('case_register', 'case_badi.case_id', '=', 'case_register.id')
        ->select('case_badi.*')
        ->where('case_badi.case_id', '=', $id)
        ->get();

        $data['bibadis'] = DB::table('case_bibadi')
        ->join('case_register', 'case_bibadi.case_id', '=', 'case_register.id')
        ->select('case_bibadi.*')
        ->where('case_bibadi.case_id', '=', $id)
        ->get();

        $data['surveys'] = DB::table('case_survey')
        ->join('case_register', 'case_survey.case_id', '=', 'case_register.id')
        ->join('survey_type', 'case_survey.st_id', '=', 'survey_type.id')
        ->join('land_type', 'case_survey.lt_id', '=', 'land_type.id')
        ->select('case_survey.*','survey_type.st_name','land_type.lt_name')
        ->where('case_survey.case_id', '=', $id)
        ->get();

        // Get SF Details
        $data['sf'] = DB::table('case_sf')
        ->select('case_sf.*')
        ->where('case_sf.case_id', '=', $id)
        ->first();
        // dd($data['sf']);

        // Get SF Details
        $data['logs'] = DB::table('case_log')
        ->select('case_log.comment', 'case_log.created_at', 'case_status.status_name', 'roles.name', 'users.name')
        ->join('case_status', 'case_status.id', '=', 'case_log.status_id')
        ->leftjoin('roles', 'case_log.send_user_group_id', '=', 'roles.id')
        ->join('users', 'case_log.user_id', '=', 'users.id')
        ->where('case_log.case_id', '=', $id)
        ->orderBy('case_log.id', 'desc')
        ->get();
        // dd($data['sf']);

        // Get SF Details
        $data['hearings'] = DB::table('case_hearing')
        ->select('case_hearing.*')
        ->where('case_hearing.case_id', '=', $id)
        ->orderBy('case_hearing.id', 'desc')
        ->get();

        // Dropdown
        $data['roles'] = DB::table('roles')
        ->select('id', 'name')
        ->where('in_action', '=', 1)
        ->orderBy('sort_order', 'asc')
        ->get();

        // dd($data['bibadis']);

        $data['page_title'] = 'মামলার বিস্তারিত তথ্য'; //exit;
        return view('dashboard.case_details')->with($data);
     }*/



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
        /*$validator = \Validator::make($request->all(), [
            'group' => 'required',
            'comment' => 'required',
            ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        // User Info
        $user = Auth::user();

        // Inputs
        $roleGroup = $request->group;
        $caseID = $request->case_id;
        $input = $request->all();

        // Roles
        if($roleGroup == 1){
            // Superadmin
            $caseStatus = '';
        }elseif($roleGroup == 5){
            // DC Assistant
            $caseStatus = '';
        }elseif($roleGroup == 6){
            // DC
            $caseStatus = 2;
        }elseif($roleGroup == 7){
            // ADC (Revenue)
            $caseStatus = 3;
        }elseif($roleGroup == 8){
            // AC (RM)
            $caseStatus = 4;
        }elseif($roleGroup == 9){
            // AC (Land)
            $caseStatus = 5;
        }elseif($roleGroup == 10){
            // Survyor
            $caseStatus = 6;
        }elseif($roleGroup == 11){
            // Kanongo
            $caseStatus = 7;
        }elseif($roleGroup == 12){
            // ULAO
            $caseStatus = 8;
        }elseif($roleGroup == 13){
            // GP
            $caseStatus = 9;
        }elseif($roleGroup == 14){
            // ULAO
            $caseStatus = 10;
        }


        // Get Case Data
        $case = DB::table('case_register')
        ->select('id', 'cs_id', 'court_id', 'case_number', 'case_date', 'ct_id', 'mouja_id', 'upazila_id', 'district_id', 'tafsil', 'chowhaddi', 'show_cause_file', 'created_at')
        ->where('id', $caseID)
        ->first();
        // dd($case);

        // Insert data into case_log table
        $log_data = [
        'case_id'       => $caseID,
        'status_id'     => $caseStatus,
        'user_id'       => $user->id,
        'send_user_group_id' => $user->role_id,
        'receive_user_group_id' => $roleGroup,
        'comment'      => $request->comment,
        'created_at'    => date('Y-m-d H:i:s'),
        ];
        DB::table('case_log')->insert($log_data);
        // Book::create($input);
        // dd($data_case_log);

        // Update Case Register (cs_id, action_user_group_id, status(2), updated_at) table
        $case_data = [
        'cs_id'     => $caseStatus,
        'action_user_group_id' => $roleGroup,
        'status'       => 2,
        'updated_at'    => date('Y-m-d H:i:s'),
        ];
        DB::table('case_register')->where('id', $caseID)->update($case_data);

        return response()->json(['success'=>'Data is successfully added']);*/
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*public function create_sf(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sf_details' => 'required',
            ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        // User Info
        $user = Auth::user();

        // Inputs
        $caseID = $request->case_id;
        $sfDetails = $request->sf_details;
        // $input = $request->all();
        // dd($input);

        // Insert data into case_sf table
        $sf_data = [
        'case_id'       => $caseID,
        'sf_details'    => $sfDetails,
        'user_id'       => $user->id,
        'created_at'    => date('Y-m-d H:i:s'),
        ];
        DB::table('case_sf')->insert($sf_data);
        // dd($sf_data);

        // Update Case Register (is_sf(1), status(2), updated_at) table
        $case_data = [
        'is_sf'     => 1,
        //'status'       => 2,
        //'updated_at'    => date('Y-m-d H:i:s'),
        ];
        DB::table('case_register')->where('id', $caseID)->update($case_data);

        return response()->json(['success'=>'Data is successfully added','sfdata'=>'Data is successfully added']);
     } */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    /*public function edit_sf(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sf_details' => 'required',
            ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        // Inputs
        $caseID = $request->case_id;
        $sfID = $request->sf_id;
        $sfDetails = $request->sf_details;
        // $input = $request->all();
        // dd($input);

        // Update Case SF table
        $sf_data = [
        'sf_details'  => $sfDetails,
        'updated_at'  => date('Y-m-d H:i:s'),
        ];
        DB::table('case_sf')->where('id', $sfID)->update($sf_data);

        return response()->json(['success'=>'Data is successfully updated','sfdata'=> 'SF Details']);
     }*/

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }

    public function logincheck(){
       if(Auth::check()){
            // dd('checked');
         return redirect('dashboard');
      }else{
         return redirect('login');
      }
   }
    public function public_home(){
       if(Auth::check()){
            // dd('checked');
         return redirect('dashboard');
      }else{
         return view('public_home');
        //  return redirect('login');
      }
   }
}
