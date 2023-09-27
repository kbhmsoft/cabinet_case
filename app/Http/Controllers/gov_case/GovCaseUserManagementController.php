<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseOfficeType;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Redirect;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Response;

class GovCaseUserManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create_new_user', ['only' => ['create']]);
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

        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = DB::table('users')->orderBy('id', 'DESC')->join('roles', 'users.role_id', '=', 'roles.id')->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')->where('users.is_gov', 1);
        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        $data['users'] = $query->paginate(10)->withQueryString();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->whereNotIn('id', $role)->where('is_gov', 1)->orderBy('sort_order', 'ASC')->get();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        $data['page_title'] = 'ব্যাবহারকারীর তালিকা';
        // return $users;
        return view('gov_case.user_manage.index')
            ->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $role = array('1', '27');
        $data['roles'] = DB::table('roles')
            ->select('id', 'name')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        $query = DB::table('users')->orderBy('id', 'DESC')->join('roles', 'users.role_id', '=', 'roles.id')->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')->where('users.is_gov', 1);
        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        $data['users'] = $query->paginate(10)->withQueryString();
        $data['user_role'] = DB::table('roles')->select('id', 'name')->whereNotIn('id', $role)->where('is_gov', 1)->orderBy('sort_order', 'ASC')->get();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

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

        $data['page_title'] = 'নতুন ব্যাবহারকারী এন্ট্রি ফরম';
        // dd($data);

        return view('gov_case.user_manage.add')->with($data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'office_type' => 'nullable',
            'ministry' => 'nullable',
            'div_office' => 'nullable',
            // 'username' => 'required', 'max:100',
            'role_id' => 'required',
            'email' => 'required|unique:users,email',
            'office_id' => 'required',
            /*'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'mobile_no' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users', */
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                // 'regex:/[@$!%*#?&]/',
            ],
        ],
            [
                'name.required' => 'পুরো নাম লিখুন',
                'email.unique' => 'ইমেইলটি ইতিমধ্যে সিস্টেমে বিদ্যমান রয়েছে',
                'email.required' => 'ইমেইল লিখুন',
                'role_id.required' => 'ভূমিকা নির্বাচন করুন',
                'office_id.required' => 'অফিস নির্বাচন করুন',
                'password.required' => 'পাসওয়ার্ড লিখুন',
            ]);

        DB::table('users')->insert([
            'name' => $request->name,
            // 'username' =>$request->username,
            'ministry' => $request->ministry,
            'div_office' => $request->divOffice,
            'office_type' => $request->office_type,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'office_id' => $request->office_id,
            'is_gov' => 1,
            'password' => Hash::make($request->password),

        ]);
        // dd($request->all());

        return redirect()->route('cabinet.user-management.index')->with('success', 'সাফল্যের সাথে সংযুক্তি সম্পন্ন হয়েছে');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    // public function show(UserManagement $userManagement)
    public function show($id = '')
    {
        $data['userManagement'] = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
            ->select('users.*', 'roles.name as roles_name', 'gov_case_office.office_name_bn')
            ->where('users.id', $id)
            ->get()->first();
        // dd($data['userManagement']);

        $data['roles'] = DB::table('roles')
            ->select('id', 'name')
            ->get();

        // dd($data['roles']);
        $data['page_title'] = 'ব্যাবহারকারীর বিস্তারিত';
        return view('gov_case.user_manage.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['userManagement'] = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
            ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
            ->where('users.id', $id)
            ->get()->first();
        // dd($userManagement);
        $data['roles'] = DB::table('roles')
            ->select('id', 'name')
            ->get();

        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();
        // dd($data['userManagement'] );

        $data['offices'] = GovCaseOffice::get();
        $data['page_title'] = 'ইউজার ইনফর্মেশন সংশোধন ফরম';
        return view('gov_case.user_manage.edit')->with($data);
        // return view('gov_case.user_manage.edit', compact('userManagement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = '')
    {
        $request->validate([
            'name' => 'required',
            // 'username' => 'required', 'unique:users', 'max:100',
            'role_id' => 'required',
            'office_type' => 'nullable',
            'ministry' => 'nullable',
            'div_office' => 'nullable',
            'office_id' => 'required',
            // 'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',
            // 'mobile_no' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'signature' => 'max:10240',
        ],
            [
                'name.required' => 'পুরো নাম লিখুন',
                // 'username.required' => 'ইউজার নাম লিখুন',
                'role_id.required' => 'ভূমিকা নির্বাচন করুন',
                'office_id.required' => 'অফিস নির্বাচন করুন',

            ]);

        // File upload
        if ($file = $request->file('signature')) {
            $fileName = $id . '_' . time() . '.' . $request->signature->extension();
            $request->signature->move(public_path('uploads/signature'), $fileName);
        } else {
            $fileName = null;
        }
        if ($file = $request->file('pro_pic')) {
            $profilePic = $id . '_' . time() . '.' . $request->pro_pic->extension();
            $request->pro_pic->move(public_path('uploads/profile'), $profilePic);
        } else {
            $profilePic = null;
        }

        DB::table('users')
            ->where('id', $id)
            ->update(['name' => $request->name,
                'username' => $request->username,
                'mobile_no' => $request->mobile_no,
                'office_type' => $request->office_type,
                'ministry' => $request->ministry,
                'div_office' => $request->div_office,
                'signature' => $fileName,
                'profile_pic' => $profilePic,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'office_id' => $request->office_id,
            ]);
        return redirect()->route('cabinet.user-management.index')
            ->with('success', 'ইউজার ডাটা সফলভাবে আপডেট হয়েছে');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserManagement $userManagement)
    {
        //
    }
}
