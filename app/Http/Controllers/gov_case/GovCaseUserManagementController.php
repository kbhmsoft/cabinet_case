<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;

use \Auth;
use App\Models\UserManagement;
use App\Models\gov_case\GovCaseOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator,Redirect,Response;



class GovCaseUserManagementController extends Controller
{

    public function __construct(){
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
         
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        if($roleID == 1 || $roleID == 2 || $roleID == 3 || $roleID == 4 || $roleID == 27 || $roleID == 28 ){
            $data['users']= DB::table('users')
                            ->orderBy('id','DESC')
                            ->join('roles', 'users.role_id', '=', 'roles.id')
                            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
                            
                            ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
                            ->where('users.is_gov', 1)
                            ->paginate(10);
        }else{                    
            $data['users']= DB::table('users')
                            ->orderBy('id','DESC')
                            ->join('roles', 'users.role_id', '=', 'roles.id')
                            ->join('gov_case_office', 'users.office_id', '=', 'gov_case_office.id')
                            
                            ->select('users.*', 'roles.name as roleName', 'gov_case_office.office_name_bn')
                            ->where('gov_case_office.id', $officeInfo->office_id)
                            ->orWhere('gov_case_office.parent', $officeInfo->office_id)
                            ->paginate(10);
        }
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
        $role = array('1','27');
        $data['roles'] = DB::table('roles')
        ->select('id', 'name')
        ->whereNotIn('id', $role)
        ->where('is_gov', 1)
        ->orderBy('sort_order', 'ASC')
        ->get(); 
        if($roleID == 1 || $roleID == 2 || $roleID == 3 || $roleID == 4 || $roleID == 27 || $roleID == 28 ){
            $data['offices'] = $data['offices'] = GovCaseOffice::get();  
        }else{
        $data['offices'] = DB::table('gov_case_office')
        
        ->select('gov_case_office.id', 'gov_case_office.office_name_bn')
        ->where('gov_case_office.district_id', $officeInfo->district_id)
        ->get();   
        }

        // dd($case_type);
        // $data['subcategories'] = DB::table("mouja")->where("upazila_id",38)->pluck("mouja_name_bn","id");

        $data['page_title'] = 'নতুন ব্যাবহারকারী এন্ট্রি ফরম';

        return view('gov_case.user_manage.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
       $request->validate([
            'name' => 'required',
            // 'username' => 'required', 'max:100',
            'role_id' => 'required',
            'email' => 'required|unique:users,email',
            'office_id' => 'required',            
            /*'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',            
            'mobile_no' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users', */           
            'password' => 'required',            
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
            'name'=>$request->name,
            'username' =>$request->username,
            'mobile_no' =>$request->mobile_no,
            'email' =>$request->email,
            'role_id' =>$request->role_id,
            'office_id' =>$request->office_id,
            'is_gov' =>1,
            'password' =>Hash::make($request->password)
            
       ]);

         return redirect()->route('cabinet.user-management.index')->with('success','সাফল্যের সাথে সংযুক্তি সম্পন্ন হয়েছে');
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
                        ->select('users.*', 'roles.name', 'gov_case_office.office_name_bn')
                        ->where('users.id',$id)
                        ->get()->first();
                  // dd($userManagement);     

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
                        ->where('users.id',$id)
                        ->get()->first();
                  // dd($userManagement);     
        $data['roles'] = DB::table('roles')
        ->select('id', 'name')
        ->get(); 

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
    public function update(Request $request,$id='')
    {
         $request->validate([
            'name' => 'required',
            'username' => 'required', 'unique:users', 'max:100',
            'role_id' => 'required',
            'office_id' => 'required',            
            // 'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users',            
            // 'mobile_no' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',            
            'signature' => 'max:10240',             
            ],
            [
            'name.required' => 'পুরো নাম লিখুন',
            'username.required' => 'ইউজার নাম লিখুন',
            'role_id.required' => 'ভূমিকা নির্বাচন করুন',
            'office_id.required' => 'অফিস নির্বাচন করুন',
            
            ]);

        // File upload
        if($file = $request->file('signature')){
            $fileName = $id.'_'.time().'.'.$request->signature->extension();
            $request->signature->move(public_path('uploads/signature'), $fileName);
        }else{
            $fileName = NULL;
        }
        if($file = $request->file('pro_pic')){
            $profilePic = $id.'_'.time().'.'.$request->pro_pic->extension();
            $request->pro_pic->move(public_path('uploads/profile'), $profilePic);
        }else{
            $profilePic = NULL;
        }

         DB::table('users')
            ->where('id', $id)
            ->update(['name'=>$request->name,
            'username' =>$request->username,
            'mobile_no' =>$request->mobile_no,
            'signature' =>$fileName,
            'profile_pic' =>$profilePic,
            'email' =>$request->email,
            'role_id' =>$request->role_id,
            'office_id' =>$request->office_id,
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
