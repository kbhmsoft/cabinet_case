<?php

namespace App\Http\Controllers;

// use Auth;
use App\Models\Dashboard;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseRegister;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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

        View::share('notification_count', 0);
        View::share('case_status_highcourt', array());
        View::share('case_status', array());
        $officeInfo = user_office_info();
        $user = Auth::user();

        $roleID = Auth::user()->role_id;

        $officeID = Auth::user()->office_id;

        $data = [];
        $data['rm_case_status'] = [];

        if ($roleID == 1) {
            $data['total_case'] = GovCaseRegister::count();
            $data['running_case'] = GovCaseRegister::where('status', 1)->count();
            $data['appeal_case'] = GovCaseRegister::where('status', 2)->count();
            $data['completed_case'] = GovCaseRegister::where('status', 3)->count();
            $data['running_case_appeal'] = GovCaseRegister::where('status', 1)->count();
            // $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', '!=' , 3)->count();
            $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', 1)->count();
            // $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', '!=' , 3)->count();
            $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', 1)->count();
            $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->where('status', 3)->count();
            $data['against_gov'] = GovCaseRegister::where('in_favour_govt', 0)->where('status', 3)->count();
            $data['total_office'] = DB::table('office')->whereNotIn('id', [1, 2, 7])->count();
            $data['total_ministry'] = DB::table('office')->where('level', 9)->count();
            $data['total_user'] = DB::table('users')->count();
            $data['total_court'] = DB::table('court')->whereNotIn('id', [1, 2])->count();
            $data['total_mouja'] = DB::table('mouja')->count();
            $data['total_ct'] = DB::table('case_type')->count();

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
            $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
            $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

            // View

            $data['page_title'] = 'সুপার অ্যাডমিন ড্যাশবোর্ড';
            return view('dashboard.cabinet.super_admin')->with($data);
            // return view('dashboard.superadmin')->with($data);

        } elseif ($roleID == 27) {

            $data['ministry'] = DB::table('gov_case_office')
                ->whereIn('gov_case_office.level', [1, 3])
                ->paginate(10);

            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $childOfficeIds = [];

                $childOfficeQuery = DB::table('gov_case_office')
                    ->select('id', 'doptor_office_id')
                    ->where('parent_office_id', $val->doptor_office_id)->get();

                foreach ($childOfficeQuery as $childOffice) {
                    $childOfficeIds[] = $childOffice->doptor_office_id;
                }

                $finalOfficeIds = [];

                if (empty($childOfficeIds)) {
                    $finalOfficeIds[] = $val->doptor_office_id;
                } else {
                    $finalOfficeIds[] = $val->doptor_office_id;
                    $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
                }

                $val->highcourt_running_case = $this->countHighCourtRunningCase($finalOfficeIds)->count();
                $val->appeal_running_case = $this->countAppealRunningCase($finalOfficeIds)->count();
                $val->against_gov = $this->countHighCourtAgainstGovCase($finalOfficeIds)->count();
                $val->result_sending_count = $this->countHighCourtSolicitorPendingCase($finalOfficeIds)->count();
                $val->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($finalOfficeIds)->count();
                array_push($arrayd, $val);
            }

            // return $data['ministry'] = $arrayd;

            $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();
            $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
            $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];
            $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();
            // return $data['total_high_court_case'];
            $data['running_high_court_case'] = GovCaseRegister::where('is_final_order', 0)
                ->where('deleted_at', '=', null)
                ->count();

            $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::count();
            $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 0)
                ->where('deleted_at', '=', null)
                ->count();
            $data['final_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 1)->count();

            $data['appealAgainstGovt'] = GovCaseRegister::where('deleted_at', '=', null)->where('result', 2)
                ->where('is_appeal', 2)->count();

            $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->count();

            $data['sent_to_solicitor_case'] = GovCaseRegister::where('deleted_at', '=', null)
                ->where('is_final_order', 0)
                ->whereNull('result_sending_date')
                ->count();

            $data['against_postpond_order'] = GovCaseRegister::where('deleted_at', '=', null)->whereNull('appeal_against_postpond_interim_order')->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::where('deleted_at', '=', null)->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 0)
                ->where('deleted_at', '=', null)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->count();

            $data['most_important_appeal_case'] = AppealGovCaseRegister::orderby('id', 'DESC')
                ->where('deleted_at', '=', null)
                ->where('most_important', 1)
                ->count();

            $data['most_important_highcourt_case'] = GovCaseRegister::orderby('id', 'DESC')
                ->where('deleted_at', '=', null)
                ->where('most_important', 1)
                ->count();

            $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
            $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['total_office'] = DB::table('office')->whereNotIn('id', [1, 2, 7])->count();
            $data['total_ministry'] = DB::table('gov_case_office')->where('level', 1)->count();
            $data['total_division'] = DB::table('gov_case_office')->where('level', 3)->count();
            $data['total_district'] = DB::table('gov_case_office')->where('level', 4)->count();
            $data['total_doptor'] = DB::table('gov_case_office')->whereIn('level', [2, 5])->count();

            $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();

            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
            // $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

            $data['page_title'] = 'মন্ত্রিপরিষদ সচিবের ড্যাশবোর্ড';
            // return view('dashboard.cabinet.cabinet_admin')->with($data);
            return view('dashboard.cabinet_new.super_admin')->with($data);
        } elseif ($roleID == 28) {

            $data['total_case'] = GovCaseRegister::count();
            $data['running_case'] = GovCaseRegister::where('status', 1)->count();
            $data['appeal_case'] = GovCaseRegister::where('status', 2)->count();
            $data['completed_case'] = GovCaseRegister::where('status', 3)->count();
            $data['running_case_appeal'] = GovCaseRegister::where('status', 1)->count();
            // $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', '!=' , 3)->count();
            $data['high_court_case'] = GovCaseRegister::where('case_division_id', 2)->where('status', 1)->count();
            // $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', '!=' , 3)->count();
            $data['appeal_court_case'] = GovCaseRegister::where('case_division_id', 1)->where('status', 1)->count();
            $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->where('status', 3)->count();
            $data['against_gov'] = GovCaseRegister::where('in_favour_govt', 0)->where('status', 3)->count();

            $data['total_office'] = DB::table('office')->whereNotIn('id', [1, 2, 7])->count();
            $data['total_ministry'] = DB::table('office')->where('level', 9)->count();
            $data['total_user'] = DB::table('users')->count();
            $data['total_court'] = DB::table('court')->whereNotIn('id', [1, 2])->count();
            $data['total_mouja'] = DB::table('mouja')->count();
            $data['total_ct'] = DB::table('case_type')->count();

            $data['ministry'] = DB::table('gov_case_office')->whereIn('gov_case_office.level', [1, 3])->paginate(10);

            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $val->highcourt_running_case = $this->countHighCourtRunningCase($val->id)->count();
                $val->appeal_running_case = $this->countAppealRunningCase($val->id)->count();
                $val->against_gov = $this->countHighCourtAgainstGovCase($val->id)->count();
                $val->result_sending_count = $this->countHighCourtAppealPendingCase($val->id)->count();
                $val->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($val->id)->count();
                array_push($arrayd, $val);
            }

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
            $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
            $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

            // return $data;
            // View
            $data['page_title'] = 'মন্ত্রিপরিষদ সচিবের সহকারীর ড্যাশবোর্ড';
            return view('dashboard.cabinet.cabinet_admin')->with($data);
        }
         elseif ($roleID == 29) {

            $childOfficeIds = [];
            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id', 'doptor_office_id')
                ->where('parent_office_id', $officeID)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->doptor_office_id;
            }

            $finalOfficeIds = [];
            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $officeID;
            } else {
                $finalOfficeIds[] = $officeID;
                $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            }

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->where('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', null)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['appealPending'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->where('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
                }
            )->where('result', 2)
            ->where('is_appeal', 2)->where('deleted_at', null)->count();

            $data['appeal_not_against_gov'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
            ->whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 1)
            ->count();

            $data['appeal_against_gov'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
            ->whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 2)
            ->count();

            $data['highcourt_not_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 1)
                ->whereNull('deleted_at')
                ->whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                })
                ->count();

            $data['highcourt_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 2)
                ->whereNull('deleted_at')
                ->whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                })
                ->count();

            $data['highcourt_against_gov '] = 0;
            $data['total_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->count();

            $data['running_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['against_high_court_case_appeal_pending'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->where('result', 2)->where('is_appeal', 2)->count();

            $data['not_against_gov'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('in_favour_govt', 1)->where('deleted_at', null)->count();

            $data['total_office_list'] = GovCaseOffice::select('gov_case_office.id', 'gov_case_office.office_name_bn')
                ->whereIn('id', $finalOfficeIds)
                ->get();

            $data['sent_to_solicitor_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->whereNull('result_sending_date')->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['pendingPostpondOrder'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->whereNull('appeal_against_postpond_interim_order')->where('deleted_at', null)->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['ministry'] = DB::table('gov_case_office')
                ->where('gov_case_office.parent_office_id', $finalOfficeIds)
                ->orwhere('doptor_office_id', $finalOfficeIds)
                ->paginate(10);

            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
                array_push($arrayd, $val);
            }

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();

            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();


            $data['page_title'] = 'মিনিস্ট্রি এডমিনের ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.min_admin')->with($data);
        }
        elseif ($roleID == 30) {

            $data['total_case'] = DB::table('gov_case_registers')->count();
            $data['running_case'] = DB::table('gov_case_registers')->where('status', 1)->count();
            $data['appeal_case'] = DB::table('gov_case_registers')->where('status', 2)->count();
            $data['completed_case'] = DB::table('gov_case_registers')->where('status', 3)->count();

            $data['total_office'] = DB::table('office')->whereIn('id', [10, 12, 11])->count();
            $data['total_user'] = DB::table('users')->count();
            $data['total_court'] = DB::table('court')->whereNotIn('id', [1, 2])->count();
            $data['total_mouja'] = DB::table('mouja')->count();
            $data['total_ct'] = DB::table('case_type')->count();

            $data['cases'] = DB::table('gov_case_registers')
                ->select('gov_case_registers.*')
                ->get();

            // Drildown Statistics
            $division_list = DB::table('division')
                ->select('division.id', 'division.division_name_bn', 'division.division_name_en')
                ->get();

            $divisiondata = array();
            $districtdata = array();
            // $dis_data=array();
            $upazilatdata = array();

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
            // $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

            $data['page_title'] = 'মন্ত্রণালয়ের সচিবের সহকারীর ড্যাশবোর্ড';
            return view('dashboard.cabinet.admin')->with($data);
        }
         elseif ($roleID == 31) {

            $childOfficeIds = [];
            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id', 'doptor_office_id')
                ->where('parent_office_id', $officeID)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->doptor_office_id;
            }

            $finalOfficeIds = [];
            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $officeID;
            } else {
                $finalOfficeIds[] = $officeID;
                $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            }

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->where('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', null)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['appealPending'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->where('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
                }
            )->where('result', 2)
            ->where('is_appeal', 2)->where('deleted_at', null)->count();

            $data['appeal_not_against_gov'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
            ->whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 1)
            ->count();

            $data['appeal_against_gov'] = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
            ->whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 2)
            ->count();

            $data['highcourt_not_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 1)
                ->whereNull('deleted_at')
                ->whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                })
                ->count();

            $data['highcourt_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 2)
                ->whereNull('deleted_at')
                ->whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                })
                ->count();

            $data['highcourt_against_gov '] = 0;
            $data['total_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->count();

            $data['running_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['against_high_court_case_appeal_pending'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->where('result', 2)->where('is_appeal', 2)->count();

            $data['not_against_gov'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('in_favour_govt', 1)->where('deleted_at', null)->count();

            $data['total_office_list'] = GovCaseOffice::select('gov_case_office.id', 'gov_case_office.office_name_bn')
                ->whereIn('id', $finalOfficeIds)
                ->get();

            $data['sent_to_solicitor_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->whereNull('result_sending_date')->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['pendingPostpondOrder'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->whereNull('appeal_against_postpond_interim_order')->where('deleted_at', null)->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['ministry'] = DB::table('gov_case_office')
                ->where('gov_case_office.parent_office_id', $finalOfficeIds)
                ->orwhere('doptor_office_id', $finalOfficeIds)
                ->paginate(10);

            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
                array_push($arrayd, $val);
            }

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();

            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();


            $data['page_title'] = 'মিনিস্ট্রি এডমিন সহকারীর ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.min_admin')->with($data);
        }
         elseif ($roleID == 32) {

            // $childOfficeIds = [];
            // $childOfficeQuery = DB::table('gov_case_office')
            //     ->select('id', 'doptor_office_id')
            //     ->where('parent_office_id', $officeID)->get();

            // foreach ($childOfficeQuery as $childOffice) {
            //     $childOfficeIds[] = $childOffice->doptor_office_id;
            // }

            // $finalOfficeIds = [];
            // if (empty($childOfficeIds)) {
            //     $finalOfficeIds[] = $officeID;
            // } else {
            //     $finalOfficeIds[] = $officeID;
            //     $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            // }

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)
                ->where('is_final_order', null)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['appealPending'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('result', 2)
            ->where('is_appeal', 2)->where('deleted_at', null)->count();

            $data['appeal_not_against_gov'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)
            ->whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 1)
            ->count();

            $data['appeal_against_gov'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)
            ->whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 2)
            ->count();

            $data['highcourt_not_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 1)
                ->whereNull('deleted_at')
                ->whereHas('mainBibadis', function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                })
                ->count();

            $data['highcourt_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 2)
                ->whereNull('deleted_at')
                ->whereHas('mainBibadis', function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                })
                ->count();

            $data['highcourt_against_gov '] = 0;
            $data['total_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('deleted_at', null)->count();

            $data['running_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['against_high_court_case_appeal_pending'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('deleted_at', null)->where('result', 2)->where('is_appeal', 2)->count();

            $data['not_against_gov'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('in_favour_govt', 1)->where('deleted_at', null)->count();

            $data['total_office_list'] = GovCaseOffice::select('gov_case_office.id', 'gov_case_office.office_name_bn')
                ->where('id', $officeID)
                ->get();

            $data['sent_to_solicitor_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->whereNull('result_sending_date')->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['pendingPostpondOrder'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->whereNull('appeal_against_postpond_interim_order')->where('deleted_at', null)->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $officeID)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['ministry'] = DB::table('gov_case_office')
                // ->where('gov_case_office.parent_office_id', $finalOfficeIds)
                ->where('doptor_office_id', $officeID)
                ->paginate(10);

            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
                array_push($arrayd, $val);
            }

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();

            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();


            $data['page_title'] = 'দপ্তর এডমিনের ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.doptor_admin')->with($data);

        }
         elseif ($roleID == 41) {
            $childOfficeIds = [];

            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id', 'doptor_office_id')
                ->where('parent_office_id', $officeID)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->doptor_office_id;
            }

            $finalOfficeIds = [];
            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $officeID;
            } else {
                $finalOfficeIds[] = $officeID;
                $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            }

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->where('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['total_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('deleted_at', null)->count();

            $data['running_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_high_court_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['against_high_court_case_appeal_pending'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->where('in_favour_govt', 2)->where('is_appeal', 0)->where('deleted_at', null)->count();

            $data['not_against_gov'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('in_favour_govt', 1)->where('deleted_at', null)->count();

            $data['total_office_list'] = GovCaseOffice::select('gov_case_office.id', 'gov_case_office.office_name_bn')
                ->whereIn('id', $finalOfficeIds)
                ->get();

            $data['sent_to_solicitor_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )
                ->whereNull('result_sending_date')
                ->where('is_final_order', 0)
                ->where('deleted_at', null)
                ->count();

            $data['against_postpond_order'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )->whereNull('appeal_against_postpond_interim_order')->where('deleted_at', null)->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds);
                }
            )
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('appeal_office_id', $finalOfficeIds)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['ministry'] = DB::table('gov_case_office')
                ->where('gov_case_office.parent_office_id', $finalOfficeIds)
                ->orwhere('doptor_office_id', $finalOfficeIds)
                ->paginate(10);

            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
                array_push($arrayd, $val);
            }
            // return $arrayd;
            $ministrydata = array();
            $departmentdata = array();

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
            // $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
            // $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();
            $data['page_title'] = 'দপ্তর এডমিন সহকারীর ড্যাশবোর্ড';
            return view('dashboard.cabinet.dept_admin')->with($data);
        } elseif ($roleID == 33 || $roleID == 36 || $roleID == 14 || $roleID == 15) {
            $authUserId = Auth()->user()->id;

            $childOfficeIds = [];
            // $childOfficeQuery = DB::table('gov_case_office')
            //     ->select('id')
            //     ->where('parent', $officeID)->get();

            // foreach ($childOfficeQuery as $childOffice) {
            //     $childOfficeIds[] = $childOffice->id;
            // }

            // $finalOfficeIds = [];
            // if (empty($childOfficeIds)) {
            //     $finalOfficeIds[] = $officeID;
            // } else {
            //     $finalOfficeIds[] = $officeID;
            //     $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            // }

            $data['total_highcourt'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::where('concern_user_id', $authUserId)
                ->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            // $data['running_case'] = GovCaseRegister::whereHas(
            //     'bibadis',
            //     function ($query) use ($finalOfficeIds) {
            //         $query->where('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
            //     }
            // )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::where('concern_user_id', $authUserId)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = AppealGovCaseRegister::where('concern_user_id', $authUserId)
                ->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::where('concern_user_id', $authUserId)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['total_high_court_case'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('deleted_at', null)->count();

            $data['running_high_court_case'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_high_court_case'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();
            // return $data['final_high_court_case'];
            $data['against_high_court_case_appeal_pending'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('in_favour_govt', 2)->where('is_appeal', 0)->where('deleted_at', null)->count();

            $data['not_against_gov'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('in_favour_govt', 1)->where('deleted_at', null)->count();

            // $data['total_office_list'] = GovCaseOffice::select('gov_case_office.id', 'gov_case_office.office_name_bn')
            //     ->whereIn('id', $finalOfficeIds)
            //     ->get();

            $data['sent_to_solicitor_case'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->whereNull('result_sending_date')
                ->where('is_final_order', 0)
                ->where('deleted_at', null)->count();

            $data['against_postpond_order'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->whereNull('appeal_against_postpond_interim_order')->where('deleted_at', null)->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::where('concern_user_id', $authUserId)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('concern_user_id', $authUserId)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $officeId = Auth::user()->office_id;
            // $data['ministry'] = DB::table('gov_case_office')
            //     ->where('gov_case_office.parent', $finalOfficeIds)
            //     ->orwhere('id', $finalOfficeIds)
            //     ->paginate(10);

            $arrayd = [];
            // foreach ($data['ministry'] as $key => $val) {
            //     $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($val->id)->count();
            //     $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($val->id)->count();
            //     $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($val->id)->count();
            //     $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($val->id)->count();
            //     $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($val->id)->count();
            //     array_push($arrayd, $val);
            // }

            $ministrydata = array();
            $departmentdata = array();

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();

            // $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
            //return $roleID;
            if ($roleID == 15) {
                $data['page_title'] = 'অ্যাটর্নি-জেনারেল ড্যাশবোর্ড';
            }
            if ($roleID == 14) {
                $data['page_title'] = 'অতিরিক্ত-অ্যাটর্নি-জেনারেল ড্যাশবোর্ড';
            }
            if ($roleID == 33) {
                $data['page_title'] = 'ডেপুটি-অ্যাটর্নি-জেনারেল ড্যাশবোর্ড';
            }
            if ($roleID == 36) {
                $data['page_title'] = 'সহকারী-অ্যাটর্নি-জেনারেল ড্যাশবোর্ড';
            }

            return view('dashboard.cabinet.dept_attorney')->with($data);
        } elseif ($roleID == 34) {
            // Attorney General
            // Get case status by group
            // Counter
            $data['total_case'] = DB::table('gov_case_registers')->count();
            $data['running_case'] = DB::table('gov_case_registers')->where('status', 1)->count();
            $data['appeal_case'] = DB::table('gov_case_registers')->where('status', 2)->count();
            $data['completed_case'] = DB::table('gov_case_registers')->where('status', 3)->count();

            $data['cases'] = DB::table('gov_case_registers')
                ->select('gov_case_registers.*')
                ->get();

            $data['case_status'] = DB::table('gov_case_registers')
                ->select('gov_case_registers.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
                ->leftJoin('case_status', 'gov_case_registers.cs_id', '=', 'case_status.id')
                ->groupBy('gov_case_registers.cs_id')
                ->where('gov_case_registers.action_user_group_id', $roleID)
                ->get();

            $data['page_title'] = 'অতিরিক্ত-অ্যাটর্নি-জেনারেল';
            return view('dashboard.cabinet.officer')->with($data);
        } elseif ($roleID == 35) {
            // Attorney General
            // Get case status by group
            // Counter
            $data['total_case'] = DB::table('gov_case_registers')->count();
            $data['running_case'] = DB::table('gov_case_registers')->where('status', 1)->count();
            $data['appeal_case'] = DB::table('gov_case_registers')->where('status', 2)->count();
            $data['completed_case'] = DB::table('gov_case_registers')->where('status', 3)->count();

            $data['cases'] = DB::table('gov_case_registers')
                ->select('gov_case_registers.*')
                ->get();

            $data['case_status'] = DB::table('gov_case_registers')
                ->select('gov_case_registers.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
                ->leftJoin('case_status', 'gov_case_registers.cs_id', '=', 'case_status.id')
                ->groupBy('gov_case_registers.cs_id')
                ->where('gov_case_registers.action_user_group_id', $roleID)
                ->get();
            // dd($data['case_status']);

            $data['page_title'] = 'ডেপুটি-অ্যাটর্নি-জেনারেল';
            return view('dashboard.cabinet.officer')->with($data);
        }

        /////////////   মনিটরিং ইউজার  //////////////////

        elseif ($roleID == 39) {

            $data['ministry'] = DB::table('gov_case_office')
                ->whereIn('gov_case_office.level', [1, 3])
                ->paginate(10);
            $arrayd = [];
            foreach ($data['ministry'] as $key => $val) {
                $childOfficeIds = [];

                // $childOfficeQuery = DB::table('gov_case_office')
                //     ->select('id')
                //     ->where('parent', $val->id)->get();

                $childOfficeQuery = DB::table('gov_case_office')
                    ->select('id', 'doptor_office_id')
                    ->where('parent_office_id', $val->doptor_office_id)->get();

                foreach ($childOfficeQuery as $childOffice) {
                    $childOfficeIds[] = $childOffice->doptor_office_id;
                }

                $finalOfficeIds = [];

                if (empty($childOfficeIds)) {
                    $finalOfficeIds[] = $val->doptor_office_id;
                } else {
                    $finalOfficeIds[] = $val->doptor_office_id;
                    $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
                }

                $val->highcourt_running_case = $this->countHighCourtRunningCase($finalOfficeIds)->count();
                $val->appeal_running_case = $this->countAppealRunningCase($finalOfficeIds)->count();
                $val->against_gov = $this->countHighCourtAgainstGovCase($finalOfficeIds)->count();
                $val->result_sending_count = $this->countHighCourtSolicitorPendingCase($finalOfficeIds)->count();
                $val->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($finalOfficeIds)->count();
                array_push($arrayd, $val);
            }

            $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();
            $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
            $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];
            $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();
            // return $data['total_high_court_case'];
            $data['running_high_court_case'] = GovCaseRegister::where('is_final_order', 0)
                ->where('deleted_at', '=', null)
                ->count();

            $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::count();
            $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 0)
                ->where('deleted_at', '=', null)
                ->count();
            $data['final_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 1)->count();

            $data['appealAgainstGovt'] = GovCaseRegister::where('deleted_at', '=', null)->where('result', 2)
                ->where('is_appeal', 2)->count();

            $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->count();

            $data['sent_to_solicitor_case'] = GovCaseRegister::where('deleted_at', '=', null)
                ->where('is_final_order', 0)
                ->whereNull('result_sending_date')
                ->count();

            $data['against_postpond_order'] = GovCaseRegister::where('deleted_at', '=', null)->whereNull('appeal_against_postpond_interim_order')->count();

            $data['five_years_running_highcourt_case'] = GovCaseRegister::where('deleted_at', '=', null)->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->count();

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 0)
                ->where('deleted_at', '=', null)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->count();

            $data['most_important_appeal_case'] = AppealGovCaseRegister::orderby('id', 'DESC')
                ->where('deleted_at', '=', null)
                ->where('most_important', 1)
                ->count();

            $data['most_important_highcourt_case'] = GovCaseRegister::orderby('id', 'DESC')
                ->where('deleted_at', '=', null)
                ->where('most_important', 1)
                ->count();

            $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
            $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['total_office'] = DB::table('office')->whereNotIn('id', [1, 2, 7])->count();
            $data['total_ministry'] = DB::table('gov_case_office')->where('level', 1)->count();
            $data['total_division'] = DB::table('gov_case_office')->where('level', 3)->count();
            $data['total_district'] = DB::table('gov_case_office')->where('level', 4)->count();
            $data['total_doptor'] = DB::table('gov_case_office')->whereIn('level', [2, 5])->count();

            $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

            $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
            $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
            // $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
            // return $data['sent_to_solicitor_case'];
            $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
            // $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

            $data['page_title'] = 'মনিটরিং ইউজারের ড্যাশবোর্ড';
            return view('dashboard.cabinet.cabinet_admin')->with($data);
        } else if ($roleID == 43) {

            $data['page_title'] = 'গেস্ট ইউজারের ড্যাশবোর্ড';
            return view('dashboard.cabinet.cabinet_guest_user')->with($data);
        }

    }

    public function countHighCourtRunningCase($id)
    {
        $query = GovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)
            ->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }

    public function countMinistryWiseHighCourtRunningCase($id)
    {

        $query = GovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }

    public function countHighCourtAgainstGovCase($id)
    {
        $query = GovCaseRegister::where('result', 2)->where('is_appeal', 2)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }

    public function countMinistryWiseHighCourtAgainstGovCase($id)
    {
        $query = GovCaseRegister::where('result', 2)->where('is_appeal', 2)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }
    public function countHighCourtSolicitorPendingCase($id)
    {
        $query = GovCaseRegister::whereNull('result_sending_date')
            ->where('is_final_order', 0)
            ->where('deleted_at', null)
            ->orderby('id', 'DESC')
            ->whereHas('bibadis', function ($query) use ($id) {
                $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
            })->get();
        return $query;
    }

    public function countMinistryWiseSolicitorPendingCase($id)
    {
        $query = GovCaseRegister::whereNull('result_sending_date')
            ->where('is_final_order', 0)
            ->where('deleted_at', null)
            ->orderby('id', 'DESC')
            ->whereHas('bibadis', function ($query) use ($id) {
                $query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
            })->get();
        return $query;
    }
    public function countHighCourtAppealPospondOrderPendingCase($id)
    {
        $query = GovCaseRegister::where('appeal_against_postpond_interim_order', null)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }

    public function countMinistryWiseHighCourtAppealPospondOrderPendingCase($id)
    {
        $query = GovCaseRegister::where('appeal_against_postpond_interim_order', null)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->where('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }
    public function countAppealRunningCase($id)
    {
        $query = AppealGovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)->orderby('id', 'DESC')->whereIn('appeal_office_id', $id)->get();
        return $query;
    }

    public function countMinistryWiseAppealRunningCase($id)
    {
        $query = AppealGovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)->orderby('id', 'DESC')->where('appeal_office_id', $id)->get();
        return $query;
    }
    public function hearing_date_today()
    {
        $data['hearing'] = DB::table('case_hearing')
            ->join('gov_case_registers', 'case_hearing.case_id', '=', 'gov_case_registers.id')
            ->join('court', 'gov_case_registers.court_id', '=', 'court.id')
            ->join('upazila', 'gov_case_registers.upazila_id', '=', 'upazila.id')
            ->join('mouja', 'gov_case_registers.mouja_id', '=', 'mouja.id')
            ->select('case_hearing.*', 'gov_case_registers.id', 'gov_case_registers.court_id', 'gov_case_registers.case_number', 'gov_case_registers.status', 'court.court_name')
            ->where('case_hearing.hearing_date', '=', date('Y-m-d'))
            ->get();

        // dd($data['hearing']);

        $data['page_title'] = 'আজকের দিনে শুনানী/মামলার তারিখ';
        return view('dashboard.hearing_date')->with($data);
    }

    public function hearing_date_tomorrow()
    {
        $d = date('Y-m-d', strtotime('+1 day'));
        $data['hearing'] = DB::table('case_hearing')
            ->join('gov_case_registers', 'case_hearing.case_id', '=', 'gov_case_registers.id')
            ->join('court', 'gov_case_registers.court_id', '=', 'court.id')
            ->join('upazila', 'gov_case_registers.upazila_id', '=', 'upazila.id')
            ->join('mouja', 'gov_case_registers.mouja_id', '=', 'mouja.id')
            ->select('case_hearing.*', 'gov_case_registers.id', 'gov_case_registers.court_id', 'gov_case_registers.case_number', 'gov_case_registers.status', 'court.court_name')
            ->where('case_hearing.hearing_date', '=', $d)
            ->get();

        // dd($data['hearing']);

        $data['page_title'] = 'আগামী দিনে শুনানী/মামলার তারিখ';
        return view('dashboard.hearing_date')->with($data);
    }

    public function hearing_date_nextWeek()
    {

        $d = date('Y-m-d', strtotime('+7 day'));
        $data['hearing'] = DB::table('case_hearing')
            ->join('gov_case_registers', 'case_hearing.case_id', '=', 'gov_case_registers.id')
            ->join('court', 'gov_case_registers.court_id', '=', 'court.id')
            ->join('upazila', 'gov_case_registers.upazila_id', '=', 'upazila.id')
            ->join('mouja', 'gov_case_registers.mouja_id', '=', 'mouja.id')
            ->select('case_hearing.*', 'gov_case_registers.id', 'gov_case_registers.court_id', 'gov_case_registers.case_number', 'gov_case_registers.status', 'court.court_name')
            ->where('case_hearing.hearing_date', '>=', date('Y-m-d'))
            ->where('case_hearing.hearing_date', '<=', $d)
            ->get();

        // dd($data['hearing']);

        $data['page_title'] = 'আগামী সপ্তাহের শুনানী/মামলার তারিখ';
        return view('dashboard.hearing_date')->with($data);
    }

    public function hearing_date_nextMonth()
    {
        $d = date('Y-m-d', strtotime('+1 month'));
        /* $m = date('m',strtotime($d));
        dd($d);*/
        $data['hearing'] = DB::table('case_hearing')
            ->join('gov_case_registers', 'case_hearing.case_id', '=', 'gov_case_registers.id')
            ->join('court', 'gov_case_registers.court_id', '=', 'court.id')
            ->join('upazila', 'gov_case_registers.upazila_id', '=', 'upazila.id')
            ->join('mouja', 'gov_case_registers.mouja_id', '=', 'mouja.id')
            ->select('case_hearing.*', 'gov_case_registers.id', 'gov_case_registers.court_id', 'gov_case_registers.case_number', 'gov_case_registers.status', 'court.court_name')
            ->where('case_hearing.hearing_date', '>=', date('Y-m-d'))
            ->where('case_hearing.hearing_date', '<=', $d)
            ->get();

        // dd($data['hearing']);

        $data['page_title'] = 'আগামী মাসের শুনানী/মামলার তারিখ';
        return view('dashboard.hearing_date')->with($data);
    }

    public function hearing_case_details($id)
    {

        $data['info'] = DB::table('gov_case_registers')
            ->join('court', 'gov_case_registers.court_id', '=', 'court.id')
            ->join('upazila', 'gov_case_registers.upazila_id', '=', 'upazila.id')
            ->join('mouja', 'gov_case_registers.mouja_id', '=', 'mouja.id')
        // ->join('case_type', 'gov_case_registers.ct_id', '=', 'case_type.id')
            ->join('case_status', 'gov_case_registers.cs_id', '=', 'case_status.id')
        // ->join('case_badi', 'gov_case_registers.id', '=', 'case_badi.case_id')
        // ->join('case_bibadi', 'gov_case_registers.id', '=', 'case_bibadi.case_id')
            ->select('gov_case_registers.*', 'court.court_name', 'upazila.upazila_name_bn', 'mouja.mouja_name_bn', 'case_status.status_name')
            ->where('gov_case_registers.id', '=', $id)
            ->first();
        // dd($data['info']);
        // dd($data['info']);

        $data['badis'] = DB::table('case_badi')
            ->join('gov_case_registers', 'case_badi.case_id', '=', 'gov_case_registers.id')
            ->select('case_badi.*')
            ->where('case_badi.case_id', '=', $id)
            ->get();

        $data['bibadis'] = DB::table('case_bibadi')
            ->join('gov_case_registers', 'case_bibadi.case_id', '=', 'gov_case_registers.id')
            ->select('case_bibadi.*')
            ->where('case_bibadi.case_id', '=', $id)
            ->get();

        $data['surveys'] = DB::table('case_survey')
            ->join('gov_case_registers', 'case_survey.case_id', '=', 'gov_case_registers.id')
            ->join('survey_type', 'case_survey.st_id', '=', 'survey_type.id')
            ->join('land_type', 'case_survey.lt_id', '=', 'land_type.id')
            ->select('case_survey.*', 'survey_type.st_name', 'land_type.lt_name')
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

    public function ministryWiseData($ministry_id)
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        // $data['ministry_wise'] = DB::table('gov_case_office')->where('gov_case_office.parent', $ministry_id)->orwhere('id', $ministry_id)->paginate(10);

        $data['ministry_wise'] = DB::table('gov_case_office')->where('gov_case_office.parent_office_id', $ministry_id)->orwhere('doptor_office_id', $ministry_id)->paginate(10);

        $arrayd = [];
        foreach ($data['ministry_wise'] as $key => $val) {
            $doptorOfficeId = $val->doptor_office_id;
            $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
            $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
            $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
            $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
            $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
            array_push($arrayd, $val);
        }
        //    return $arrayd;
        $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];
        $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();

        $data['running_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)
            ->where('is_final_order', 0)->count();

        $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

        $data['total_appeal_case'] = AppealGovCaseRegister::count();
        $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 0)->count();
        $data['final_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 1)->count();

        $data['appealAgainstGovt'] = GovCaseRegister::where('deleted_at', '=', null)->where('in_favour_govt', 2)
            ->where('is_appeal', 0)->count();

        $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->count();

        $data['sent_to_solicitor_case'] = GovCaseRegister::where('deleted_at', '=', null)->whereNull('result_sending_date')->count();

        $data['against_postpond_order'] = GovCaseRegister::where('deleted_at', '=', null)->whereNull('appeal_against_postpond_interim_order')->count();

        $data['five_years_running_highcourt_case'] = GovCaseRegister::where('deleted_at', '=', null)->where('is_final_order', 0)
            ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
            ->orderBy('id', 'DESC')
            ->count();

        $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 0)
            ->where('deleted_at', '=', null)
            ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
            ->orderBy('id', 'DESC')
            ->count();

        $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();

        $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

        $data['total_office'] = DB::table('office')->whereNotIn('id', [1, 2, 7])->count();
        $data['total_ministry'] = DB::table('gov_case_office')->where('level', 1)->count();
        $data['total_division'] = DB::table('gov_case_office')->where('level', 3)->count();
        $data['total_district'] = DB::table('gov_case_office')->where('level', 4)->count();
        $data['total_doptor'] = DB::table('gov_case_office')->whereIn('level', [2, 5])->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
        $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

        $data['page_title'] = 'মন্ত্রিপরিষদ সচিবের ড্যাশবোর্ড';
        return view('dashboard.cabinet.cabinet_admin_ministry_wise')->with($data);
    }

    public function get_drildown_gov_case_count($ministry = null, $department = null, $status = null)
    {
        $query = DB::table('gov_case_bibadis');

        if ($ministry != null) {
            $query->where('respondent_id', $ministry);
            $query->where('is_main_bibadi', 1);
            // $query->groupBy('gov_case_id');
        }
        if ($department != null) {
            $query->where('department_id', $department);
            $query->where('is_main_bibadi', 1);
            // $query->groupBy('gov_case_id');
        }
        /*if($upazila != NULL){
        $query->where('upazila_id', $upazila);
        }*/

        return $query->count();
    }

    public function get_mouja_by_ulo_office_id($officeID)
    {
        return DB::table('mouja_ulo')->where('ulo_office_id', $officeID)->pluck('mouja_id');
        // return DB::table('mouja_ulo')->select('mouja_id')->where('ulo_office_id', $officeID)->get();
        // return DB::table('division')->select('id', 'division_name_bn')->get();
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

    }

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

    public function logincheck()
    {
        if (Auth::check()) {
            // dd(Auth::user());
            return redirect('dashboard');
        } else {
            // return redirect('login');
            return view('frontend.portalpage');
        }
    }
    public function public_home()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        } else {
            return view('public_home');
        }
    }
    public function logoutUser()
    {
        $userData = Auth::user();

        // if ($userData->doptor_user_id == null) {
        //     Auth::logout();
        //     return redirect('login');
        // }

        Auth::logout();
        session()->flush();
        $callbackurl = url('/');
        $zoom_join_url = DOPTOR_ENDPOINT() . '/logout?' . 'referer=' . base64_encode($callbackurl);
        // $zoom_join_url = 'https://api-training.doptor.gov.bd' . '/logout?' . 'referer=' . base64_encode($callbackurl);
        return redirect()->away($zoom_join_url);
    }
}
