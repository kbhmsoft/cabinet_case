<?php

namespace App\Http\Controllers\gov_case;

use Redirect;
use Response;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\DoptorHandler;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\DoptorGovOffice;
use App\Models\gov_case\GovCaseOfficeType;
use App\Models\gov_case\DoptorUserManagement;

class GovCaseOfficeController extends Controller
{
    // private $authenticationService;

    // public function __construct(DoptorHandler $authenticationService)
    // {
    //     $this->authenticationService = $authenticationService;
    //     $this->middleware('permission:create_new_office', ['only' => ['create']]);
    // }

    public function __construct()
    {
        $this->middleware('permission:create_new_office', ['only' => ['create']]);
        $this->middleware('permission:create_new_office', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();

        $data['page_title'] = 'অফিসের তালিকা';
        // $data['offices'] = GovCaseOffice::orderby('id','DESC')->paginate(10)->withQueryString();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = GovCaseOffice::orderby('id', 'ASC');

        if (!empty($_GET['office_type'])) {
            $query->where('gov_case_office.level', '=', $_GET['office_type']);
        }
        if (!empty($_GET['ministry'])) {
            $query->where('gov_case_office.parent', '=', $_GET['ministry']);
        }
        if (!empty($_GET['divOffice'])) {
            $query->where('gov_case_office.parent', '=', $_GET['divOffice']);
        }
        if (!empty($_GET['office_name'])) {
            $query->where('gov_case_office.office_name_bn', 'LIKE', '%' . $_GET['office_name'] . '%');
        }

        $data['offices'] = $query->paginate(10)->withQueryString();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['upazilas'] = null;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            // dd(1);
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }
        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function totalMinistryOffice()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        // dd($officeInfo);
        $data['page_title'] = 'ব্যাবহারকারী মন্ত্রণালয়ের তালিকা';
        // $data['offices'] = GovCaseOffice::orderby('id','DESC')->paginate(10)->withQueryString();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = GovCaseOffice::orderby('id', 'ASC')->where('level', 1);
        //    dd($query);
        if (!empty($_GET['office_type'])) {
            $query->where('gov_case_office.level', '=', $_GET['office_type']);
        }
        if (!empty($_GET['ministry'])) {
            $query->where('gov_case_office.parent', '=', $_GET['ministry']);
        }
        if (!empty($_GET['divOffice'])) {
            $query->where('gov_case_office.parent', '=', $_GET['divOffice']);
        }
        if (!empty($_GET['office_name'])) {
            $query->where('gov_case_office.office_name_bn', 'LIKE', '%' . $_GET['office_name'] . '%');
        }

        $data['offices'] = $query->paginate(10)->withQueryString();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['upazilas'] = null;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            // dd(1);
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }
        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function totalDoptor()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        // dd($officeInfo);
        $data['page_title'] = 'ব্যবহারকারী দপ্তর-সংস্থা তালিকা';
        // $data['offices'] = GovCaseOffice::orderby('id','DESC')->paginate(10)->withQueryString();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = GovCaseOffice::orderby('id', 'ASC')->whereIn('level', [2, 5]);


        //    dd($query);
        if (!empty($_GET['office_type'])) {
            $query->where('gov_case_office.level', '=', $_GET['office_type']);
        }
        if (!empty($_GET['ministry'])) {
            $query->where('gov_case_office.parent', '=', $_GET['ministry']);
        }
        if (!empty($_GET['divOffice'])) {
            $query->where('gov_case_office.parent', '=', $_GET['divOffice']);
        }
        if (!empty($_GET['office_name'])) {
            $query->where('gov_case_office.office_name_bn', 'LIKE', '%' . $_GET['office_name'] . '%');
        }

