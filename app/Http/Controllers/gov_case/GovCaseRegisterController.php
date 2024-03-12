<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Court;
use App\Models\gov_case\AppealAdalat;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\gov_case\GovCaseDivisionCategoryType;
use App\Models\gov_case\GovCaseLog;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\HighcourtAdalat;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use App\Repositories\gov_case\AppealGovCaseRegisterRepository;
use App\Repositories\gov_case\AttachmentRepository;
use App\Repositories\gov_case\GovCaseBadiBibadiRepository;
use App\Repositories\gov_case\GovCaseLogRepository;
use App\Repositories\gov_case\GovCaseRegisterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GovCaseRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create_new_case', ['only' => ['create']]);
        $this->middleware('permission:appeal_division', ['only' => ['appellate_division_case']]);
        $this->middleware('permission:highcourt_case_update', ['only' => ['edit']]);
    }

    public function index()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC');

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('ministry_id', $finalOfficeIds)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        // echo "<pre>"; print_r($data['cases']); exit();

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';
        return view('gov_case.case_register.index')->with($data);
    }

    public function high_court_case()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 33) {
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    // for all highcourt case attorney
    public function attorney_high_court_case()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $authUserId = Auth()->user()->id;

        $query = GovCaseRegister::where('concern_user_id', $authUserId)->orderby('id', 'DESC')
            ->where('deleted_at', '=', null);

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট মামলার তালিকা';

        // return $data;

        return view('gov_case.case_register.attorney_highcourt_total')->with($data);
    }

    // attorney running case

    public function attorney_high_court_running_case()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $authUserId = Auth()->user()->id;

        $query = GovCaseRegister::where('concern_user_id', $authUserId)
            ->orderby('id', 'DESC')
            ->where('is_final_order', 0)
            ->where('deleted_at', '=', null);

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট চলমান মামলার তালিকা';

        return view('gov_case.case_register.attorney_highcourt_running')->with($data);
    }

    // attorney complete case

    public function attorney_high_court_complete_case()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $authUserId = Auth()->user()->id;

        $query = GovCaseRegister::where('concern_user_id', $authUserId)
            ->orderby('id', 'DESC')
            ->where('is_final_order', 1)
            ->where('deleted_at', '=', null);

        // $query = GovCaseRegister::orderby('id', 'DESC')
        // ->where('is_final_order', 0)->where('deleted_at', '=', null);

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট নিস্পত্তিকৃত মামলার তালিকা';
        // return $data;
        return view('gov_case.case_register.attorney_highcourt_complete')->with($data);
    }

    public function highcourtMostImportantCase()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')->where('deleted_at', '=', null)
            ->where('most_important', 1);

        if ($roleID == 32 || $roleID == 33) {
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

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট অতি গুরুত্বপূর্ণ মামলার তালিকা';

        // return $data;

        return view('gov_case.case_register.most_important_highcourt')->with($data);
    }

    public function highcourtAppealMostImportantCase()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')->where('deleted_at', '=', null)
            ->where('most_important', 1);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট অতি গুরুত্বপূর্ণ মামলার তালিকা';

        // For Appeal

        $queryAppeal = AppealGovCaseRegister::orderby('id', 'DESC')
            ->where('deleted_at', '=', null)->where('most_important', 1);

        $data['offices'] = DB::table('gov_case_office')->get();

        if ($roleID == 32 || $roleID == 33) {
            $queryAppeal->where('appeal_office_id', $officeID);
        }

        if ($roleID == 29 || $roleID == 31) {
            $queryAppeal->where('appeal_office_id', $officeID);
        }

        if (!empty($_GET['case_category_id'])) {
            $queryAppeal->where('appeal_gov_case_register.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $queryAppeal->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $queryAppeal->where('appeal_gov_case_register.case_no', '=', $_GET['case_no']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $queryAppeal->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $queryAppeal->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }
        $data['appealCases'] = $queryAppeal->with('highcourtCaseDetail:id,case_no,subject_matter', 'badis:id,gov_case_id,name')
            ->get();

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')
            ->where('gov_case_division_id', 2)->get();

        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();
        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title2'] = 'আপিল বিভাগে সরকারি স্বার্থসংশ্লিষ্ট অতি গুরুত্বপূর্ণ মামলার তালিকা';

        return view('gov_case.case_register.most_important_highcourt_appeal')->with($data);
    }

    public function highcourtAppealImportantCase()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')->where('deleted_at', '=', null)
            ->where('important', 1);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট গুরুত্বপূর্ণ মামলার তালিকা';

        // For Appeal

        $queryAppeal = AppealGovCaseRegister::orderby('id', 'DESC')
            ->where('deleted_at', '=', null)->where('important', 1);

        $data['offices'] = DB::table('gov_case_office')->get();

        if ($roleID == 32 || $roleID == 33) {
            $queryAppeal->where('appeal_office_id', $officeID);
        }

        if ($roleID == 29 || $roleID == 31) {
            $queryAppeal->where('appeal_office_id', $officeID);
        }

        if (!empty($_GET['case_category_id'])) {
            $queryAppeal->where('appeal_gov_case_register.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $queryAppeal->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $queryAppeal->where('appeal_gov_case_register.case_no', '=', $_GET['case_no']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $queryAppeal->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $queryAppeal->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }
        $data['appealCases'] = $queryAppeal->with('highcourtCaseDetail:id,case_no,subject_matter', 'badis:id,gov_case_id,name')
            ->get();

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')
            ->where('gov_case_division_id', 2)->get();

        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();
        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title2'] = 'আপিল বিভাগে সরকারি স্বার্থসংশ্লিষ্ট গুরুত্বপূর্ণ মামলার তালিকা';

        return view('gov_case.case_register.important_highcourt_appeal')->with($data);
    }

    public function highcourtMostImportantSave(Request $request)
    {
        $rowId = $request->input('rowId');
        $mostImportant = $request->input('most_important');

        try {
            $appeal = GovCaseRegister::findOrFail($rowId);
            $appeal->most_important = $mostImportant;
            $appeal->save();

            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    public function highcourtImportantSave(Request $request)
    {
        $rowId = $request->input('rowId');
        $important = $request->input('important');

        try {
            $caseData = GovCaseRegister::findOrFail($rowId);
            $caseData->important = $important;
            $caseData->save();

            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    public function totalHighcourt()
    {
        session()->forget('currentUrlPath');
        $data['ministry'] = DB::table('gov_case_office')
            ->whereIn('gov_case_office.level', [1, 3])
            ->paginate(10);
        // $ministry = DB::table('gov_case_office')
        //     ->select(
        //         'gov_case_office.id',
        //         'gov_case_office.office_name_bn',
        //         'gov_case_office.office_name_en',
        //         DB::raw('SUM(CASE WHEN gcb.is_main_bibadi = "1" AND gcr.deleted_at IS NULL THEN 1 ELSE 0 END) AS highcourt_running_case'),
        //     )
        //     ->leftJoin('gov_case_bibadis as gcb', 'gov_case_office.id', '=', 'gcb.respondent_id')
        //     ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
        //     ->whereIn('gov_case_office.level', [1, 3]);

        //     $data['ministry'] = DB::table('gov_case_office')
        //     ->whereIn('gov_case_office.level', [1, 3])
        //     ->paginate(10);

        $arrayd = [];
        foreach ($data['ministry'] as $key => $val) {
            $childOfficeIds = [];

            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id')
                ->where('parent', $val->id)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->id;
            }

            $finalOfficeIds = [];

            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $val->id;
            } else {
                $finalOfficeIds[] = $val->id;
                $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            }

            $val->highcourt_running_case = $this->countHighCourtRunningCase($finalOfficeIds)->count();
            $val->appeal_running_case = $this->countAppealRunningCase($finalOfficeIds)->count();
            $val->against_gov = $this->countHighCourtAgainstGovCase($finalOfficeIds)->count();
            $val->result_sending_count = $this->countHighCourtSolicitorPendingCase($finalOfficeIds)->count();
            $val->against_postponed_count = $this->countHighCourtAppealPospondOrderPendingCase($finalOfficeIds)->count();
            array_push($arrayd, $val);
        }
// return $arrayd;
        // $data['ministry'] = $ministry->groupBy('gov_case_office.id')
        //     ->paginate(10);
        // return $data['ministry'];

        $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();

        $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();

        $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];

        $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();

        $data['running_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)
            ->where('is_final_order', 0)->count();

        $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

        $data['total_appeal_case'] = AppealGovCaseRegister::count();

        $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)->count();

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

        $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)
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
        $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
        $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

        // return $data;

        // View
        $data['page_title'] = 'হাইকোর্ট বিভাগে মোট মামলা';

        return view('dashboard.cabinet.cabinet_admin_highcourt_total_case')->with($data);
    }

    public function totalHighcourtRunning()
    {
        session()->forget('currentUrlPath');
        $data['ministry'] = DB::table('gov_case_office')
            ->whereIn('gov_case_office.level', [1, 3])
            ->paginate(10);

        $arrayd = [];
        foreach ($data['ministry'] as $key => $val) {
            $childOfficeIds = [];

            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id')
                ->where('parent', $val->id)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->id;
            }

            $finalOfficeIds = [];

            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $val->id;
            } else {
                $finalOfficeIds[] = $val->id;
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
        $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        // $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        // return $data['sent_to_solicitor_case'];
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();

        // View
        $data['page_title'] = 'হাইকোর্ট বিভাগে চলমান মামলা';

        return view('dashboard.cabinet.cabinet_admin_highcourt_running_total_case')->with($data);
    }

    public function appealCaseAgainstGovt()
    {
        session()->forget('currentUrlPath');

        $ministry = DB::table('gov_case_office')
            ->select(
                'gov_case_office.id',
                'gov_case_office.office_name_bn',
                'gov_case_office.office_name_en',
                DB::raw('SUM(CASE WHEN gcb.is_main_bibadi = "1" AND gcr.result = "2" AND gcr.is_appeal = "2" AND  gcr.deleted_at IS NULL THEN 1 ELSE 0 END) AS against_gov_case'),
            )
            ->leftJoin('gov_case_bibadis as gcb', 'gov_case_office.id', '=', 'gcb.respondent_id')
            ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
            ->whereIn('gov_case_office.level', [1, 3]);

        $data['ministry'] = $ministry->groupBy('gov_case_office.id')
            ->paginate(10);

        $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];
        $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();

        $data['running_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)
            ->where('is_final_order', 0)->count();

        $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

        $data['total_appeal_case'] = AppealGovCaseRegister::count();
        $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)->count();
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

        $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)
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
        $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
        $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

        // return $data;

        // View
        $data['page_title'] = 'সরকারের বিপক্ষে আপিলের জন্য পেন্ডিং';

        return view('dashboard.cabinet.cabinet_admin_appeal_against_govt_case')->with($data);
    }

    public function againstCasePostponedOrder()
    {
        session()->forget('currentUrlPath');

        $data['ministry'] = DB::table('gov_case_office')
            ->whereIn('gov_case_office.level', [1, 3])
            ->paginate(10);

        $arrayd = [];
        foreach ($data['ministry'] as $key => $val) {
            $childOfficeIds = [];

            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id')
                ->where('parent', $val->id)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->id;
            }

            $finalOfficeIds = [];

            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $val->id;
            } else {
                $finalOfficeIds[] = $val->id;
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
        $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        // $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        // return $data['sent_to_solicitor_case'];
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
        $data['page_title'] = 'স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলা';

        return view('dashboard.cabinet.cabinet_admin_postponed_interim_order_govt_case')->with($data);
    }

    public function sentToSolicitorCase()
    {
        session()->forget('currentUrlPath');

        $data['ministry'] = DB::table('gov_case_office')
            ->whereIn('gov_case_office.level', [1, 3])
            ->paginate(10);

        $arrayd = [];
        foreach ($data['ministry'] as $key => $val) {
            $childOfficeIds = [];

            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id')
                ->where('parent', $val->id)->get();

            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->id;
            }

            $finalOfficeIds = [];

            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $val->id;
            } else {
                $finalOfficeIds[] = $val->id;
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
        $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        // $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        // return $data['sent_to_solicitor_case'];
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
        $data['page_title'] = 'জবাব পেন্ডিং';

        return view('dashboard.cabinet.cabinet_admin_appeal_result_sending_date')->with($data);
    }

    // public function ministryWiseSentToSolicitorCase()
    // {
    //     session()->forget('currentUrlPath');

    //     $ministry = DB::table('gov_case_office')
    //         ->select(
    //             'gov_case_office.id',
    //             'gov_case_office.office_name_bn',
    //             'gov_case_office.office_name_en',
    //             DB::raw('SUM(CASE WHEN gcb.is_main_bibadi = "1" AND gcr.result_sending_date IS NULL  AND  gcr.deleted_at IS NULL THEN 1 ELSE 0 END) AS result_sending_date'),
    //         )
    //         ->leftJoin('gov_case_bibadis as gcb', 'gov_case_office.id', '=', 'gcb.respondent_id')
    //         ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
    //         ->whereIn('gov_case_office.level', [1, 3]);

    //     $data['ministry'] = $ministry->groupBy('gov_case_office.id')
    //         ->paginate(10);

    //     $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();
    //     $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
    //     $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];
    //     $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();

    //     $data['running_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)
    //         ->where('is_final_order', 0)->count();

    //     $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

    //     $data['total_appeal_case'] = AppealGovCaseRegister::count();
    //     $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)->count();
    //     $data['final_appeal_case'] = AppealGovCaseRegister::where('is_final_order', 1)->count();

    //     $data['appealAgainstGovt'] = GovCaseRegister::where('deleted_at', '=', null)->where('in_favour_govt', 2)
    //         ->where('is_appeal', 0)->count();

    //     $data['not_against_gov'] = GovCaseRegister::where('in_favour_govt', 1)->count();

    //     $data['sent_to_solicitor_case'] = GovCaseRegister::where('deleted_at', '=', null)->whereNull('result_sending_date')->count();

    //     $data['against_postpond_order'] = GovCaseRegister::where('deleted_at', '=', null)->whereNull('appeal_against_postpond_interim_order')->count();

    //     $data['five_years_running_highcourt_case'] = GovCaseRegister::where('deleted_at', '=', null)->where('is_final_order', 0)
    //         ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
    //         ->orderBy('id', 'DESC')
    //         ->count();

    //     $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)
    //         ->where('deleted_at', '=', null)
    //         ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
    //         ->orderBy('id', 'DESC')
    //         ->count();

    //     $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
    //     $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();

    //     $data['total_case'] = $data['total_highcourt'] + $data['total_appeal'];

    //     $data['total_office'] = DB::table('office')->whereNotIn('id', [1, 2, 7])->count();
    //     $data['total_ministry'] = DB::table('gov_case_office')->where('level', 1)->count();
    //     $data['total_division'] = DB::table('gov_case_office')->where('level', 3)->count();
    //     $data['total_district'] = DB::table('gov_case_office')->where('level', 4)->count();
    //     $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

    //     $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

    //     // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
    //     $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
    //     $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
    //     $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
    //     $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

    //     $data['page_title'] = 'জবাব পেন্ডিং';

    //     return view('dashboard.cabinet.cabinet_admin_appeal_result_sending_date')->with($data);
    // }

    public function totalHighcourtComplete()
    {
        session()->forget('currentUrlPath');

        $ministry = DB::table('gov_case_office')
            ->select(
                'gov_case_office.id',
                'gov_case_office.office_name_bn',
                'gov_case_office.office_name_en',
                DB::raw('SUM(CASE WHEN gcb.is_main_bibadi = "1" AND gcr.is_final_order = "1" AND gcr.deleted_at IS NULL THEN 1 ELSE 0 END) AS highcourt_complete_case'),
            )
            ->leftJoin('gov_case_bibadis as gcb', 'gov_case_office.id', '=', 'gcb.respondent_id')
            ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
            ->whereIn('gov_case_office.level', [1, 3]);

        $data['ministry'] = $ministry->groupBy('gov_case_office.id')
            ->paginate(10);

        $data['total_appeal'] = AppealGovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_highcourt'] = GovCaseRegister::where('deleted_at', '=', null)->count();
        $data['total_case'] = $data['total_appeal'] + $data['total_highcourt'];
        $data['total_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)->count();

        $data['running_high_court_case'] = GovCaseRegister::where('deleted_at', '=', null)
            ->where('is_final_order', 0)->count();

        $data['final_high_court_case'] = GovCaseRegister::where('is_final_order', 1)->where('deleted_at', '=', null)->count();

        $data['total_appeal_case'] = AppealGovCaseRegister::count();
        $data['running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)->count();
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

        $data['five_years_running_appeal_case'] = AppealGovCaseRegister::where('is_final_order', null)
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
        $data['total_doptor'] = DB::table('gov_case_office')->where('level', 2)->count();

        $data['cases'] = DB::table('gov_case_registers')->select('gov_case_registers.*')->get();

        // $data['gov_case_status'] = GovCaseRegisterRepository::caseStatusByRoleId($roleID);
        $data['against_gov_case'] = GovCaseRegisterRepository::againestGovCases();
        $data['sent_to_solicitor_case'] = GovCaseRegisterRepository::sendToSolicotorCases();
        $data['sent_to_ag_from_sol_case'] = GovCaseRegisterRepository::sendToAgFromSolCases();
        $data['against_postpond_order'] = GovCaseRegisterRepository::stepNotTakenAgainstPostpondOrderCases();

        //   return $data['ministry'];

        // View
        $data['page_title'] = 'হাইকোর্ট বিভাগে নিস্পত্তিকৃত মামলা';

        return view('dashboard.cabinet.cabinet_admin_highcourt_complete_total_case')->with($data);
    }
    public function appealAgainstGovt()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::where('deleted_at', '=', null)
            ->where('in_favour_govt', 2)->where('is_appeal', 0);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'সরকারের বিপক্ষে আপিলের জন্য
        পেন্ডিং মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function againstPostponedOrder()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::where('deleted_at', '=', null)->whereNull('appeal_against_postpond_interim_order');

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'স্থগিতাদেশ অন্তর্বর্তীকালীন
        পেন্ডিং মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function sentToSolicitor()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::where('deleted_at', '=', null)->whereNull('result_sending_date');

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'জবাব পেন্ডিং মামলার তালিকা';

        // return $data;

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function fiveYearsRunningHighCourt()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::orderby('id', 'DESC')->where('case_division_id', 2)->where('deleted_at', '=', null)
            ->where('is_final_order', 0)
            ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString());
        // $data['five_years_running_highcourt_case'] = GovCaseRegister::where('deleted_at', '=', null)->where('is_final_order', 0)
        // ->whereDate('updated_at', '<=', now()->subYears(5)->toDateString())
        // ->orderBy('id', 'DESC')
        // ->count();
        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে ৫ বছরের অধিককাল চলমান মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function high_court_running_case()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')
            ->where('is_final_order', 0)->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট চলমান মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }
    public function high_court_complete_case()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeIds = [];

        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->where('is_final_order', 1)->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট নিস্পত্তিকৃত মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    //     public function appellate_division_complete_case()
    //     {

    //         session()->forget('currentUrlPath');

    //         $officeInfo = user_office_info();
    //         $roleID = userInfo()->role_id;
    //         $officeID = userInfo()->office_id;

    //         $query = GovCaseRegister::with('mainBibadis')->orderby('id', 'DESC')->where('case_division_id', 1)->where('is_final_order', 1);

    //         if ($roleID == 32 || $roleID == 33) {
    //             $query->whereHas('bibadis',
    //                 function ($query) use ($officeID) {
    //                     $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
    //                 }
    //             );
    //         }

    //         if ($roleID == 29 || $roleID == 31) {
    //             $query->whereHas('bibadis',
    //                 function ($query) use ($officeID) {
    //                     $query->where('ministry_id', $officeID)->where('is_main_bibadi', 1);
    //                 }
    //             );
    //         }

    //         if (!empty($_GET['case_category_id'])) {
    //             $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
    //         }

    //         if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
    //             // dd(1);
    //             $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
    //             $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
    //             $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
    //         }

    //         if (!empty($_GET['case_no'])) {
    //             $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
    //         }
    //         if (!empty($_GET['division'])) {
    //             $query->where('gov_case_registers.division_id', '=', $_GET['division']);
    //         }
    //         if (!empty($_GET['district'])) {
    //             $query->where('gov_case_registers.district_id', '=', $_GET['district']);
    //         }
    //         if (!empty($_GET['upazila'])) {
    //             $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
    //         }
    //         if ($roleID == 5 || $roleID == 7) {
    //             $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
    //         } elseif ($roleID == 9 || $roleID == 21) {
    //             $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
    //         }
    //         $data['cases'] = $query->paginate(10);
    //         $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
    //         $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')
    //         ->where('gov_case_division_id', 2)->get();
    // // return $data['division_categories'];
    //         // $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();

    //         $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

    //         $data['page_title'] = 'আপিল বিভাগে সরকারি স্বার্থসংশ্লিষ্ট মামলার তালিকা';
    //         // return $data;
    //         return view('gov_case.case_register.appealcourt')->with($data);
    //     }

    //============Running Case list============//
    public function running_case()
    {
        // dd(user_office_info());
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::orderby('id', 'DESC')->whereIn('gov_case_registers.status', [1, 2]);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('ministry_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }

    //============Appeal Case list============//
    public function appeal_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::orderby('id', 'DESC')->where('case_division_id', 1)->where('status', '!=', 3);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('ministry_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }

    //============Complete Case list============/
    public function complete_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::orderby('id', 'DESC')->where('status', 3);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('ministry_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }

    //============Not Against Govt Case list============/
    public function govt_not_against_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::orderby('id', 'DESC')->where('gov_case_registers.in_favour_govt', 1);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('ministry_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }

    //============Against Govt Case list============/
    public function govt_against_case()
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $childOfficeIds = [];

        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        // return $finalOfficeIds;

        $query = GovCaseRegister::orderby('id', 'DESC')->where('in_favour_govt', 2)->where('is_appeal', 0);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($finalOfficeIds) {
                    $query->whereIn('respondent_id', $finalOfficeIds)->where('is_main_bibadi', 1);
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

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.index')->with($data);
    }

    //============Division Wise Case list============/
    public function division_wise($id)
    {
        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $query = GovCaseRegister::orderby('id', 'DESC')->where('gov_case_registers.case_division_id', $id)->where('status', '!=', 3);

        if ($roleID == 32 || $roleID == 33) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if ($roleID == 29 || $roleID == 31) {
            $query->whereHas(
                'bibadis',
                function ($query) use ($officeID) {
                    $query->where('ministry_id', $officeID)->where('is_main_bibadi', 1);
                }
            );
        }

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi   ', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['division_wise_id'] = $id;
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'মামলা এন্ট্রি রেজিষ্টারের তালিকা';

        return view('gov_case.case_register.division_wise_list')->with($data);
    }

    //============Ministry Wise Case list============/
    public function ministry_wise_list($id)
    {
        // dd($id);

        $query = DB::table('gov_case_office')
            ->select(
                'gov_case_office.id',
                'gov_case_office.office_name_bn',
                'gov_case_office.office_name_en',
                \DB::raw('SUM(CASE WHEN gcr.status != "3" THEN 1 ELSE 0 END) AS running_case'),
                \DB::raw('SUM(CASE WHEN gcr.status = "3" THEN 1 ELSE 0 END) AS completed_case'),
                \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "0" THEN 1 ELSE 0 END) AS against_gov'),
                \DB::raw('SUM(CASE WHEN gcr.in_favour_govt = "1" THEN 1 ELSE 0 END) AS not_against_gov'),
            )
            ->leftJoin('gov_case_bibadis as gcb', 'gov_case_office.id', '=', 'gcb.respondent_id')
            ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
            ->where('gov_case_office.parent', $id);

        $data['ministry_wise'] = $query->groupBy('gov_case_office.id')
            ->groupBy('gcb.respondent_id')
            ->orderBy('gov_case_office.id', 'asc')
            ->paginate(10);

        // $data['ministry_wise'] = $query;
        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['ministry'] = DB::table('gov_case_office')->select('id', 'office_name_bn')->where('id', $id)->first();
        $data['page_title'] = $data['ministry']->office_name_bn . ' এর মামলার তালিকা';
        // return $data;
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
        ->leftJoin('gov_case_bibadis as gcb', 'office.id', '=', 'gcb.respondent_id')
        ->leftJoin('gov_case_registers as gcr', 'gcb.gov_case_id', '=', 'gcr.id')
        ->where('office.id', $id)->groupBy('office.id')->groupBy('gcb.respondent_id')
        ->orderBy('office.id', 'asc')->paginate(10);*/

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;

        $query = GovCaseRegister::select('gov_case_registers.*')->orderby('gov_case_registers.id', 'DESC');
        $query->leftJoin('gov_case_bibadis as gcb', 'gov_case_registers.id', '=', 'gcb.gov_case_id');
        $query->where('gcb.respondent_id', $id);

        if (!empty($_GET['case_division_id'])) {
            $query->where('gov_case_registers.case_division_id', '=', $_GET['case_division_id']);
        }

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('gov_case_registers.case_date', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }

        if ($roleID == 5 || $roleID == 7) {
            $query->where('gov_case_registers.district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('gov_case_registers.upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        // Dorpdown
        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $ministry = DB::table('office')->select('office.id', 'op.office_name_bn', 'office.office_name_bn as ministry_name')
            ->leftJoin('office as op', 'office.id', '=', 'op.parent')
            ->where('op.id', $id)->first();

        $data['ministry'] = $ministry;
        $data['roleID'] = $roleID;
        $data['page_title'] = $data['ministry']->office_name_bn . ' এর মামলার তালিকা';
        return view('gov_case.case_register.department_wise_list')->with($data);
    }

    public function highcourt_create()
    {
        session()->forget('currentUrlPath');
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $data['ministrys'] = GovCaseOffice::get();

        $data['highCourtAdalat'] = HighcourtAdalat::get();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['page_title'] = 'নতুন/চলমান হাইকোর্ট মামলা এন্ট্রি ';

        return view('gov_case.case_register.create_new')->with($data);
    }

    public function appellateDivision_create()
    {
        session()->forget('currentUrlPath');

        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        // $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['ministrys'] = GovCaseOffice::get();
        // $data['ministrys'] = DB::table('gov_case_office')->get();
        $data['caseRegister'] = GovCaseRegister::all();
        // return $data['caseRegister'];

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }

        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategoryHighcourt'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 1)->get();
        // return $data['GovCaseDivisionCategory'];
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['appealCourtAdalat'] = AppealAdalat::get();

        $data['page_title'] = 'নতুন/চলমান আপিল মামলা এন্ট্রি ';

        return view('gov_case.appeal_case_register.create_new_appeal')->with($data);
    }

    public function appellateDivision_old_case_create()
    {
        session()->forget('currentUrlPath');

        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $data['ministrys'] = GovCaseOffice::get();
        $data['caseRegister'] = GovCaseRegister::all();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }

        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategoryHighcourt'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 1)->get();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['appealCourtAdalat'] = AppealAdalat::get();

        $data['page_title'] = 'নিস্পত্তিকৃত আপিল মামলা এন্ট্রি ';

        return view('gov_case.appeal_case_register.create_old_appeal_case')->with($data);
    }

    public function get_details(Request $request)
    {
        //$query =  DB::table('gov_case_registers')->where('id',$request->case_id)->first();
        $query = GovCaseRegister::where('id', $request->case_id)->first();
        return $query;
    }

    public function store(Request $request)
    {
        $caseId = $request->caseId;
        $request->validate(
            [
                'case_no' => 'required|unique:gov_case_registers,case_no,' . $caseId,
            ],
            [
                'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',
            ]
        );
        try {
            $caseId = GovCaseRegisterRepository::storeGovCase($request);
            GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);
            GovCaseLogRepository::storeGovCaseLog($caseId);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
            }
            if ($request->reply_file_type && $_FILES["reply_file_name"]['name']) {
                AttachmentRepository::storeReplyAttachment('gov_case', $caseId, $request);
            }
            if ($request->suspension_file_type && $_FILES["suspension_file_name"]['name']) {
                AttachmentRepository::storeSuspentionOrderAttachment('gov_case', $caseId, $request);
            }
            if ($request->final_order_file_type && $_FILES["final_order_file_name"]['name']) {
                AttachmentRepository::storeFinalOrderAttachment('gov_case', $caseId, $request);
            }
            if ($request->contempt_file_type && $_FILES["contempt_file_name"]['name']) {
                AttachmentRepository::storeContemptAttachment('gov_case', $caseId, $request);
            }

            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);

            $cs_activity_data['case_register_id'] = $caseId;
            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'নতুন মামলা রেজিস্ট্রেশন করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'মামলার তথ্য হালনাগাদ করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            // dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

        // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    // public function appealStore(Request $request)
    // {
    //     $caseId = $request->caseId;
    //     $request->validate([
    //         'case_no' => 'required|unique:appeal_gov_case_register,case_no,' . $caseId,
    //     ],
    //         [
    //             'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',
    //         ]);
    //     try {
    //         $caseId = AppealGovCaseRegisterRepository::storeAppeal($request);
    //         // GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
    //         // GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);
    //         GovCaseLogRepository::storeGovCaseLog($caseId);
    //         if ($request->file_type && $_FILES["file_name"]['name']) {
    //             AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
    //         }
    //         if ($request->reply_file_type && $_FILES["reply_file_name"]['name']) {
    //             AttachmentRepository::storeReplyAttachment('gov_case', $caseId, $request);
    //         }
    //         if ($request->suspension_file_type && $_FILES["suspension_file_name"]['name']) {
    //             AttachmentRepository::storeSuspentionOrderAttachment('gov_case', $caseId, $request);
    //         }
    //         if ($request->final_order_file_type && $_FILES["final_order_file_name"]['name']) {
    //             AttachmentRepository::storeFinalOrderAttachment('gov_case', $caseId, $request);
    //         }
    //         if ($request->contempt_file_type && $_FILES["contempt_file_name"]['name']) {
    //             AttachmentRepository::storeContemptAttachment('gov_case', $caseId, $request);
    //         }

    //         //========= Gov Case Activity Log -  start ============
    //         $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
    //         $caseRegisterData = array_merge($caseRegister, [
    //             'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
    //             'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
    //             'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
    //             'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
    //         ]);

    //         $cs_activity_data['case_register_id'] = $caseId;
    //         if ($request->formType != 'edit') {
    //             $cs_activity_data['activity_type'] = 'create';
    //             $cs_activity_data['message'] = 'নতুন মামলা রেজিস্ট্রেশন করা হয়েছে';
    //         } else {
    //             $cs_activity_data['activity_type'] = 'update';
    //             $cs_activity_data['message'] = 'মামলার তথ্য হালনাগাদ করা হয়েছে';
    //         }
    //         $cs_activity_data['old_data'] = null;
    //         $cs_activity_data['new_data'] = json_encode($caseRegisterData);
    //         gov_case_activity_logs($cs_activity_data);
    //         // ========= Gov Case Activity Log  End ==========

    //     } catch (\Exception $e) {
    //         // dd($e);
    //         $flag = 'false';
    //         return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
    //     }
    //     return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

    //     // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    // }

    public function storeGeneralInfo(Request $request)
    {
        try {
            $caseId = $request->caseId;
        //    dd($caseId);
            // DB::beginTransaction();

            $request->validate([
                'case_no' => 'required|unique:gov_case_registers,case_no,' . $caseId,
            ], [
                'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',
            ]);

            $caseId = GovCaseRegisterRepository::storeGeneralInfo($request);

            GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);

            if ($request->file_type && $_FILES["file_name"]['name']) {
         
                AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
            }
            if ($request->reply_file_type && $_FILES["reply_file_name"]['name']) {
                AttachmentRepository::storeReplyAttachment('gov_case', $caseId, $request);
            }
            if ($request->suspension_file_type && $_FILES["suspension_file_name"]['name']) {
                AttachmentRepository::storeSuspentionOrderAttachment('gov_case', $caseId, $request);
            }
            if ($request->final_order_file_type && $_FILES["final_order_file_name"]['name']) {
                AttachmentRepository::storeFinalOrderAttachment('gov_case', $caseId, $request);
            }
            if ($request->contempt_file_type && $_FILES["contempt_file_name"]['name']) {
                AttachmentRepository::storeContemptAttachment('gov_case', $caseId, $request);
            }
            // DB::commit();
            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();

            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);

            $cs_activity_data['case_register_id'] = $caseId;

            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'নতুন মামলা রেজিস্ট্রেশন করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'মামলার তথ্য হালনাগাদ করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            // DB::rollBack();
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);
    }

    public function caseGeneralInfoForEdit(Request $request)
    {
        // dd($request->all());
        $caseNo = $request->case_no;
        $previousMainRespondent = $request->input('previous_main_respondent');
        $mainRespondent = $request->input('main_respondent');

        $previousMainRespondent = $previousMainRespondent[0];
        $newMainRespondent = $mainRespondent[0];

        $exists = GovCaseRegister::where('case_no', $caseNo)->where('deleted_at', null)->exists();
        $caseId = GovCaseRegister::where('case_no', $caseNo)->where('deleted_at', null)->first();
        $id = $caseId->id;

        if ($previousMainRespondent != $newMainRespondent && $exists) {
            DB::table('main_respondent_notifications')->insert([
                'gov_case_id' => $id,
                'case_no' => $request->case_no,
                'previous_office_id' => $previousMainRespondent,
                'new_office_id' => $newMainRespondent,
                'is_shown' => 0,
            ]);
        }

        // $request->validate(
        //     [
        //         'case_no' => 'required|unique:gov_case_registers,case_no,' . $caseId,
        //     ],
        //     [
        //         'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',
        //     ]
        // );

        try {

            $caseId = GovCaseRegisterRepository::storeGeneralInfo($request);
            GovCaseBadiBibadiRepository::storeBadi($request, $id);
            GovCaseBadiBibadiRepository::storeBibadiForChangingMainRespondent($request, $id);

            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeAttachment('gov_case', $id, $request);
            }
            if ($request->reply_file_type && $_FILES["reply_file_name"]['name']) {
                AttachmentRepository::storeReplyAttachment('gov_case', $id, $request);
            }
            if ($request->suspension_file_type && $_FILES["suspension_file_name"]['name']) {
                AttachmentRepository::storeSuspentionOrderAttachment('gov_case', $id, $request);
            }
            if ($request->final_order_file_type && $_FILES["final_order_file_name"]['name']) {
                AttachmentRepository::storeFinalOrderAttachment('gov_case', $id, $request);
            }
            if ($request->contempt_file_type && $_FILES["contempt_file_name"]['name']) {
                AttachmentRepository::storeContemptAttachment('gov_case', $id, $request);
            }

            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($id)->toArray();

            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $id)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $id)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $id)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $id)->get()->toArray(),
            ]);

            $cs_activity_data['case_register_id'] = $id;

            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'নতুন মামলা রেজিস্ট্রেশন করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'মামলার তথ্য হালনাগাদ করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);

            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);
    }

    public function sendingReplyEdit($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        // $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['ministrys'] = GovCaseOffice::get();
        // $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();
        // dd($data['concern_person_desig']);
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['usersInfo'] = User::all();
        // return $data['usersInfo'];
        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();
        // return $data['concern_person_desig'];
        $data['page_title'] = 'জবাব প্রেরণ';
        // return $data['concern_person_desig'] ;
        // return $data;
        return view('gov_case.case_register._inc.sending_reply_edit')->with($data);
    }

    public function sendingReplyStore(Request $request)
    {
        // dd($request);
        $caseId = $request->case_id;
        $request->validate(
            [
                'case_id' => 'required',
            ],
            [
                'case_id' => 'জবাব প্রেরণের তথ্য মামলার অ্যাকশন থেকে পূরণ করুণ',
            ]
        );
        try {
            $caseInfo = GovCaseRegisterRepository::storeSendingReply($request);

            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeReplyAttachment('gov_case', $caseId, $request);
            }
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);
            // return $caseRegisterData;
            $cs_activity_data['case_register_id'] = $caseId;
            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

        // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    public function suspensionOrderEdit($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        // $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['ministrys'] = GovCaseOffice::get();
        // $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();
        // dd($data['concern_person_desig']);
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['usersInfo'] = User::all();
        // return $data['usersInfo'];
        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();
        // return $data['concern_person_desig'];
        $data['page_title'] = 'স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিষয়ে ব্যাবস্থা';
        // return $data['concern_person_desig'] ;
        // return $data;
        return view('gov_case.case_register._inc.suspension_order_edit')->with($data);
    }

    public function suspensionOrderStore(Request $request)
    {
        // dd($_FILES["file_name"]['name']);
        $caseId = $request->case_id;
        $request->validate(
            [
                'case_id' => 'required',
            ],
            [
                'case_id' => 'স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের তথ্য মামলার অ্যাকশন থেকে পূরণ করুণ',
            ]
        );
        try {
            $caseId = GovCaseRegisterRepository::storeSuspensionOrder($request);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeSuspentionOrderAttachment('gov_case', $caseId, $request);
            }
            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);
            // return $caseRegisterData;
            $cs_activity_data['case_register_id'] = $caseId;
            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

        // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }
    public function finalOrderEdit($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        // $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['ministrys'] = GovCaseOffice::get();
        // $data['concern_person'] = User::whereIn('role_id', [15,34,35])->get();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();
        // dd($data['concern_person_desig']);
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['usersInfo'] = User::all();
        // return $data['usersInfo'];
        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();
        // return $data['concern_person_desig'];
        $data['page_title'] = 'চূড়ান্ত আদেশ';
        // return $data['concern_person_desig'] ;
        // return $data['case']->id;
        return view('gov_case.case_register._inc.final_order_edit')->with($data);
    }

    public function contemptCaseIssue($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['ministrys'] = GovCaseOffice::get();

        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['usersInfo'] = User::all();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();

        $data['page_title'] = 'কনটেম্প্ট মামলা / অন্যান্য';

        return view('gov_case.case_register._inc.contempt_case_issue')->with($data);
    }

    public function finalOrderStore(Request $request)
    {
        // dd($request);
        $caseId = $request->case_id;
        $request->validate(
            [
                'case_id' => 'required',
            ],

        );
        try {
            $caseId = GovCaseRegisterRepository::storeFinalOrder($request);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeFinalOrderAttachment('gov_case', $caseId, $request);
            }
            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);
            // return $caseRegisterData;
            $cs_activity_data['case_register_id'] = $caseId;
            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'মামলার চূড়ান্ত আদেশের তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'মামলার চূড়ান্ত আদেশের তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

        // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    // public function finalOrderStore(Request $request)
    // {
    //     // dd($request);
    //     $caseId = $request->case_id;
    //     $request->validate(
    //         [
    //             'case_id' => 'required',
    //         ],

    //     );
    //     try {
    //         $caseId = GovCaseRegisterRepository::storeFinalOrder($request);
    //         if ($request->file_type && $_FILES["file_name"]['name']) {
    //             AttachmentRepository::storeFinalOrderAttachment('gov_case', $caseId, $request);
    //         }
    //         //========= Gov Case Activity Log -  start ============
    //         $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
    //         $caseRegisterData = array_merge($caseRegister, [
    //             'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
    //             'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
    //             'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
    //             'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
    //         ]);
    //         // return $caseRegisterData;
    //         $cs_activity_data['case_register_id'] = $caseId;
    //         if ($request->formType != 'edit') {
    //             $cs_activity_data['activity_type'] = 'create';
    //             $cs_activity_data['message'] = 'মামলার চূড়ান্ত আদেশের তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
    //         } else {
    //             $cs_activity_data['activity_type'] = 'update';
    //             $cs_activity_data['message'] = 'মামলার চূড়ান্ত আদেশের তথ্য সফলভাবে হালনাগাদ করা হয়েছে';
    //         }
    //         $cs_activity_data['old_data'] = null;
    //         $cs_activity_data['new_data'] = json_encode($caseRegisterData);
    //         gov_case_activity_logs($cs_activity_data);
    //         // ========= Gov Case Activity Log  End ==========

    //     } catch (\Exception $e) {
    //         dd($e);
    //         $flag = 'false';
    //         return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
    //     }
    //     return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

    //     // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    // }

    public function contemptCaseStore(Request $request)
    {
        // dd($request);
        $caseId = $request->case_id;
        $request->validate(
            [
                'case_id' => 'required',
            ],
            [
                'case_id' => 'স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের তথ্য মামলার অ্যাকশন থেকে পূরণ করুণ',
            ]
        );
        try {
            $caseId = GovCaseRegisterRepository::storeContemptCase($request);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeContemptAttachment('gov_case', $caseId, $request);
            }
            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);
            // return $caseRegisterData;
            // $cs_activity_data['case_register_id'] = $caseId;

            // $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            // gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);
    }

    // public function contemptCaseStoreActionButton(Request $request,$id)
    // {
    //     dd($request);
    //     $caseId = $request->id;
    //     $request->validate(
    //         [
    //             'case_id' => 'required',
    //         ],
    //         [
    //             'case_id' => 'স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের তথ্য মামলার অ্যাকশন থেকে পূরণ করুণ',
    //         ]
    //     );
    //     try {
    //         $caseId = GovCaseRegisterRepository::storeContemptCase($request);
    //         if ($request->file_type && $_FILES["file_name"]['name']) {
    //             AttachmentRepository::storeContemptAttachment('gov_case', $caseId, $request);
    //         }
    //         //========= Gov Case Activity Log -  start ============
    //         $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
    //         $caseRegisterData = array_merge($caseRegister, [
    //             'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
    //             'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
    //             'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
    //             'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
    //         ]);
    //         // return $caseRegisterData;
    //         $cs_activity_data['case_register_id'] = $caseId;

    //         $cs_activity_data['old_data'] = null;
    //         $cs_activity_data['new_data'] = json_encode($caseRegisterData);
    //         gov_case_activity_logs($cs_activity_data);
    //         // ========= Gov Case Activity Log  End ==========

    //     } catch (\Exception $e) {
    //         dd($e);
    //         $flag = 'false';
    //         return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
    //     }
    //     return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);
    // }

    public function highcourt_edit($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        $data['ministrys'] = GovCaseOffice::get();

        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['usersInfo'] = User::all();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();

        $data['highCourtAdalat'] = HighcourtAdalat::get();

        $data['page_title'] = 'মামলা সংশোধন';

        return view('gov_case.case_register.highcourt_edit')->with($data);
    }

    public function editHighcourtCaseApplication($caseNo)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;
        $caseId = GovCaseRegister::where('case_no', $caseNo)->where('deleted_at', null)->first();

        if ($caseId) {
            $id = $caseId->id;
        }

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        $data['ministrys'] = GovCaseOffice::get();

        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();

        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();

        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['usersInfo'] = User::all();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();

        $data['highCourtAdalat'] = HighcourtAdalat::get();

        $data['page_title'] = 'মামলা সংশোধন';

        return view('gov_case.case_register.application_form_as_main_defendent.highcourt_edit')->with($data);
    }

    public function highcourt_case_delete($id)
    {

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;

        $data = GovCaseRegister::findOrFail($id);
        $data->delete();

        // $query = GovCaseRegister::orderby('id', 'DESC')->where('case_division_id', 2)->where('deleted_at', '=', null);
        return redirect()->back()->with('message', 'IT WORKS!');
    }

    public function highcourt_old_case_create()
    {

        session()->forget('currentUrlPath');

        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        // $data['ministrys'] = Office::whereIn('level', [8,9])->get();
        $data['ministrys'] = GovCaseOffice::get();
        // $data['ministrys'] = DB::table('gov_case_office')->get();

        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();
        // return $data['concern_person_desig'];
        $data['courts'] = DB::table('court')
            ->select('id', 'court_name')
            ->whereIn('id', [1, 2])
            ->get();
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
        if ($roleID != 33) {
            $data['depatments'] = Office::where('parent', $officeID)->get();
        } else {
            $data['depatments'] = Office::where('level', 12)->get();
        }
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::where('gov_case_division_id', 2)->get();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['appealCase'] = DB::table('gov_case_registers')->select('id', 'case_no')->where('case_division_id', 2)->where('status', 3)->get();

        $data['case_types'] = DB::table('case_type')->select('id', 'ct_name')->get();
        $data['surveys'] = DB::table('survey_type')->select('id', 'st_name')->get();
        $data['land_types'] = DB::table('land_type')->select('id', 'lt_name')->get();

        $data['highCourtAdalat'] = HighcourtAdalat::get();

        $data['page_title'] = 'নিস্পত্তিকৃত হাইকোর্ট মামলা এন্ট্রি '; //exit;
        // dd($data);
        // return $data;
        return view('gov_case.case_register.create_old_highcourt_case')->with($data);
    }

    public function leaveToAppealCreate($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        $data['page_title'] = 'লিভ টু আপিল আবেদন';
        // return $data['concern_person_desig'] ;
        // return $data;
        return view('gov_case.case_register._inc.leave_to_appeal_create')->with($data);
    }

    public function leaveToAppealStore(Request $request)
    {
        // return $request;
        $caseId = $request->case_id;
        $request->validate(
            [
                'case_id' => 'required',
                'leave_to_appeal_no' => 'required',
            ],
            [
                'leave_to_appeal_no' => 'লিভ টু আপিল নম্বর পূরণ করুণ',
            ]
        );
        try {
            $caseId = GovCaseRegisterRepository::storeLeaveToAppealInfo($request);
            if ($request->leave_to_appeal_file_type && $_FILES["leave_to_appeal_file_name"]['name']) {
                AttachmentRepository::storeLeaveToAppealAttachment('gov_case', $caseId, $request);
            }
            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);
            // return $caseRegisterData;
            $cs_activity_data['case_register_id'] = $caseId;
            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'লিভ টু আপিল করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'লিভ টু আপিল করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

        // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    public function leaveToAppealAnswerCreate($id)
    {
        $roleID = userInfo()->role_id;

        $officeID = userInfo()->office_id;

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        $data['page_title'] = 'লিভ টু আপিল আবেদন';

        return view('gov_case.case_register._inc.leave_to_appeal_answer_create')->with($data);
    }

    public function leaveToAppealAnswerStore(Request $request)
    {
        // dd($request);
        $caseId = $request->case_id;
        $request->validate(
            [
                'case_id' => 'required',
                'leave_to_appeal_order_date' => 'required',
            ],
            [
                'leave_to_appeal_order_date' => 'লিভ টু আপিলের রায় প্রদানের তারিখ পূরণ করুণ',
            ]
        );
        try {
            $caseId = GovCaseRegisterRepository::storeLeaveToAppealAnswerInfo($request);
            if ($request->leave_to_appeal_file_type && $_FILES["leave_to_appeal_file_name"]['name']) {
                AttachmentRepository::storeLeaveToAppealAttachment('gov_case', $caseId, $request);
            }
            //========= Gov Case Activity Log -  start ============
            $caseRegister = GovCaseRegister::findOrFail($caseId)->toArray();
            $caseRegisterData = array_merge($caseRegister, [
                'badi' => GovCaseBadi::where('gov_case_id', $caseId)->get()->toArray(),
                'bibadi' => GovCaseBibadi::where('gov_case_id', $caseId)->get()->toArray(),
                'attachment' => Attachment::where('gov_case_id', $caseId)->get()->toArray(),
                'log_data' => GovCaseLog::where('gov_case_id', $caseId)->get()->toArray(),
            ]);
            // return $caseRegisterData;
            $cs_activity_data['case_register_id'] = $caseId;
            if ($request->formType != 'edit') {
                $cs_activity_data['activity_type'] = 'create';
                $cs_activity_data['message'] = 'লিভ টু আপিল করা হয়েছে';
            } else {
                $cs_activity_data['activity_type'] = 'update';
                $cs_activity_data['message'] = 'লিভ টু আপিল করা হয়েছে';
            }
            $cs_activity_data['old_data'] = null;
            $cs_activity_data['new_data'] = json_encode($caseRegisterData);
            gov_case_activity_logs($cs_activity_data);
            // ========= Gov Case Activity Log  End ==========

        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return response()->json(['success' => 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', 'caseId' => $caseId]);

        // return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    public function create_appeal($id)
    {

        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['ministrys'] = Office::whereIn('level', [8, 9])->get();
        $data['concern_person'] = User::whereIn('role_id', [15, 34, 35])->get();
        $data['courts'] = Court::select('id', 'court_name')->get();
        $data['GovCaseDivision'] = GovCaseDivision::all();
        $data['page_title'] = 'আপিল মামলা এন্ট্রি ফরম'; //exit;
        // dd($data);
        return view('gov_case.case_register.creat_appeal')->with($data);
    }

    public function store_appeal(Request $request, $id = '')
    {
        // return $request;
        $caseId = $request->caseId;
        // dd($caseId);
        // 'email' => 'unique:users,email_address,'.$user->id

        $request->validate(
            [
                'case_no' => 'required|unique:gov_case_registers,case_no,' . $caseId,
            ],
            [
                'case_no.unique' => 'মামলা নং ইতিমধ্যে বিদ্যমান আছে',

            ]
        );
        try {
            $caseId = AppealGovCaseRegisterRepository::storeAppeal($request, $id);
            // dd($caseId);
            GovCaseBadiBibadiRepository::storeBadi($request, $caseId);
            GovCaseBadiBibadiRepository::storeBibadi($request, $caseId);
            GovCaseLogRepository::storeGovCaseLog($caseId);
            if ($request->file_type && $_FILES["file_name"]['name']) {
                AttachmentRepository::storeAttachment('gov_case', $caseId, $request);
            }
        } catch (\Exception $e) {
            dd($e);
            $flag = 'false';
            return redirect()->back()->with('error', 'তথ্য সংরক্ষণ করা হয়নি ');
        }
        return redirect()->back()->with('success', 'তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    public function getCaseCategory($id)
    {
        // return $id;
        $categories = GovCaseDivisionCategory::orderby('id', 'desc')->where('gov_case_division_id', $id)->pluck("name_bn", "id");
        return json_encode($categories);
    }

    public function getDependentCaseCategoryType($id)
    {
        $categories = GovCaseDivisionCategoryType::orderby('id', 'desc')->where('gov_case_category_id', $id)->pluck("name_bn", "id");
        return json_encode($categories);
    }

    // for appeal origin case number
    public function getDependentCaseOriginNumber($id)
    {
        $originCaseNumber = GovCaseRegister::orderby('id', 'desc')
            ->where('case_category_id', $id)
            ->where('is_final_order', 1)
        // ->pluck("case_no", "id", "year");
            ->where('leave_to_appeal_is_favour_of_gov', 1)
            ->select("case_no", "id", "year")->get();

        return json_encode($originCaseNumber);
    }

    public function getOriginCaseDetails($id)
    {
        // $originCaseDetails = GovCaseRegister::orderby('id', 'desc')
        //     ->where('case_category_id', $id)
        //     ->where('is_final_order', 1)
        //     ->pluck("case_no", "id");
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        // $data = GovCaseRegister::where('id', $id)->first();
        return json_encode($data);
    }
    public function getHighCourtCaseDetails($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        // return $data;
        // $data = GovCaseRegister::where('id', $id)->first();
        return view('gov_case.appeal_case_register._inc.get_highcourt_case_for_appeal', $data);
    }
    public function register($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        if ($data['case']->case_division_id == 2) {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলা সম্পর্কিত রেজিস্টার';
            return view('gov_case.case_register.highCourtRegister')->with($data);
        } else {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট আপিল বিভাগের মামলা সম্পর্কিত রেজিস্টার';
            return view('gov_case.case_register.appealRegister')->with($data);
        }

    }

    public function show($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();
        $data['usersInfo'] = User::all();

        if ($data['case']->case_division_id == 2) {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলার বিস্তারিত তথ্য';
        } else {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলার বিস্তারিত তথ্য';
        }
        // dd($data['files'],$data['replyFiles']);
        //  return $data;
        return view('gov_case.case_register.showDetails')->with($data);
        // return $data;
    }

    public function highcourtDetailsPdf($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);
        $data['GovCaseDivisionCategory'] = GovCaseDivisionCategory::all();
        $data['GovCaseDivisionCategoryType'] = GovCaseDivisionCategoryType::all();
        $data['concern_person_desig'] = Role::whereIn('id', [14, 15, 33, 36])->get();
        $data['usersInfo'] = User::all();

        if ($data['case']->case_division_id == 2) {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলার বিস্তারিত তথ্য';
        } else {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলার বিস্তারিত তথ্য';
        }
        // return view('gov_case.case_register.showDetails')->with($data);

        $html = view('gov_case.case_register.showDetailsPdf')->with($data);

        $this->generatePDF($html);
    }

    public function highcourtRegisterPdf($id)
    {
        $data = GovCaseRegisterRepository::GovCaseAllDetails($id);

        if ($data['case']->case_division_id == 2) {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট হাইকোর্ট বিভাগের মামলা সম্পর্কিত রেজিস্টার';

        } else {
            $data['page_title'] = 'সরকারি স্বার্থসংশ্লিষ্ট আপিল বিভাগের মামলা সম্পর্কিত রেজিস্টার';

        }
        //  return $data;
        $html = view('gov_case.case_register.showHighcourtRegisterPdf')->with($data);
// return $html;
        $this->generatePDF($html);
    }
    public function generatePDF($html)
    {
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font' => 'kalpurush',
            //  'format' => 'A4-L',
            //  'orientation' => 'L',
        ]);
        // $mpdf->AddPageByArray([
        // 'margin-left' => 5,
        // 'margin-right' => 5,
        // 'margin-top' => 5,
        // 'margin-bottom' => 5,
        // ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        // $mpdf->shrink_tables_to_fit = 1;
        $mpdf->use_kwt = true;
    }
    // public function generatePDF($html)
    // {
    //     if (ob_get_contents()) {
    //         ob_end_clean();
    //     }

    //     $mpdf = new \Mpdf\Mpdf([
    //         'default_font_size' => 12,
    //         'default_font' => 'kalpurush',
    //         // 'format' => 'A4-L',
    //         'orientation' => 'L',
    //     ]);
    //     // dd($html);
    //     $mpdf->WriteHTML($html);
    //     $mpdf->Output();
    // }

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
        if ($res == false) {
            return Response()->json(["error" => 'Something Went Wrong']);
        }
        return Response()->json(["success" => 'সফলভাবে তথ্য  মুছে ফেলা হয়েছে']);
    }
    public function getdependentMinDept($id)
    {
        $getdependentDoptor = GovCaseOffice::where('level', $id)->pluck("office_name_bn", "id");
        return json_encode($getdependentDoptor);
    }
    public function getDependentConcernPerson($id)
    {
        $getdependentUser = User::where('role_id', $id)->pluck("name", "id");
        return json_encode($getdependentUser);
    }

    public function sentToSolicitorCaseList()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->whereNull('result_sending_date')
            ->where('is_final_order', 0)
            ->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 33) {
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

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট জবাব পেন্ডিং মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function postponedInterimOrderCaseList()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->whereNull('appeal_against_postpond_interim_order')
            ->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 33) {
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

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function againstHighCourtCaseAppealPending()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->where('result', 2)
            ->where('is_appeal', 2)
            ->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 33) {
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

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        if ($roleID == 5 || $roleID == 7) {
            $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        } elseif ($roleID == 9 || $roleID == 21) {
            $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলার তালিকা';

        return view('gov_case.case_register.highcourt')->with($data);
    }

    public function attorneyHighcourtMostImportantCase()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        // $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $authUserId = Auth()->user()->id;

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->where('deleted_at', '=', null)
            ->where('concern_user_id', $authUserId)
            ->where('most_important', 1);

        if (!empty($_GET['case_category_id'])) {
            $query->where('gov_case_registers.case_category_id', '=', $_GET['case_category_id']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }
        if (!empty($_GET['division'])) {
            $query->where('gov_case_registers.division_id', '=', $_GET['division']);
        }
        if (!empty($_GET['district'])) {
            $query->where('gov_case_registers.district_id', '=', $_GET['district']);
        }
        if (!empty($_GET['upazila'])) {
            $query->where('gov_case_registers.upazila_id', '=', $_GET['upazila']);
        }
        // if ($roleID == 5 || $roleID == 7) {
        //     $query->where('district_id', $officeInfo->district_id)->orderby('id', 'DESC');
        // } elseif ($roleID == 9 || $roleID == 21) {
        //     $query->where('upazila_id', $officeInfo->upazila_id)->orderby('id', 'DESC');
        // }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট অতি গুরুত্বপূর্ণ মামলার তালিকা';

        // return $data;

        return view('gov_case.case_register.attorney_most_important_highcourt')->with($data);
    }

    public function countHighCourtAppealPospondOrderPendingCase($id)
    {
        $query = GovCaseRegister::where('appeal_against_postpond_interim_order', null)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
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

    public function countHighCourtAgainstGovCase($id)
    {
        $query = GovCaseRegister::where('result', 2)->where('is_appeal', 2)->where('deleted_at', null)->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }

    public function countAppealRunningCase($id)
    {
        $query = AppealGovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)->orderby('id', 'DESC')->whereIn('appeal_office_id', $id)->get();
        return $query;
    }

    public function countHighCourtRunningCase($id)
    {
        $query = GovCaseRegister::where('is_final_order', 0)->where('deleted_at', null)
            ->orderby('id', 'DESC')->whereHas('bibadis', function ($query) use ($id) {
            $query->whereIn('respondent_id', $id)->where('is_main_bibadi', 1)->groupBy('gov_case_id');
        })->get();
        return $query;
    }

    public function checkCaseNo(Request $request)
    {
        $caseNo = $request->input('case_no');

        $exists = GovCaseRegister::where('case_no', $caseNo)->where('deleted_at', null)->exists();
        $caseId = GovCaseRegister::where('case_no', $caseNo)->where('deleted_at', null)->first();

        if ($caseId && $exists) {
            $id = $caseId->id;
            $officeId = GovCaseBibadi::where('gov_case_id', $id)
                ->where('is_main_bibadi', 1)
                ->groupBy('gov_case_id')->first();

            $officeName = GovCaseOffice::where('doptor_office_id', $officeId->respondent_id)->first();

            return response()->json(['exists' => $exists, 'officeName' => $officeName->office_name_bn]);
        }
    }

    public function ministriesId()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://n-doptor-api.nothi.gov.bd/api/offices',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('layer_levels' => 3)),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-version: 1',
                'apikey: 8XI1PI',
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE3MDY3NzkyMDMsImp0aSI6Ik1UY3dOamMzT1RJd013PT0iLCJpc3MiOiJodHRwczpcL1wvYXBpLXN0YWdlLmRvcHRvci5nb3YuYmRcLyIsIm5iZiI6MTcwNjc3OTIwMywiZXhwIjoxNzA2ODY1NjAzLCJkYXRhIjoie1wiY2xpZW50X25hbWVcIjpcIlNtYXJ0IENhc2UgTWFuYWdlbWVudCBTeXN0ZW1cIixcInVzZXJuYW1lXCI6XCIyMDAwMDAwMDI5NjJcIn0ifQ.P4lk5ndJBjH4bwj4bGU13SjAV0LZq7hhBCFT5jWcpBbN2bcOIBpWdkhXmg29dFUAZY8oHOVm7AS4N41IAEjfVQ',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function ministryIdInsert()
    {
        $ministriesInfo = $this->ministriesId();

        $response = json_decode($ministriesInfo, true);
        $responseData = $response['data'];

        foreach ($responseData as $entry) {
            $office = new GovCaseOffice();
            $office->doptor_office_id = $entry['id'];
            $office->office_name_bn = $entry['office_name_bng'];
            $office->office_name_en = $entry['office_name_eng'];
            $office->parent_office_id = $entry['parent_office_id'];
            $office->custom_layer_id = $entry['custom_layer_id'];
            $office->office_ministry_id = $entry['office_ministry_id'];
            $office->level = 5;
            $office->parent = null;
            $office->status = 1;
            $office->save();
        }

        // foreach ($response as $officeData) {

        //     $id = $officeData['id'];
        //     $nameBng = $officeData['nameBn'];
        //     $nameEng = $officeData['name'];
        //     $nameShort = $officeData['nameShort'];
        //     $nameReference = $officeData['reference'];
        //     $nameType = $officeData['type'];

        //     $dataToSave = [
        //         'doptor_office_id' => $id,
        //         'level' => 1,
        //         'parent' => null,
        //         'parent_doptor_id' => null,
        //         'parent_layer_id' => null,
        //         'parent_name' => null,
        //         'office_name_bn' => $nameBng,
        //         'office_name_en' => $nameEng,
        //         'status' => 1,
        //         'reference' => $nameReference,
        //         'type' => $nameType,
        //     ];

        //     GovOffice::create($dataToSave);

        // }

        // $ministriesInfo = GovOffice::get();

        // foreach ($ministriesInfo as $officeData) {
        //     $tableId = $officeData->id;
        //     $doptorId = $officeData->doptor_office_id;

        //     // $ministryLayerId = $this->ministryLayerOffices($doptorId);
        //     $ministryOrganogram = $this->ministryOraganogram($doptorId);
        //     $responseMinistryLayer = json_decode($ministryOrganogram, true);

        //     foreach ($responseMinistryLayer as $ministryLayer) {

        //         if (is_array($ministryLayer)) {
        //         $ministryLayerId = $ministryLayer['id'];
        //         $parentId = $ministryLayer['parent'];
        //         $sequence = $ministryLayer['sequence'];
        //         $ministry = $ministryLayer['ministry'];
        //         $level = $ministryLayer['level'];
        //         $nameBn = $ministryLayer['nameBn'];
        //         $nameEn = $ministryLayer['name'];

        //         $dataToSave = [
        //             'doptor_office_id' => $ministryLayerId,
        //             'level' => $level,
        //             'parent' => $tableId,
        //             'sequence' => $sequence,
        //             'parent_doptor_id' => $ministry,
        //             'parent_layer_id' => null,
        //             'parent_name' => null,
        //             'office_name_bn' => $nameBn,
        //             'office_name_en' => $nameEn,
        //             'doptor_parent_id' => $parentId,
        //             'status' => 1,
        //         ];

        //         GovOffice::create($dataToSave);
        //     }
        // }
        // }
        //  }

    }

    public function ministryLayerId($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://n-doptor-api.nothi.gov.bd/api/ministry/layers/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS => array('ministry_id' => '25'),
            CURLOPT_POSTFIELDS => json_encode(array('ministry_id' => $id)),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-version: 1',
                'apikey: 8XI1PI',
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE3MDYwOTg5MzYsImp0aSI6Ik1UY3dOakE1T0Rrek5nPT0iLCJpc3MiOiJodHRwczpcL1wvYXBpLXN0YWdlLmRvcHRvci5nb3YuYmRcLyIsIm5iZiI6MTcwNjA5ODkzNiwiZXhwIjoxNzA2MTg1MzM2LCJkYXRhIjoie1wiY2xpZW50X25hbWVcIjpcIlNtYXJ0IENhc2UgTWFuYWdlbWVudCBTeXN0ZW1cIixcInVzZXJuYW1lXCI6XCIyMDAwMDAwMDI5NjJcIn0ifQ.mJaC0nsi2vTy79ytTEBsc-u0oscONPRr5sNevb_PsCbO8i9TVF74BZsd0ddvap3wVmbzHkx2uXJCQlpTDOFN2A',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function ministryLayerOffices($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apigw-stage.doptor.gov.bd/api/v1/officeorigin?ministry=' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-version: 1',
                'apikey: 8XI1PI',
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE3MDY0MTU4MTAsImp0aSI6Ik1UY3dOalF4TlRneE1BPT0iLCJpc3MiOiJodHRwczpcL1wvYXBpLXN0YWdlLmRvcHRvci5nb3YuYmRcLyIsIm5iZiI6MTcwNjQxNTgxMCwiZXhwIjoxNzA2NTAyMjEwLCJkYXRhIjoie1wiY2xpZW50X25hbWVcIjpcIlNtYXJ0IENhc2UgTWFuYWdlbWVudCBTeXN0ZW1cIixcInVzZXJuYW1lXCI6XCIyMDAwMDAwMDI5NjJcIn0ifQ.4JH70gU1GCLoO1eUr1HRfMqFOZjZYgGTQi5ZiStZZ8lZ0O23EXmCGm_t9RG2iXtL9aRmF1VZpb7gtZOxmbs_Lg',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function ministryLayerAndOffices($layerId, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://n-doptor-api.nothi.gov.bd/api/ministry/layer/offices/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('ministry_id' => $id, 'layer_id' => $layerId)),
            // CURLOPT_POSTFIELDS => array('ministry_id' => '13','layer_id' => '62'),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-version: 1',
                'apikey: 8XI1PI',
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE3MDYwOTY4OTIsImp0aSI6Ik1UY3dOakE1TmpnNU1nPT0iLCJpc3MiOiJodHRwczpcL1wvYXBpLXN0YWdlLmRvcHRvci5nb3YuYmRcLyIsIm5iZiI6MTcwNjA5Njg5MiwiZXhwIjoxNzA2MTgzMjkyLCJkYXRhIjoie1wiY2xpZW50X25hbWVcIjpcIlNtYXJ0IENhc2UgTWFuYWdlbWVudCBTeXN0ZW1cIixcInVzZXJuYW1lXCI6XCIyMDAwMDAwMDI5NjJcIn0ifQ.Ib_qBBCG-lj1fOIGTo5hY6b5F_P83ctuNlJ42zdjE5hw6eWzhJ57MxwkTJX_1IxRaoqw2TIJvGUeeQEYalmJ4Q',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // public function ministryIdInsert()
    // {
    //     // $ministriesId = $this->ministriesId();
    //     // $response = json_decode($ministriesId, true);
    //     // if ($response['status'] === 'success') {

    //     //     foreach ($response['data'] as $officeData) {

    //     //         $id = $officeData['id'];
    //     //         $ministryLayerId = $this->ministryLayerId($id);

    //     //         $responseMinistryLayer = json_decode($ministryLayerId, true);
    //     //         if ($responseMinistryLayer['status'] === 'success') {

    //     //             foreach ($responseMinistryLayer['data'] as $officeDataMinistryLayerData) {
    //     //                 $layerId = $officeDataMinistryLayerData['id'];
    //     //                 $nameBng = $officeDataMinistryLayerData['layer_name_bng'];
    //     //                 $nameEng = $officeDataMinistryLayerData['layer_name_eng'];
    //     //                 $parentLayerId = $officeDataMinistryLayerData['parent_layer_id'];

    //     //                 $dataToSave = [
    //     //                     'min_id' =>$id,
    //     //                     'layer_id' => $layerId,
    //     //                     'parent_layer_id' => $parentLayerId,
    //     //                     'layer_name_bng' => $nameBng,
    //     //                     'layer_name_eng' => $nameEng,
    //     //                     'status' => 1,
    //     //                 ];

    //     //                 MinistryLayers::create($dataToSave);
    //     //             }
    //     //         }
    //     //         //    $nameBng = $officeData['name_bng'];
    //     //         //    $nameEng = $officeData['name_eng'];
    //     //         //    $officeType = $officeData['office_type'];

    //     //         //    $dataToSave = [
    //     //         //        'doptor_office_id' => $id,
    //     //         //        'level' => 1,
    //     //         //        'parent' => null,
    //     //         //        'parent_doptor_id' => null,
    //     //         //        'parent_name' => null,
    //     //         //        'office_name_bn' => $nameBng,
    //     //         //        'office_name_en' => $nameEng,
    //     //         //        'status' => 1,
    //     //         //    ];

    //     //         //
    //     //         // DoptorOffice::create($dataToSave);
    //     //     }
    //     //     dd($ministryLayerId);
    //     //     echo "Data saved successfully!";
    //     // } else {

    //     //     echo "Failed to fetch data!";
    //     // }

    // }

    public function ministryOraganogram()
    {

    }

    public function highcourtNotAgainstGov()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')
            ->where('is_final_order', 1)
            ->where('result', 1)->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট সরকারের পক্ষে মামলার তালিকা';

        return view('gov_case.case_register.highcourt_not_against_highcourt_gov')->with($data);
    }

    public function highcourtAgainstGov()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }
        $query = GovCaseRegister::orderby('id', 'DESC')
            ->where('is_final_order', 1)
            ->where('result', 2)->where('deleted_at', '=', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট সরকারের বিপক্ষে মামলার তালিকা';

        return view('gov_case.case_register.highcourt_against_highcourt_gov')->with($data);
    }
    public function sentToSolicitorPending()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->whereNull('result_sending_date')->where('is_final_order', 0)->where('deleted_at', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট জবাব প্রেরণের জন্য পেন্ডিংমামলার তালিকা';

        return view('gov_case.case_register.highcourt_sentToSolicitorPending')->with($data);
    }
    public function pendingPostpondOrder()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->whereNull('appeal_against_postpond_interim_order')->where('deleted_at', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট স্থগিতাদেশ সম্পর্কিত পেন্ডিং মামলার তালিকা';

        return view('gov_case.case_register.highcourt_pendingPostpondOrder')->with($data);
    }

    public function contemptCaseList()
    {
        session()->forget('currentUrlPath');

        $officeInfo = user_office_info();
        $roleID = userInfo()->role_id;
        $officeID = userInfo()->office_id;
        $childOfficeQuery = DB::table('gov_case_office')
            ->select('id')
            ->where('parent', $officeID)->get();

        foreach ($childOfficeQuery as $childOffice) {
            $childOfficeIds[] = $childOffice->id;
        }

        $finalOfficeIds = [];

        if (empty($childOfficeIds)) {
            $finalOfficeIds[] = $officeID;
        } else {
            $finalOfficeIds[] = $officeID;
            $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
        }

        $query = GovCaseRegister::orderby('id', 'DESC')
            ->whereNull('contempt_case_isuue_date')->whereNull('contempt_case_answer_sending_date')->where('deleted_at', null);

        if ($roleID == 32 || $roleID == 41) {
            $query->whereHas(
                'mainBibadis',
                function ($query) use ($officeID) {
                    $query->where('respondent_id', $officeID);
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

        if (!empty($_GET['case_category_type'])) {
            $query->where('gov_case_registers.case_type_id', '=', $_GET['case_category_type']);
        }

        if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
            // dd(1);
            $dateFrom = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_start'])));
            $dateTo = date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_end'])));
            $query->whereBetween('date_issuing_rule_nishi', [$dateFrom, $dateTo]);
        }

        if (!empty($_GET['case_no'])) {
            $query->where('gov_case_registers.case_no', '=', $_GET['case_no']);
        }

        $data['cases'] = $query->paginate(10);

        $data['case_divisions'] = DB::table('gov_case_divisions')->select('id', 'name_bn')->get();
        $data['division_categories'] = DB::table('gov_case_division_categories')->select('id', 'name_bn')->where('gov_case_division_id', 2)->get();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->get();

        $data['gov_case_division_category_type'] = GovCaseDivisionCategoryType::orderby('id', 'desc')->select('id', 'name_bn')->get();

        $data['page_title'] = 'হাইকোর্ট বিভাগে সরকারি স্বার্থসংশ্লিষ্ট কন্টেম্পট সম্পর্কিত  মামলার তালিকা';

        return view('gov_case.case_register.highcourt_contempt_case_list')->with($data);
    }
}
