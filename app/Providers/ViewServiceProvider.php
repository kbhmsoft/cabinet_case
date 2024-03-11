<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\MainRespondentNotification;
use App\Models\Message;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends AppServiceProvider
{
    public function boot()
    {
        // $this->messages_inc_search();

        // $this->composeFooter();
        // Schema::defaultstringLength(191);
        // Paginator::useBootstrap();

        // view()->composer('home', function ($view)
        // {
        //     $users = Auth::user()->id;

        //     $view->with('users', $users);
        // });

        view()->composer('layouts.cabinet.base.header', function ($view) {
            $roleID = Auth::user()->role_id;
            $officeID = Auth::user()->office_id;
            $childOfficeIds = [];
            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id', 'doptor_office_id')
                ->where('parent_office_id', $officeID)
                ->get();

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

            $total_highcourt = GovCaseRegister::whereHas('mainBibadis', function ($query) use ($finalOfficeIds) {
                $query->whereIn('respondent_id', $finalOfficeIds);
            })
                ->where('deleted_at', null)
                ->count();

            $total_appeal = AppealGovCaseRegister::whereIn('appeal_office_id', $finalOfficeIds)
                ->where('deleted_at', null)
                ->count();

            $total_case = $total_highcourt + $total_appeal;

            if ($roleID == 32 || $roleID == 41) {

                $total_highcourt = GovCaseRegister::whereHas('mainBibadis', function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
                })
                    ->where('deleted_at', null)
                    ->count();

                $total_appeal = AppealGovCaseRegister::where('appeal_office_id', $officeID)
                    ->where('deleted_at', null)
                    ->count();

                $total_case = $total_highcourt + $total_appeal;
            }

            if ($roleID == 27) {

                $total_highcourt = GovCaseRegister::where('deleted_at', null)
                    ->count();

                $total_appeal = AppealGovCaseRegister::where('deleted_at', null)
                    ->count();

                $total_case = $total_highcourt + $total_appeal;
            }

            $view->with([
                'total_highcourt' => $total_highcourt,
                'total_appeal' => $total_appeal,
                'total_case' => $total_case,
            ]);

        });

        view()->composer('messages.inc.search', function ($view) {
            $roleID = Auth::user()->role_id;
            $officeInfo = user_office_info();
            // Dorpdown
            $upazilas = null;
            $courts = DB::table('court')->select('id', 'court_name')->get();
            $divisions = DB::table('division')->select('id', 'division_name_bn')->get();
            $user_role = DB::table('roles')->select('id', 'name')->get();

            if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13 || $roleID == 16) {
                $courts = DB::table('court')->select('id', 'court_name')->where('district_id', $officeInfo->district_id)->orWhere('district_id', null)->get();
                $upazilas = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();

            } elseif ($roleID == 9 || $roleID == 10 || $roleID == 11 || $roleID == 12) {
                $courts = DB::table('court')->select('id', 'court_name')->where('district_id', $officeInfo->district_id)->orWhere('district_id', null)->get();
            }

            $gp_users = DB::table('users')->select('id', 'name')->where('role_id', 13)->get();

            $view->with([
                'upazilas' => $upazilas,
                'courts' => $courts,
                'divisions' => $divisions,
                'gp_users' => $gp_users,
                'user_role' => $user_role,
            ]);

        });

        view()->composer('layouts.cabinet.base.aside', function ($view) {
            $notification_count = 0;
            $case_status_highcourt = [];
            $notificationCount = 0;
            $officeInfo = user_office_info();
            $roleID = Auth::user()->role_id;

            if ($roleID == 29 || $roleID == 31 || $roleID == 32 || $roleID == 41 || $roleID == 27) {
                $authUserOfficeId = Auth()->user()->office_id;
                $case_swap = MainRespondentNotification::
                    where('previous_office_id', $authUserOfficeId)
                    ->where('is_shown', 0)
                    ->get();

                $notificationCount = MainRespondentNotification::
                    where('previous_office_id', $authUserOfficeId)
                    ->where('is_shown', 0)
                    ->count();
            }

            if ($roleID == 29 || $roleID == 31) {
                // ===============Ministry Admin===============//
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    ->where('gov_case_registers.selected_main_min_id', '=', $officeInfo->office_id)
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();

                // $CaseHearingCount = DB::table('gov_case_hearings')
                //     ->distinct()
                //     ->join('gov_case_registers', 'gov_case_hearings.gov_case_id', '=', 'gov_case_registers.id')
                //     ->where('gov_case_registers.status', 1)
                //     ->where('gov_case_registers.selected_main_min_id','=', $officeInfo->office_id)
                //     ->select('gov_case_hearings.gov_case_id')
                //     ->get()
                //     ->count();

            } elseif ($roleID == 32 || $roleID == 41) {
                // ===============Ministry Admin===============//
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    ->where('gov_case_registers.selected_main_dept_id', '=', $officeInfo->office_id)
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();

            } elseif ($roleID == 34 || $roleID == 35 || $roleID == 36) {
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();

            } elseif ($roleID == 27 || $roleID == 28) {
                $CaseResultCount = DB::table('gov_case_registers')
                    ->where('status', '!=', 1)
                    ->get()
                    ->count();

                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')

                    ->where('gov_case_registers.status', 1)
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();

                foreach ($case_status as $row) {
                    $notification_count += $row->total_case;
                }
            } else {

                $rm_case_status = '';

            }
            if ($roleID != 14 && $roleID != 15 && $roleID != 33 && $roleID != 34) {

                //Message Notification --- start
                $NewMessagesCount = Message::select('id')
                    ->where('user_receiver', Auth::user()->id)
                    ->where('receiver_seen', 0)
                    ->where('msg_reqest', 0)
                    ->count();
                $msg_request_count = Message::orderby('id', 'DESC')
                // ->select('user_sender', 'user_receiver', 'msg_reqest')
                    ->Where('user_receiver', [Auth::user()->id])
                    ->Where('msg_reqest', 1)
                    ->groupby('user_sender')
                    ->count();
                $Ncount = $NewMessagesCount + $msg_request_count;

                $view->with([
                    'Ncount' => $Ncount,
                    'case_status' => $case_status,
                    'case_swap' => $case_swap,
                    'NewMessagesCount' => $NewMessagesCount,
                    'msg_request_count' => $msg_request_count,
                    'notificationCount' => $notificationCount,
                ]);
            }

        });

    }
    public function register()
    {
        //
    }

}
