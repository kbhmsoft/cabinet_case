<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Permission;
use Auth;

class AclController extends Controller
{
     
    // for roles 
    public function roleManagement(){
        $roles = Role::where('status', 1)->paginate(25);
        return view('gov_case.roles.create', compact('roles'));
    }

    public function storeRole(Request $request){
        $this->validate($request, [
            'role_name' => 'required|unique:roles'
        ]);

        Role::create([
            'role_name' => $request->role_name,
            'user_id' => Auth::user()->id,
            'status' => 1,
        ]);

         return back()->with('success','সাফল্যের সাথে সংরক্ষণ সম্পন্ন হয়েছে');
    }

    public function updateRole(Request $request){
        $this->validate($request, [
            'role_name' => 'required|unique:roles'
        ]);

        Role::where('id', $request->role_id)->update([
            'role_name' => $request->role_name,
            'status' => $request->status,
        ]);

         return back()->with('success','সাফল্যের সাথে সংশোধন সম্পন্ন হয়েছে');
    }

    public function roleItemDelete(Request $request, $id){
        Role::where('id', $id)->delete();

         return back()->with('success','ভুমিকাটি মুছে ফেলা হয়েছে');
    }
     
    // end for roles 



    // for permissions 
    public function permissionManagement(){
        $permissions = Permission::where('status', 1)->paginate(25);

        return view('gov_case.permissions.create', compact('permissions'));
    }

    public function storePermission(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:permissions'
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->name,
            'user_id' => Auth::user()->id,
            'status' => 1,
        ]);

         return back()->with('success','সাফল্যের সাথে সংরক্ষণ সম্পন্ন হয়েছে');
    }

    public function updatePermission(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:permissions'
        ]);

        Permission::where('id', $request->permission_id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

         return back()->with('success','সাফল্যের সাথে সংশোধন সম্পন্ন হয়েছে');
    }

    public function permissionItemDelete(Request $request, $id){
        Permission::where('id', $id)->delete();

         return back()->with('success','ভুমিকাটি মুছে ফেলা হয়েছে');
    }


    // end for permissions 




}
