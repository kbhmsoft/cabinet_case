<?php

namespace App\Http\Controllers;

// use Auth;
use App\Models\ApplicationFormAsMainDefendent;
use App\Models\Dashboard;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\DoptorUserManagement;
use App\Models\gov_case\GovCaseConcernPerson;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseRegister;
use App\Models\User;
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

        if ($roleID == 27) {

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
                $val->total_running_case = ($this->countHighCourtRunningCase($finalOfficeIds)->count() + $this->countAppealRunningCase($finalOfficeIds)->count());
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

            $data['highcourt_not_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 1)
                ->whereNull('deleted_at')
                ->count();

            $data['highcourt_against_gov'] = GovCaseRegister::where('is_final_order', 1)
                ->where('result', 2)
                ->whereNull('deleted_at')
                ->count();

            $data['appeal_against_gov'] = AppealGovCaseRegister::whereNull('deleted_at')
                ->where('is_final_order', 1)
                ->where('result', 2)
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

            $data['appeal_not_against_gov'] = AppealGovCaseRegister::whereNull('deleted_at')
                ->where('is_final_order', 1)
                ->where('result', 1)
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

            $doptorLoginCount = DoptorUserManagement::whereNotNull('office_id')->count();
            $generalLoginCount = User::whereNull('doptor_user_id')->count();

            return view('dashboard.cabinet_new.super_admin')->with($data + compact('doptorLoginCount', 'generalLoginCount'));
        }
        if ($roleID == 29) {

            $childOfficeIds = DB::table('gov_case_office')
                ->where('parent_office_id', $officeID)
                ->pluck('doptor_office_id')
                ->toArray();

            $finalOfficeIds = array_merge([$officeID], $childOfficeIds);

            $appealCases = DB::table('appeal_gov_case_register')
                ->whereIn('created_by_office', $finalOfficeIds)
                ->whereNull('deleted_at')
                ->select('id', 'is_final_order', 'result')
                ->get();

            $govCases = GovCaseRegister::whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                $query->whereIn('respondent_id', $finalOfficeIds);
            })
                ->select('id', 'is_final_order', 'result')
                ->whereNull('deleted_at')
                ->get();

            $data['running_appeal_case'] = $appealCases->where('is_final_order', 0)->count();
            $data['final_appeal_case'] = $appealCases->where('is_final_order', 1)->count();
            $data['appeal_not_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 1)->count();
            $data['appeal_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 2)->count();
            $data['highcourt_not_against_gov'] = $govCases->where('is_final_order', 1)->where('result', 1)->count();

            $data['appealPending'] = $govCases->where('result', 2)->where('is_appeal', 2)->count();

            $data['highcourt_against_gov'] = $govCases->where('is_final_order', 1)->where('result', 2)->count();

            $data['running_high_court_case'] = $govCases->where('is_final_order', 0)->count();
            // return $data;

            $data['final_high_court_case'] = $govCases->where('is_final_order', 1)->count();

            $data['sent_to_solicitor_case'] = $govCases->whereNull('result_sending_date')->where('is_final_order', 0)->count();

            $data['pendingPostpondOrder'] = $govCases->whereNull('appeal_against_postpond_interim_order')->count();

            $data['ministry'] = DB::table('gov_case_office')
                ->whereIn('parent_office_id', $finalOfficeIds)
                ->orWhereIn('doptor_office_id', $finalOfficeIds)
                ->paginate(10);

            foreach ($data['ministry'] as $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
            }

            $data['page_title'] = 'মিনিস্ট্রি এডমিনের ড্যাশবোর্ড';
            return view('dashboard.cabinet_new.min_admin')->with($data);
        }
        if ($roleID == 31) {

            $childOfficeIds = DB::table('gov_case_office')
                ->where('parent_office_id', $officeID)
                ->pluck('doptor_office_id')
                ->toArray();

            $finalOfficeIds = array_merge([$officeID], $childOfficeIds);

            $appealCases = DB::table('appeal_gov_case_register')
                ->whereIn('created_by_office', $finalOfficeIds)
                ->whereNull('deleted_at')
                ->select('id', 'is_final_order', 'result')
                ->get();

            $govCases = GovCaseRegister::whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                $query->whereIn('respondent_id', $finalOfficeIds);
            })
                ->select('id', 'is_final_order', 'result')
                ->whereNull('deleted_at')
                ->get();

            $data['running_appeal_case'] = $appealCases->where('is_final_order', 0)->count();
            $data['final_appeal_case'] = $appealCases->where('is_final_order', 1)->count();
            $data['appeal_not_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 1)->count();
            $data['appeal_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 2)->count();
            $data['highcourt_not_against_gov'] = $govCases->where('is_final_order', 1)->where('result', 1)->count();

            $data['appealPending'] = $govCases->where('result', 2)->where('is_appeal', 2)->count();

            $data['highcourt_against_gov'] = $govCases->where('is_final_order', 1)->where('result', 2)->count();

            $data['running_high_court_case'] = $govCases->where('is_final_order', 0)->count();
            // return $data;

            $data['final_high_court_case'] = $govCases->where('is_final_order', 1)->count();

            $data['sent_to_solicitor_case'] = $govCases->whereNull('result_sending_date')->where('is_final_order', 0)->count();

            $data['pendingPostpondOrder'] = $govCases->whereNull('appeal_against_postpond_interim_order')->count();

            $data['ministry'] = DB::table('gov_case_office')
                ->whereIn('parent_office_id', $finalOfficeIds)
                ->orWhereIn('doptor_office_id', $finalOfficeIds)
                ->paginate(10);

            foreach ($data['ministry'] as $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
            }

            $data['page_title'] = 'মিনিস্ট্রি এডমিন সহকারীর  ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.min_admin')->with($data);
        }

        // panel lawyer dashboard
        if ($roleID == 45) {

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::where('created_by_office', $officeID)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = AppealGovCaseRegister::where('created_by_office', $officeID)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = AppealGovCaseRegister::where('created_by_office', $officeID)
                ->where('is_final_order', null)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::where('created_by_office', $officeID)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['appealPending'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('result', 2)
                ->where('is_appeal', 2)->where('deleted_at', null)->count();

            $data['appeal_not_against_gov'] = AppealGovCaseRegister::where('created_by_office', $officeID)
                ->whereNull('deleted_at')
                ->where('is_final_order', 1)
                ->where('result', 1)
                ->count();

            $data['appeal_against_gov'] = AppealGovCaseRegister::where('created_by_office', $officeID)
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

            $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('created_by_office', $officeID)
                ->where('is_final_order', 0)
                ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
                ->orderBy('id', 'DESC')
                ->where('deleted_at', null)->count();

            $data['ministry'] = DB::table('gov_case_office')
            // ->where('gov_case_office.parent_office_id', $finalOfficeIds)
                ->where('doptor_office_id', $officeID)
                ->paginate(10);

            $userId = Auth::id();

            $caseCountPanelLawyer = GovCaseConcernPerson::where('concern_user_id', $userId)
                ->distinct('gov_case_id')
                ->count('gov_case_id');
            //panel lawyer dashboard case count

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
            $data['page_title'] = 'প্যানেল আইনজীবীর ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.panel_lawyer')->with($data + ['caseCountPanelLawyer' => $caseCountPanelLawyer]);
        }if ($roleID == 32) {

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->where('is_final_order', null)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = AppealGovCaseRegister::where('created_by_office', $officeID)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['appealPending'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('result', 2)
                ->where('is_appeal', 2)->where('deleted_at', null)->count();

            $data['appeal_not_against_gov'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->whereNull('deleted_at')
                ->where('is_final_order', 1)
                ->where('result', 1)
                ->count();

            $data['appeal_against_gov'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
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

            $data['page_title'] = 'দপ্তর এডমিনের ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.doptor_admin')->with($data);
        }if ($roleID == 41) {

            $data['total_highcourt'] = GovCaseRegister::whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                }
            )->where('deleted_at', null)->count();

            $data['total_appeal'] = AppealGovCaseRegister::where('created_by_office', $officeID)->where('deleted_at', null)->count();

            $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

            $data['running_case'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('status', 1)->where('deleted_at', null)->count();

            $data['total_appeal_case'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->where('deleted_at', null)->count();

            $data['running_appeal_case'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->where('is_final_order', 0)->where('deleted_at', null)->count();

            $data['final_appeal_case'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->where('is_final_order', 1)->where('deleted_at', null)->count();

            $data['appealPending'] = GovCaseRegister::whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            )->where('result', 2)
                ->where('is_appeal', 2)->where('deleted_at', null)->count();

            $data['appeal_not_against_gov'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
                ->whereNull('deleted_at')
                ->where('is_final_order', 1)
                ->where('result', 1)
                ->count();

            $data['appeal_against_gov'] = DB::table('appeal_gov_case_register')->where('created_by_office', $officeID)
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

            $data['page_title'] = 'দপ্তর এডমিন সহকারীর ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.doptor_admin')->with($data);
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

            return view('dashboard.cabinet_new.attorney')->with($data);
        } elseif ($roleID == 34) {

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

        if ($roleID == 39) {

            $appealCases = DB::table('appeal_gov_case_register')
                ->whereNull('deleted_at')
                ->select('id', 'is_final_order', 'result')
                ->get();

            $govCases = GovCaseRegister::select('id', 'is_final_order', 'result')
                ->whereNull('deleted_at')
                ->get();

            $officeTable = DB::table('gov_case_office')->select('id', 'doptor_office_id', 'level')->get();

            $data['running_appeal_case'] = $appealCases->where('is_final_order', 0)
                ->count();
            $data['final_appeal_case'] = $appealCases->where('is_final_order', 1)->count();
            $data['appeal_not_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 1)->count();
            $data['appeal_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 2)->count();

            $data['total_ministry'] = $officeTable->where('level', 1)->count();
            $data['total_division'] = $officeTable->where('level', 3)->count();
            $data['total_district'] = $officeTable->where('level', 4)->count();
            $data['total_doptor'] = $officeTable->whereIn('level', [2, 5])->count();

            $data['sent_to_solicitor_case'] = $govCases->where('is_final_order', 0)
                ->whereNull('result_sending_date')
                ->count();
            $data['appealAgainstGovt'] = $govCases->where('in_favour_govt', 2)
                ->where('is_appeal', 0)->count();

            $data['against_postpond_order'] = $govCases->whereNull('appeal_against_postpond_interim_order')->count();

            $data['sent_to_solicitor_case'] = $govCases->where('is_final_order', 0)
                ->whereNull('result_sending_date')
                ->count();

            $data['highcourt_not_against_gov'] = $govCases->where('is_final_order', 1)
                ->where('result', 1)
                ->count();

            $data['highcourt_against_gov'] = $govCases->where('is_final_order', 1)
                ->where('result', 2)
                ->count();

            $data['running_high_court_case'] = $govCases->where('is_final_order', 0)
                ->count();

            $data['final_high_court_case'] = $govCases->where('is_final_order', 1)->count();

            $data['ministry'] = GovCaseOffice::whereIn('gov_case_office.level', [1, 3])
            ->with('childOffices')
            ->paginate(10);

        $arrayd = [];
        foreach ($data['ministry'] as $ministry) {
            $officeIds = array_merge([$ministry->doptor_office_id], $ministry->childOffices->pluck('doptor_office_id')->toArray());

            $ministry->highcourt_running_case = $this->countHighCourtRunningCase($officeIds)->count();
            $ministry->appeal_running_case = $this->countAppealRunningCase($officeIds)->count();
            $ministry->total_running_case = ($this->countHighCourtRunningCase($officeIds)->count() + $this->countAppealRunningCase($officeIds)->count());
            $ministry->against_gov = $this->countHighCourtAgainstGovCase($officeIds)->count();
            $ministry->result_sending_count = $this->countHighCourtSolicitorPendingCase($officeIds)->count();
            $ministry->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($officeIds)->count();

            array_push($arrayd, $ministry);
        }

            $data['page_title'] = 'মনিটরিং ইউজারের ড্যাশবোর্ড';
            return view('dashboard.cabinet_new.monitoring_user')->with($data);
        }if ($roleID == 44) {

            $appealCases = DB::table('appeal_gov_case_register')
                ->where('created_by_office', $officeID)
                ->whereNull('deleted_at')
                ->select('id', 'is_final_order', 'result')
                ->get();

            $govCases = GovCaseRegister::whereHas('mainBibadis', function ($query) use ($officeID) {
                $query->where('respondent_id', $officeID);
            })
                ->select('id', 'is_final_order', 'result')
                ->whereNull('deleted_at')
                ->get();

            $data['running_appeal_case'] = $appealCases->where('is_final_order', 0)->count();
            $data['final_appeal_case'] = $appealCases->where('is_final_order', 1)->count();
            $data['appeal_not_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 1)->count();
            $data['appeal_against_gov'] = $appealCases->where('is_final_order', 1)->where('result', 2)->count();
            $data['highcourt_not_against_gov'] = $govCases->where('is_final_order', 1)->where('result', 1)->count();

            $data['appealPending'] = $govCases->where('result', 2)->where('is_appeal', 2)->count();

            $data['highcourt_against_gov'] = $govCases->where('is_final_order', 1)->where('result', 2)->count();

            $data['running_high_court_case'] = $govCases->where('is_final_order', 0)->count();
            // return $data;

            $data['final_high_court_case'] = $govCases->where('is_final_order', 1)->count();

            $data['sent_to_solicitor_case'] = $govCases->whereNull('result_sending_date')->where('is_final_order', 0)->count();

            $data['pendingPostpondOrder'] = $govCases->whereNull('appeal_against_postpond_interim_order')->count();

            $data['ministry'] = DB::table('gov_case_office')
                ->where('parent_office_id', $officeID)
                ->orWhere('doptor_office_id', $officeID)
                ->paginate(10);

            foreach ($data['ministry'] as $val) {
                $doptorOfficeId = $val->doptor_office_id;
                $val->highcourt_running_case = $this->countMinistryWiseHighCourtRunningCase($doptorOfficeId)->count();
                $val->appeal_running_case = $this->countMinistryWiseAppealRunningCase($doptorOfficeId)->count();
                $val->against_gov = $this->countMinistryWiseHighCourtAgainstGovCase($doptorOfficeId)->count();
                $val->result_sending_count = $this->countMinistryWiseSolicitorPendingCase($doptorOfficeId)->count();
                $val->against_postponed_count = $this->countMinistryWiseHighCourtAppealPospondOrderPendingCase($doptorOfficeId)->count();
            }

            $data['page_title'] = 'অফিস প্রধানের ড্যাশবোর্ড';

            return view('dashboard.cabinet_new.office_head')->with($data);
        }if ($roleID == 43) {

            $data['page_title'] = 'গেস্ট ইউজারের ড্যাশবোর্ড';
            return view('dashboard.cabinet.cabinet_guest_user')->with($data);
        }
    }

    public function minWiseList()
    {
        $data['ministry'] = DB::table('gov_case_office')
            ->whereIn('gov_case_office.level', [1, 3])
            ->get();

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
            $val->total_running_case = ($this->countHighCourtRunningCase($finalOfficeIds)->count() + $this->countAppealRunningCase($finalOfficeIds)->count());
            $val->against_gov = $this->countHighCourtAgainstGovCase($finalOfficeIds)->count();
            $val->result_sending_count = $this->countHighCourtSolicitorPendingCase($finalOfficeIds)->count();
            $val->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($finalOfficeIds)->count();
            array_push($arrayd, $val);
        }
        $data['page_title'] = 'মন্ত্রনালয় ভিত্তিক মামলার সারমর্ম';
        return view('dashboard.cabinet_new.super_admin_min_wise_list')->with($data);

    }
    public function printMinWiseList()
    {
        $data['ministry'] = DB::table('gov_case_office')
            ->whereIn('gov_case_office.level', [1, 3])
            ->get();

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
            $val->total_running_case = ($this->countHighCourtRunningCase($finalOfficeIds)->count() + $this->countAppealRunningCase($finalOfficeIds)->count());
            $val->against_gov = $this->countHighCourtAgainstGovCase($finalOfficeIds)->count();
            $val->result_sending_count = $this->countHighCourtSolicitorPendingCase($finalOfficeIds)->count();
            $val->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($finalOfficeIds)->count();
            array_push($arrayd, $val);
        }
        $data['page_title'] = 'মন্ত্রনালয় ভিত্তিক মামলার সারমর্ম';
        $html = view('dashboard.cabinet_new.ministry_wise_list_pdf')->with($data);
        $this->generatePDF($html);
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
        $query = AppealGovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)->orderby('id', 'DESC')->whereIn('created_by_office', $id)->get();
        return $query;
    }

    public function countMinistryWiseAppealRunningCase($id)
    {
        $query = AppealGovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)->orderby('id', 'DESC')->where('created_by_office', $id)->get();
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

        $data['highcourt_not_against_gov'] = GovCaseRegister::where('is_final_order', 1)
            ->where('result', 1)
            ->whereNull('deleted_at')
            ->count();

        $data['appealAgainstGovt'] = GovCaseRegister::where('deleted_at', '=', null)->where('in_favour_govt', 2)
            ->where('is_appeal', 0)->count();

        $data['highcourt_against_gov'] = GovCaseRegister::where('is_final_order', 1)
            ->where('result', 2)
            ->whereNull('deleted_at')
            ->count();

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

        $data['appeal_not_against_gov'] = AppealGovCaseRegister::whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 1)
            ->count();

        $data['appeal_against_gov'] = AppealGovCaseRegister::whereNull('deleted_at')
            ->where('is_final_order', 1)
            ->where('result', 2)
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
        // return view('dashboard.cabinet.cabinet_admin_ministry_wise_old')->with($data);
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

    public function showNotifications()
    {
        $highCourtApplicationsCount = ApplicationFormAsMainDefendent::where('court', 2)->distinct('case_no')->count('case_no');
        $appealApplicationsCount = ApplicationFormAsMainDefendent::where('court', 1)->distinct('case_no')->count('case_no');
        $totalApplicationsCount = $highCourtApplicationsCount + $appealApplicationsCount;

        return view('notifications', compact('highCourtApplicationsCount', 'appealApplicationsCount', 'totalApplicationsCount'));
    }

    public function en2bn($number)
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($en, $bn, $number);
    }
    public function generatePDF($html)
    {
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font' => 'kalpurush',
            'format' => 'A4-L',
            'orientation' => 'L',
        ]);
        $mpdf->AddPageByArray([
            'margin-left' => 5,
            'margin-right' => 5,
            'margin-top' => 5,
            'margin-bottom' => 5,
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        // $mpdf->shrink_tables_to_fit = 1;
        $mpdf->use_kwt = true;
    }
}