        $data['offices'] = $query->paginate(10)->withQueryString();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['upazilas'] = null;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            // dd(1);
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }
        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function totalDivisionOffice()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['page_title'] = 'ব্যাবহারকারী বিভাগীয় প্রশাসন তালিকা';
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        $query = GovCaseOffice::orderby('id', 'ASC')->where('level', 3);

        if (!empty($_GET['office_type'])) {
            $query->where('gov_case_office.level', '=', $_GET['office_type']);
        }
        if (!empty($_GET['ministry'])) {
            $query->where('gov_case_office.parent', '=', $_GET['ministry']);
        }
        if (!empty($_GET['divOffice'])) {
            $query->where('gov_case_office.parent', '=', $_GET['divOffice']);
        }
        if (!empty($_GET['office_name'])) {
            $query->where('gov_case_office.office_name_bn', 'LIKE', '%' . $_GET['office_name'] . '%');
        }

        $data['offices'] = $query->paginate(10)->withQueryString();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['upazilas'] = null;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }
        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function totalDistrictOffice()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['page_title'] = 'ব্যাবহারকারী জেলা প্রশাসন তালিকা';
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        $query = GovCaseOffice::orderby('id', 'ASC')->where('level', 4);

        if (!empty($_GET['office_type'])) {
            $query->where('gov_case_office.level', '=', $_GET['office_type']);
        }
        if (!empty($_GET['ministry'])) {
            $query->where('gov_case_office.parent', '=', $_GET['ministry']);
        }
        if (!empty($_GET['divOffice'])) {
            $query->where('gov_case_office.parent', '=', $_GET['divOffice']);
        }
        if (!empty($_GET['office_name'])) {
            $query->where('gov_case_office.office_name_bn', 'LIKE', '%' . $_GET['office_name'] . '%');
        }

        $data['offices'] = $query->paginate(10)->withQueryString();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['upazilas'] = null;
        $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

        if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
            $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
        }
        return view('gov_case.office.index')
            ->with($data)
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    // public function level_wise($level)
    // {
    //     //
    //     $roleID = Auth::user()->role_id;
    //     $officeInfo = user_office_info();
    //     // dd($officeInfo);
    //     $data['page_title'] = 'অফিস সেটিং তালিকা';
    //     $query = DB::table('office')
    //         ->leftjoin('division', 'office.division_id', '=', 'division.id')
    //         ->leftjoin('district', 'office.district_id', '=', 'district.id')
    //         ->leftjoin('upazila', 'office.upazila_id', '=', 'upazila.id')
    //         // ->where('office.is_gov', 1)
    //         ->where('office.level', $level)
    //         ->select('office.*', 'upazila.upazila_name_bn', 'district.district_name_bn', 'division.division_name_bn');
    //     if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
    //         $query->where('office.district_id', '=', $officeInfo->district_id);
    //     } elseif ($roleID == 9 || $roleID == 10 || $roleID == 11) {
    //         $query->where('office.upazila_id', '=', $officeInfo->upazila_id);
    //     }/*elseif($roleID == 12){
    //         $moujaIDs = $this->get_mouja_by_ulo_office_id(Auth::user()->office_id);
    //         // dd($moujaIDs);
    //         // print_r($moujaIDs); exit;
    //         $query->where('office.mouja_id', $moujaIDs);
    //     }   */

    //     //Add Conditions

    //     if (!empty($_GET['division'])) {
    //         $query->where('office.division_id', '=', $_GET['division']);
    //     }
    //     if (!empty($_GET['district'])) {
    //         $query->where('office.district_id', '=', $_GET['district']);
    //     }
    //     if (!empty($_GET['upazila'])) {
    //         $query->where('office.upazila_id', '=', $_GET['upazila']);
    //     }

    //     $data['offices'] = $query->paginate(10)->withQueryString();
    //     // dd($data['offices']);
    //     // Dorpdown
    //     // $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
    //     $data['upazilas'] = NULL;
    //     $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

    //     if ($roleID == 5 || $roleID == 6 || $roleID == 7 || $roleID == 8 || $roleID == 13) {
    //         // dd(1);
    //         $data['upazilas'] = DB::table('upazila')->select('id', 'upazila_name_bn')->where('district_id', $officeInfo->district_id)->get();
    //     }

    //     return view('gov_case.office.cabinet.index')
    //         ->with($data)
    //         ->with('i', (request()->input('page', 1) - 1) * 10);
    // }

    // public function parent_wise($parent)
    // {
    //     //
    //     $roleID = Auth::user()->role_id;
    //     $officeInfo = user_office_info();
    //     // dd($officeInfo);
    //     $data['page_title'] = 'অফিস সেটিং তালিকা';
    //     $query = DB::table('office')
    //         ->leftjoin('division', 'office.division_id', '=', 'division.id')
    //         ->leftjoin('district', 'office.district_id', '=', 'district.id')
    //         ->leftjoin('upazila', 'office.upazila_id', '=', 'upazila.id')
    //         // ->where('office.is_gov', 1)
    //         ->where('office.parent', $parent)
    //         ->select('office.*', 'upazila.upazila_name_bn', 'district.district_name_bn', 'division.division_name_bn');

    //     //Add Conditions

    //     if (!empty($_GET['division'])) {
    //         $query->where('office.division_id', '=', $_GET['division']);
    //     }
    //     if (!empty($_GET['district'])) {
    //         $query->where('office.district_id', '=', $_GET['district']);
    //     }
    //     if (!empty($_GET['upazila'])) {
    //         $query->where('office.upazila_id', '=', $_GET['upazila']);
    //     }

    //     $data['offices'] = $query->paginate(10)->withQueryString();
    //     // dd($data['offices']);
    //     // Dorpdown
    //     // $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();
    //     $data['upazilas'] = NULL;
    //     $data['divisions'] = DB::table('division')->select('id', 'division_name_bn')->get();

    //     return view('gov_case.office.index')
    //         ->with($data)
    //         ->with('i', (request()->input('page', 1) - 1) * 10);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        //
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        //
        $data['offices'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
        // ->where('level', 1)
            ->get();

        $data['ministries'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
            ->where('level', 1)
            ->get();

        $data['divisions'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
            ->where('level', 3)
            ->get();

        $data['division'] = DB::table('division')
            ->select('division.*')
            ->get();
        $data['court_type'] = DB::table('court_type')
            ->select('court_type.*')
            ->get();
        $data['office_type'] = DB::table('gov_case_office_type')
            ->select('gov_case_office_type.*')
            ->get();

        $data['page_title'] = 'নতুন অফিস এন্ট্রি ফরম';
        // dd($data);

        return view('gov_case.office.add')->with($data);
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
        // dd($request->all());
        // return $request;
        $roleID = Auth::user()->role_id;

        $validator = $request->validate([
            'office_name' => 'required',
            'status' => 'required',
        ]);
        if ($request->level == 2) {
            $parentID = $request->parentMinID;
        } elseif ($request->level == 4) {
            $parentID = $request->parentDivID;
        } else {
            $parentID = '';
        }
        DB::table('gov_case_office')->insert([
            'level' => $request->office_lavel,
            'office_name_bn' => $request->office_name,
            'status' => $request->status,
            'parent' => $parentID,
            'level' => $request->level,
        ]);
        return redirect()->route('cabinet.office')
            ->with('success', 'অফিস সফলভাবে সংরক্ষণ করা হয়েছে');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $data['offices'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
            ->where('gov_case_office.id', $id)
            ->first();

        $data['ministries'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
            ->where('level', 1)
            ->get();

        $data['divisions'] = DB::table('gov_case_office')
            ->select('gov_case_office.*')
            ->where('level', 3)
            ->get();
        $data['office_type'] = DB::table('gov_case_office_type')
            ->select('gov_case_office_type.*')
            ->get();

        // dd($data);
        $data['page_title'] = 'অফিসের তথ্য হালনাগাদ ফরম';
        // return $data;
        return view('gov_case.office.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request;
        $id = $request->office_id;
        $validator = $request->validate([
            'office_name' => 'required',
            'status' => 'required',
        ]);
        if ($request->level == 2) {
            $parentID = $request->parentMinID;
        } elseif ($request->level == 4) {
            $parentID = $request->parentDivID;
        } else {
            $parentID = '';
        }
        $data = [
            'level' => $request->office_lavel,
            'office_name_bn' => $request->office_name,
            'status' => $request->status,
            'parent' => $parentID,
            'level' => $request->level,
        ];

        DB::table('gov_case_office')
            ->where('id', $id)
            ->update($data);

        // dd($data);

        return redirect()->route('cabinet.office')
            ->with('success', 'অফিস সফলভাবে সংশোধন করা হয়েছে');
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

    public function getDependentDistrict($id)
    {
        $subcategories = DB::table("district")->where("division_id", $id)->pluck("district_name_bn", "id");
        return json_encode($subcategories);
    }
    public function getDependentUpazila($id)
    {
        $subcategories = DB::table("upazila")->where("district_id", $id)->pluck("upazila_name_bn", "id");
        return json_encode($subcategories);
    }
    public function getDependentOffice($id)
    {
        // $subcategories = GovCaseOffice::where("level", $id)->pluck("office_name_bn", "id");
        // return json_encode($subcategories);
        $subcategories = GovCaseOffice::where("level", $id)->pluck("office_name_bn", 'doptor_office_id');
        // dd($subcategories);
        return json_encode($subcategories);
    }
    public function getDependentChildOffice($id)
    {
        // $subcategories = GovCaseOffice::where("parent", $id)->pluck("office_name_bn", "id");
        // return json_encode($subcategories);

        $subcategories = GovCaseOffice::where("parent_office_id", $id)->pluck("office_name_bn",'doptor_office_id');
        return json_encode($subcategories);
    }
    public function getDependentMouja($id)
    {
        $subcategories = DB::table("mouja")->where("upazila_id", $id)->pluck("mouja_name_bn", "id");
        return json_encode($subcategories);
    }

    public function doptor_user_management(Request $request)
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        // $query = DB::table('users')->orderBy('id', 'DESC')
        //     ->join('roles', 'users.role_id', '=', 'roles.id')
        //     ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
        //     ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
        //     ->where('users.is_gov', 1);

        $query = DB::table('users')->orderBy('id', 'DESC')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
        ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
        ->where('users.is_gov', 1);

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        $data['users'] = $query->paginate(10)->withQueryString();

        $data['user_role'] = DB::table('roles')->select('id', 'name')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['organoGram'] = '';
        $data['page_title'] = 'দপ্তর ব্যাবহারকারী তালিকা';
        // return $data;
        return view('gov_case.doptor_user_manage.index')
            ->with($data);
    }

    // public function doptor_user_office(Request $request)
    // {
    //     session()->forget('currentUrlPath');
    //     session()->put('currentUrlPath', request()->path());

    //     $doptoOrganogramOffice = '';
    //     if ($request->office_type != null && $request->ministry == null && $request->divOffice == null && $request->office_id != null) {
    //         $doptoOrganogramOffice = $this->organoGramId($request->office_id);
    //     }
    //     if ($request->office_type != null && $request->ministry != null && $request->divOffice == null && $request->office_id != null) {
    //         $doptoOrganogramOffice = $this->organoGramId($request->office_id);
    //     }

    //     if ($request->office_type != null && $request->ministry != null && $request->divOffice == null && $request->office_id != null) {
    //         $doptoOrganogramOffice = $this->organoGramId($request->office_id);
    //     }
    //     if ($request->office_type != null && $request->ministry == null && $request->divOffice != null && $request->office_id != null) {
    //         $doptoOrganogramOffice = $this->organoGramId($request->office_id);
    //     }

    //     $data['organoGram']=json_decode($doptoOrganogramOffice);

    //     $role = array('1', '27');
    //     $roleID = Auth::user()->role_id;
    //     $officeInfo = user_office_info();
    //     $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

    //     //Add Conditions
    //     $query = DB::table('users')->orderBy('id', 'DESC')
    //         ->join('roles', 'users.role_id', '=', 'roles.id')
    //         ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
    //         ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
    //         ->where('users.is_gov', 1);

    //     if (!empty($_GET['office_id'])) {
    //         $query->where('users.office_id', '=', $_GET['office_id']);
    //     }
    //     if (!empty($_GET['role'])) {
    //         $query->where('users.role_id', '=', $_GET['role']);
    //     }

    //     $data['users'] = $query->paginate(10)->withQueryString();

    //     $data['user_role'] = DB::table('roles')->select('id', 'name')
    //         ->whereNotIn('id', $role)
    //         ->where('is_gov', 1)
    //         ->orderBy('sort_order', 'ASC')
    //         ->get();

    //     $data['ministries'] = GovCaseOffice::where('level', 1)->get();
    //     $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

    //     $data['page_title'] = 'ব্যাবহারকারীর তালিকা';
    //    return $data;
    //     return view('gov_case.doptor_user_manage.index')
    //         ->with($data);
    // }

    public function doptor_user_office(Request $request)
    {
        session()->put('currentUrlPath', request()->path());

        $doptoOrganogramOffice = '';

        if ($request->office_type != null && $request->ministry == null && $request->divOffice == null && $request->office_id != null) {
            $doptoOrganogramOffice = $this->organoGramId($request->office_id);
        }
        if ($request->office_type != null && $request->ministry != null && $request->divOffice == null && $request->office_id != null) {
            $doptoOrganogramOffice = $this->organoGramId($request->office_id);
        }

        if ($request->office_type != null && $request->ministry != null && $request->divOffice == null && $request->office_id != null) {
            $doptoOrganogramOffice = $this->organoGramId($request->office_id);
        }
        if ($request->office_type != null && $request->ministry == null && $request->divOffice != null && $request->office_id != null) {
            $doptoOrganogramOffice = $this->organoGramId($request->office_id);
        }

        $data['organoGram'] = json_decode($doptoOrganogramOffice);


        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = DB::table('users')->orderBy('id', 'DESC')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
        ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
        ->where('users.is_gov', 1);

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        // $data['users'] = $query->paginate(10)->withQueryString();

        $data['user_role'] = DB::table('roles')->select('id', 'name_bn', 'name')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['doptorUserManagement'] = DoptorUserManagement::get();

        // $data['users'] = $data['organoGram']->paginate(10)->withQueryString();

        $data['page_title'] = 'দপ্তর ব্যাবহারকারী তালিকা';

        $tableHtml = view('gov_case.doptor_user_manage.doptor_table')->with($data)->render();
        // $tableHtml = view('gov_case.doptor_user_manage.doptor_table')->with($data)->render();

        return response()->json(['tableHtml' => $tableHtml]);
    }

    // public function tokenGenerate($user_id)
    // {
    //     $curl = curl_init();
    //     $url="https://apigw.doptor.gov.bd/api/client/login";

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => array('username' => $user_id, 'password' => '8XI1PI12W', 'client_id' => '8XI1PI'),
    //         CURLOPT_HTTPHEADER => array(
    //             'apiKey: 8XI1PI  ',
    //         ),
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);

    //     $responsData = json_decode($response);

    //     return $responsData->data->token;

    // }

    public function organoGramId($id)
    {
        $curl = curl_init();
        $token = session('bearerToken');

        curl_setopt_array($curl, array(

            CURLOPT_URL => DOPTOR_ENDPOINT().'/api/v1/empoffice?office=' . $id,

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
                'apikey: YED1EN',
                'Authorization: Bearer '.$token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function doptorUpdateUserRole(Request $request)
    {
        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');
        $officeId = $request->input('office_id');
        $organoGramId = $request->input('organogram_id');

        $userRole = Role::find($roleId);
        $userDetails = '';

        // $user = User::find($userId);

        //     //// assign role to a user
        // $user->assignRole($user->role);

        if ($userRole) {
            $existingUserRole = DoptorUserManagement::where('organogram_id', $organoGramId)->first();
            if ($existingUserRole) {
                $existingUserRole->update([
                    'office_type' => null,
                    'ministry' => null,
                    'div_office' => null,
                    // 'office_id' => $officeId,
                    'user_role' => $roleId,
                    'status' => 1,
                ]);

                $userDetails = $existingUserRole;
            } else {
                $dataToSave = [
                    'office_type' => null,
                    'ministry' => null,
                    'div_office' => null,
                    'office_id' => $officeId,
                    'user_role' => $roleId,
                    'organogram_id' => $organoGramId,
                    'status' => 1,
                ];

                $userDetails = DoptorUserManagement::create($dataToSave);
            }
            return response()->json(['success' => true, 'message' => 'User role updated successfully', 'id' => $organoGramId, 'roleDetails' => $userDetails]);
        }

    }
}
