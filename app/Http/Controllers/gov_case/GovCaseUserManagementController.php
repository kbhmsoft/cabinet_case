<?php

namespace App\Http\Controllers\gov_case;
use App\Http\Controllers\Controller;

use \Auth;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator,Redirect,Response;



class GovCaseUserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $roleID = Auth::user()->role_id;
        $officeInfo = user_office_info();
        // dd($officeInfo);
        // All user list
        // $users = UserManagement::latest()->paginate(5);
        if($roleID == 1 || $roleID == 2 || $roleID == 3 || $roleID == 4 || $roleID == 27 || $roleID == 28 ){
            $users= DB::table('users')
                            ->orderBy('id','DESC')
                            ->join('role', 'users.role_id', '=', 'role.id')
                            ->join('office', 'users.office_id', '=', 'office.id')
                            ->leftJoin('district', 'office.district_id', '=', 'district.id')
                            ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
                            ->select('users.*', 'role.role_name', 'office.office_name_bn', 'district.district_name_bn', 'upazila.upazila_name_bn')
                            ->where('users.is_gov', 1)
                            ->paginate(10);
        }else{                    
            $users= DB::table('users')
                            ->orderBy('id','DESC')
                            ->join('role', 'users.role_id', '=', 'role.id')
                            ->join('office', 'users.office_id', '=', 'office.id')
                            ->leftJoin('district', 'office.district_id', '=', 'district.id')
                            ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
                            ->select('users.*', 'role.role_name', 'office.office_name_bn', 'district.district_name_bn', 'upazila.upazila_name_bn')
                            ->where('office.id', $officeInfo->office_id)
                            ->orWhere('office.parent', $officeInfo->office_id)
                            ->paginate(10);
        }
        $page_title = 'ইউজার ম্যানেজমেন্ট তালিকা';

        return view('gov_case.user_manage.index', compact('page_title','users'))
        ->with('i', (request()->input('page',1) - 1) * 10); 
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
        $data['roles'] = DB::table('role')
        ->select('id', 'role_name')
        ->whereNotIn('id', $role)
        ->where('is_gov', 1)
        ->orderBy('sort_order', 'ASC')
        ->get(); 
        if($roleID == 1 || $roleID == 2 || $roleID == 3 || $roleID == 4 || $roleID == 27 || $roleID == 28 ){
            $data['offices'] = DB::table('office')
            ->leftJoin('district', 'office.district_id', '=', 'district.id')
            ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
            ->select('office.id', 'office.office_name_bn', 'district.district_name_bn', 'upazila.upazila_name_bn')
            ->where('office.is_gov', 1)
            ->get();                                
        }else{
        $data['offices'] = DB::table('office')
        ->leftJoin('district', 'office.district_id', '=', 'district.id')
        ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
        ->select('office.id', 'office.office_name_bn', 'district.district_name_bn', 'upazila.upazila_name_bn')
        ->where('office.district_id', $officeInfo->district_id)
        ->get();   
        }

        // dd($case_type);
        // $data['subcategories'] = DB::table("mouja")->where("upazila_id",38)->pluck("mouja_name_bn","id");

        $data['page_title'] = 'নতুন ইউজার এন্ট্রি ফরম';

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
            'username' => 'required', 'max:100',
            'role_id' => 'required', 'unique:users',
            'office_id' => 'required',            
            /*'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',            
            'mobile_no' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users', */           
            'password' => 'required',            
            ],
            [
            'name.required' => 'পুরো নাম লিখুন',
            'username.required' => 'ইউজার নাম লিখুন',
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

         return redirect()->route('user-management.index')->with('success','সাফল্যের সাথে সংযুক্তি সম্পন্ন হয়েছে');
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
                        ->join('role', 'users.role_id', '=', 'role.id')
                        ->join('office', 'users.office_id', '=', 'office.id')
                        ->leftJoin('district', 'office.district_id', '=', 'district.id')
                        ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
                        ->select('users.*', 'role.role_name', 'office.office_name_bn', 
                            'district.district_name_bn', 'upazila.upazila_name_bn')
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
                        ->join('role', 'users.role_id', '=', 'role.id')
                        ->join('office', 'users.office_id', '=', 'office.id')
                        ->leftJoin('district', 'office.district_id', '=', 'district.id')
                        ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
                        ->select('users.*', 'role.role_name', 'office.office_name_bn', 
                            'district.district_name_bn', 'upazila.upazila_name_bn')
                        ->where('users.id',$id)
                        ->get()->first();
                  // dd($userManagement);     
        $data['roles'] = DB::table('role')
        ->select('id', 'role_name')
        ->get(); 

        $data['offices'] = DB::table('office')
        ->leftJoin('district', 'office.district_id', '=', 'district.id')
        ->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
        ->select('office.id', 'office.office_name_bn', 'district.district_name_bn', 'upazila.upazila_name_bn')/*
        ->where('office.district_id', 38)*/
        ->get();
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
        return redirect()->route('user-management.index')
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
