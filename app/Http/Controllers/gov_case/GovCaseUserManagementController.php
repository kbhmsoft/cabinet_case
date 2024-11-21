<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use App\Models\gov_case\GovCaseOffice;
use App\Models\gov_case\GovCaseOfficeType;
use App\Models\Role;
use App\Models\User;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Redirect;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Response;

class GovCaseUserManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create_new_user', ['only' => ['create']]);

        View::share('notification_count', 0);
        View::share('case_status', array());
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
        $officeID = userInfo()->office_id;
        if ($roleID == 27) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        } elseif ($roleID == 29 || $roleID == 31) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [1, 2, 5])->get();
        } elseif ($roleID == 32 || $roleID == 41) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [5])->get();
        }

        // Parent office and corresponding child office
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

        //Add Conditions
        if ($roleID == 27) {
            $query = DB::table('users')->orderBy('id', 'DESC')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
                ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
                ->where('users.role_id', '!=', 42)
                ->where('users.is_gov', 1);

            // For Ministry Admin
        } else {
            $query = DB::table('users')
                ->orderBy('id', 'DESC')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
                ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
                ->whereIn('users.office_id', $finalOfficeIds)
                ->whereNotIn('users.role_id', [27, 42])
                ->where('users.is_gov', 1);
        }

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        // $data['users'] = $query->paginate(10)->withQueryString();
        $data['users'] = $query->get();

        $data['user_role'] = DB::table('roles')->select('id', 'name', 'name_bn')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        ///////// start run script
        // ***** assing role for all users
        // $userItem = User::where('is_gov', 1)->get();
        // foreach($userItem as $user){
        //     $user->assignRole($user->role);
        // }
        ///////// run script
        // return $data;

        $data['page_title'] = 'ব্যবহারকারীর তালিকা';

        return view('gov_case.user_manage.index')
            ->with($data);
    }

    public function officeWiseUsers()
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $officeID = userInfo()->office_id;
        if ($roleID == 27) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        } elseif ($roleID == 29 || $roleID == 31) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [1, 2, 5])->get();
        } elseif ($roleID == 32 || $roleID == 41) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [5])->get();
        }

        // Parent office and corresponding child office
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

        $data['offices'] = DB::table('gov_case_office')->get();
        //Add Conditions
        if ($roleID == 27) {
            $query = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
                ->select('users.*', 'roles.name_bn as roleName', 'gov_case_office.office_name_bn')
                // ->whereNotIn('users.role_id', [42, 43])
                ->where('users.is_gov', 1)
                ->orderBy('users.id', 'DESC');

            // For Ministry Admin
        } else {
            $query = DB::table('users')

                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
                ->select('users.*', 'roles.name_bn as roleName', 'gov_case_office.office_name_bn')
                ->whereIn('users.office_id', $finalOfficeIds)
                ->whereNotIn('users.role_id', [27, 42, 43])
                ->where('users.is_gov', 1)
                ->orderBy('users.id', 'DESC');
        }

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        // $data['users'] = $query->paginate(10)->withQueryString();
        $data['users'] = $query->get();

        $data['user_role'] = DB::table('roles')->select('id', 'name', 'name_bn')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();


        $data['page_title'] = 'ব্যবহারকারীর তালিকা';

        return view('gov_case.user_manage.office_wise_users')
            ->with($data);
    }
    public function officeWiseUsersExternal()
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $officeID = userInfo()->office_id;
        if ($roleID == 27) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        } elseif ($roleID == 29 || $roleID == 31) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [1, 2, 5])->get();
        } elseif ($roleID == 32 || $roleID == 41) {
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [5])->get();
        }

        // Parent office and corresponding child office
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
        $data['offices'] = DB::table('gov_case_office')->get();
        //Add Conditions
        if ($roleID == 27) {
            $query = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
                ->select('users.*', 'roles.name_bn as roleName', 'gov_case_office.office_name_bn')
                ->whereNotIn('users.role_id', [42, 43])
                ->where('users.is_gov', 1)
                ->whereNull('users.doptor_user_id') // Only include users where doptor_user_id is null
                ->orderBy('users.office_id', 'DESC');
        } else {
            $query = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
                ->select('users.*', 'roles.name_bn as roleName', 'gov_case_office.office_name_bn')
                ->whereIn('users.office_id', $finalOfficeIds)
                ->whereNotIn('users.role_id', [27, 42, 43])
                ->where('users.is_gov', 1)
                ->whereNull('users.doptor_user_id') // Only include users where doptor_user_id is null
                ->orderBy('users.office_id', 'DESC');
        }

        if (!empty($_GET['office_id'])) {
            $query->where('users.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('users.role_id', '=', $_GET['role']);
        }

        // $data['users'] = $query->paginate(10)->withQueryString();
        $data['users'] = $query->get();

        $data['user_role'] = DB::table('roles')->select('id', 'name', 'name_bn')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();

        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

        ///////// start run script
        // ***** assing role for all users
        // $userItem = User::where('is_gov', 1)->get();
        // foreach($userItem as $user){
        //     $user->assignRole($user->role);
        // }
        ///////// run script
        // return $data['offices'];

        $data['page_title'] = 'অনুমোদিত ই-নথি বহির্ভূত ব্যবহারকারী তালিকা';

        return view('gov_case.user_manage.office_wise_users_external')
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
        $officeId = Auth::user()->office_id;

        $role = array('1', '27');
        if ($roleID == 27) {
            $data['roles'] = DB::table('roles')
                ->select('id', 'name', 'name_bn')
                ->whereNotIn('id', $role)
                ->where('is_gov', 1)
                ->orderBy('sort_order', 'ASC')
                ->get();
            $data['offices'] = DB::table('gov_case_office')
                ->select('gov_case_office.*')
            // ->where('level', 1)
                ->get();
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        } elseif ($roleID == 29 || $roleID == 31) {
            $data['roles'] = DB::table('roles')
                ->select('id', 'name', 'name_bn')
                ->whereIn('id', [32, 41, 45])
                ->where('is_gov', 1)
                ->orderBy('id', 'ASC')
                ->get();
            $childOfficeIds = [];
            $childOfficeQuery = DB::table('gov_case_office')
                ->select('id', 'doptor_office_id')
                ->where('parent_office_id', $officeId)->get();
            // dd($childOfficeQuery);
            foreach ($childOfficeQuery as $childOffice) {
                $childOfficeIds[] = $childOffice->doptor_office_id;
            }

            $finalOfficeIds = [];
            if (empty($childOfficeIds)) {
                $finalOfficeIds[] = $officeId;
            } else {
                $finalOfficeIds[] = $officeId;
                $finalOfficeIds = array_merge($finalOfficeIds, $childOfficeIds);
            }
            $data['offices'] = DB::table('gov_case_office')
                ->select('gov_case_office.*')
                ->whereIn('doptor_office_id', $finalOfficeIds)
                ->get();
            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [1, 2, 5])->get();
        } else {

            $data['roles'] = DB::table('roles')
                ->select('id', 'name', 'name_bn')
                ->whereIn('id', [45])
                ->where('is_gov', 1)
                ->orderBy('sort_order', 'ASC')
                ->get();

            $data['offices'] = DB::table('gov_case_office')
                ->select('gov_case_office.*')
                ->where('parent_office_id', $officeId)
                ->get();

            $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->whereIn('id', [5])->get();
        }

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
        $data['user_role'] = DB::table('roles')->select('id', 'name', 'name_bn')->whereNotIn('id', $role)->where('is_gov', 1)->orderBy('sort_order', 'ASC')->get();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();

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

        $data['page_title'] = 'নতুন ব্যবহারকারী এন্ট্রি ফরম';
        // return $data;
        return view('gov_case.user_manage.add')->with($data);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'office_type' => 'nullable',
                'ministry' => 'nullable',
                'div_office' => 'nullable',
                'role_id' => 'required',
                'email' => 'nullable|unique:users,email',
                'mobile_no' => 'required|unique:users,mobile_no',
                'office_id' => 'required',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                ],
            ],
            [
                'name.required' => 'পুরো নাম লিখুন',
                'email.unique' => 'ইমেইলটি ইতিমধ্যে সিস্টেমে বিদ্যমান রয়েছে',
                // 'email.required' => 'ইমেইল লিখুন',
                'mobile_no.unique' => 'মোবাইল নাম্বারটি ইতিমধ্যে সিস্টেমে বিদ্যমান রয়েছে',
                'role_id.required' => 'ভূমিকা নির্বাচন করুন',
                'office_id.required' => 'অফিস নির্বাচন করুন',
                'password.required' => 'পাসওয়ার্ড লিখুন',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'ministry' => $request->ministry,
            'div_office' => $request->divOffice,
            'office_type' => $request->office_type,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'office_id' => $request->office_id,
            'unit_name_bn' => $request->unit_name_bn,
            'designation' => $request->designation,
            'is_gov' => 1,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            $role = Role::find($request->role_id);
            if ($role) {
                $user->syncRoles([$role]);
            }
        }

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
            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
            ->select('users.*', 'roles.name as roles_name', 'gov_case_office.office_name_bn')
            ->where('users.id', $id)
            ->get()->first();

        $data['roles'] = DB::table('roles')
            ->select('id', 'name','name_bn')
            ->get();


        $data['page_title'] = 'ব্যবহারকারীর বিস্তারিত';
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
        // return ($id);
        $data['userManagement'] = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.doptor_office_id')
            ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
            ->where('users.id', $id)
            ->get()->first();
        // dd($data['userManagement']);
        $data['roles'] = DB::table('roles')
            ->select('id', 'name','name_bn')
            ->get();

        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();
        // dd($data['userManagement'] );

        $data['offices'] = GovCaseOffice::get();
        $data['page_title'] = 'ইউজার ইনফর্মেশন সংশোধন ফরম';
        // return $data;
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
        // return $request->all();
        $request->validate(
            [
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
                'new_password' => ['nullable'],
                'new_confirm_password' => ['nullable','same:new_password'],
            ],
            [
                'name.required' => 'পুরো নাম লিখুন',
                // 'username.required' => 'ইউজার নাম লিখুন',
                'role_id.required' => 'ভূমিকা নির্বাচন করুন',
                'office_id.required' => 'অফিস নির্বাচন করুন',

            ]
        );

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

        $userUpdate = DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
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
                'password' => Hash::make($request->new_password),
            ]);

        $role = Role::where('id', $request->role_id)->first();
        $user = User::where('id', $id)->first();

        // clear all ruole for this user
        $user->syncRoles([]);

        // assign role to the user
        if ($user != null && $role != null) {
            $user->assignRole($role);
        }

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
        // return $userManagement->id;
        $id = $userManagement->id;
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('cabinet.user-management.index')
            ->with('success', 'ইউজার ডাটা সফলভাবে মুছে ফেলা হয়েছে');
    }

    public function assignedENothiUserManagement()
    {
        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $role = array('1', '27');
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        $data['office_types'] = GovCaseOfficeType::orderby('id', 'ASC')->get();

        //Add Conditions
        $query = DB::table('doptor_user_managements')->orderBy('id', 'DESC')
            ->join('roles', 'doptor_user_managements.user_role', '=', 'roles.id')
            ->join('gov_case_office', 'doptor_user_managements.office_id', '=', 'gov_case_office.doptor_office_id')
            ->select('doptor_user_managements.*', 'roles.name_bn as roleName', 'gov_case_office.office_name_bn')
            ->where('doptor_user_managements.status', 1)
            ->where('doptor_user_managements.user_role', '!=', 42)
            ->orderby('id', 'DESC');

        if (!empty($_GET['office_id'])) {
            $query->where('doptor_user_managements.office_id', '=', $_GET['office_id']);
        }
        if (!empty($_GET['role'])) {
            $query->where('doptor_user_managements.role_id', '=', $_GET['role']);
        }

        // $data['users'] = $query->paginate(10)->withQueryString();
        $data['users'] = $query->get();
    
        $data['user_role'] = DB::table('roles')->select('id', 'name', 'name_bn')
            ->whereNotIn('id', $role)
            ->where('is_gov', 1)
            ->orderBy('sort_order', 'ASC')
            ->get();
        $data['offices'] = GovCaseOffice::orderby('id', 'DESC');
        $data['ministries'] = GovCaseOffice::where('level', 1)->get();
        $data['divOffices'] = GovCaseOffice::where('level', 3)->get();



        $data['page_title'] = 'অনুমোদিত
        ই-নথি ব্যবহারকারী তালিকা';

        return view('gov_case.user_manage.e-nothi-assigned-user-index')
            ->with($data);
    }
}
