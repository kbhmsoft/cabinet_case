<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Providers\AppServiceProvider;
use App\Models\User;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\RM_CaseRgister;
use App\Models\RM_CaseHearing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Message;

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

        view()->composer('messages.inc.search', function ($view)
        {
            $roleID = Auth::user()->role_id;
            $officeInfo = user_office_info();
            // Dorpdown
            $upazilas = NULL;
            $courts = DB::table('court')->select('id', 'court_name')->get();
            $divisions = DB::table('division')->select('id', 'division_name_bn')->get();
            $user_role = DB::table('roles')->select('id', 'name')->get();

            if($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13 || $roleID == 16){
                $courts = DB::table('court')->select('id', 'court_name')->where('district_id', $officeInfo->district_id)->orWhere('district_id', NULL)->get();
                $upazilas = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();

            }elseif($roleID == 9 || $roleID == 10 || $roleID == 11 || $roleID == 12){
                $courts = DB::table('court')->select('id', 'court_name')->where('district_id', $officeInfo->district_id)->orWhere('district_id', NULL)->get();
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

        view()->composer('layouts.cabinet.base.aside', function ($view)
        {
            $notification_count = 0;
            $case_status = [];
            $rm_case_status = [];
            $officeInfo = user_office_info();
            $roleID = Auth::user()->role_id;
            

            if( $roleID == 29 || $roleID == 31){
                // ===============Ministry Admin===============//
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    ->where('gov_case_registers.selected_main_min_id','=', $officeInfo->office_id)
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();
                

                $CaseHearingCount = DB::table('gov_case_hearings')
                    ->distinct()
                    ->join('gov_case_registers', 'gov_case_hearings.gov_case_id', '=', 'gov_case_registers.id')
                    ->where('gov_case_registers.status', 1)
                    ->where('gov_case_registers.selected_main_min_id','=', $officeInfo->office_id)
                    ->select('gov_case_hearings.gov_case_id')
                    ->get()
                    ->count();

            }elseif( $roleID == 32 || $roleID == 33){
                // ===============Ministry Admin===============//
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    ->where('gov_case_registers.selected_main_dept_id','=', $officeInfo->office_id)
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();
                

                $CaseHearingCount = DB::table('gov_case_hearings')
                    ->distinct()
                    ->join('gov_case_registers', 'gov_case_hearings.gov_case_id', '=', 'gov_case_registers.id')
                    ->where('gov_case_registers.status', 1)
                    ->where('gov_case_registers.selected_main_dept_id','=', $officeInfo->office_id)
                    ->select('gov_case_hearings.gov_case_id')
                    ->get()
                    ->count();

            } elseif($roleID == 34 || $roleID == 35 || $roleID == 36) {
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();

                $CaseHearingCount = DB::table('gov_case_hearings')
                    ->distinct()
                    ->join('gov_case_registers', 'gov_case_hearings.gov_case_id', '=', 'gov_case_registers.id')
                    ->where('gov_case_registers.status', 1)
                    ->select('gov_case_hearings.gov_case_id')
                    ->get()
                    ->count();

            }elseif($roleID == 27 || $roleID == 28) {
                $CaseResultCount = DB::table('gov_case_registers')
                    ->where('status', '!=', 1)
                    ->get()
                    ->count();

                $CaseHearingCount = DB::table('gov_case_hearings')
                    ->distinct()
                    ->join('gov_case_registers', 'gov_case_hearings.gov_case_id', '=', 'gov_case_registers.id')
                    ->where('gov_case_registers.status', 1)
                    ->where('gov_case_registers.selected_main_min_id','=', $officeInfo->office_id)
                    ->select('gov_case_hearings.gov_case_id')
                    ->get()
                    ->count();
                $case_status = DB::table('gov_case_registers')
                    ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
                    ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
                    ->groupBy('gov_case_registers.case_status_id')
                    // ->where('gov_case_registers.selected_main_min_id','=', $officeInfo->office_id)
                    ->where('gov_case_registers.status', 1)
                    ->where('gov_case_registers.action_user_role_id', $roleID)
                    ->get();
                // dd($dfsdf);

                $notification_count = $CaseResultCount + $CaseHearingCount ;

                foreach ($case_status as $row){
                     $notification_count += $row->total_case;
                }
            } else {
                //for role id : 5,6,7,8,13
                $case_status = DB::table('case_register')
                    ->select('case_register.cs_id', 'case_status.status_name', DB::raw('COUNT(case_register.id) as total_case'))
                    ->leftJoin('case_status', 'case_register.cs_id', '=', 'case_status.id')
                    ->groupBy('case_register.cs_id')
                    ->where('case_register.district_id','=', $officeInfo->district_id)
                    ->where('case_register.action_user_group_id', $roleID)
                    ->get();
                $rm_case_status = '';

                // dd($rm_case_status);
            }

           if( $roleID != 1 && $roleID != 2 && $roleID != 14 && $roleID != 28 && $roleID != 27){
                foreach ($case_status as $row){
                     $notification_count += $row->total_case;
                }
                     $notification_count += $CaseHearingCount;

                $view->with([
                    'notification_count' => $notification_count,
                    'case_status' => $case_status,
                    'CaseHearingCount' => $CaseHearingCount,
                ]);

            } elseif($roleID == 27 || $roleID == 28) {
                $view->with([
                    'notification_count' => $notification_count,
                    'CaseHearingCount' => $CaseHearingCount,
                    'CaseResultCount' => $CaseResultCount,
                    'case_status' => $case_status,
                ]);
            }
            //

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
                'NewMessagesCount' => $NewMessagesCount,
                'msg_request_count' => $msg_request_count,
            ]);
            //Message Notification  --- End



        });


    }
    public function register()
    {
        //
    }

}
